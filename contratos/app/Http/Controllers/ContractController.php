<?php
// app/Http/Controllers/ContractController.php
namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Theme;
use App\Models\Contract;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function list(Request $req)
    {
        $user = $req->attributes->get('auth_user');
        return Contract::where('user_id', $user->id)->latest()->limit(20)->get();
    }

    public function show(Request $req, string $id)
    {
        $user = $req->attributes->get('auth_user');
        $c = Contract::where('user_id', $user->id)->findOrFail($id);
        return $c;
    }

    public function download(Request $req, string $id)
    {
        $user = $req->attributes->get('auth_user');
        $c = Contract::where('user_id', $user->id)->findOrFail($id);
        abort_unless($c->pdf_path && Storage::exists($c->pdf_path), 404, 'PDF não encontrado');
        return response()->file(storage_path("app/{$c->pdf_path}"));
    }

    public function generate(Request $req, PdfService $pdfService)
    {
        $user = $req->attributes->get('auth_user');
        $data = $req->validate([
            'template_id' => 'required|integer|exists:templates,id',
            'theme_id'    => 'nullable|integer|exists:themes,id',
            'fields'      => 'required|array',
            'options.logo_url' => 'nullable|url'
        ]);

        // Quota Freemium (3/mês)
        $monthStart = Carbon::now()->startOfMonth();
        $count      = Contract::where('user_id', $user->id)->where('created_at', '>=', $monthStart)->count();
        if ($user->plan === 'free' && $count >= 3) {
            return response()->json(['message' => 'Limite mensal do plano Free atingido.'], 402);
        }

        $template = Template::findOrFail($data['template_id']);
        if ($template->premium_only && $user->plan === 'free') {
            return response()->json(['message' => 'Template premium. Faça upgrade.'], 402);
        }

        $themeCss = '';
        if (!empty($data['theme_id'])) {
            $theme = Theme::findOrFail($data['theme_id']);
            if ($theme->plan === 'premium' && $user->plan === 'free') {
                return response()->json(['message' => 'Tema premium. Faça upgrade.'], 402);
            }
            $themeCss = $theme->css;
        }

        $fileName = Str::uuid()->toString();
        $path = $pdfService->generate($template->blade_view, [
            'fields' => $data['fields'],
            'logo_url' => $data['options']['logo_url'] ?? null
        ], $themeCss, $fileName);

        $contract = Contract::create([
            'id'          => $fileName,
            'user_id'     => $user->id,
            'template_id' => $template->id,
            'theme_id'    => $data['theme_id'] ?? null,
            'data'        => $data['fields'],
            'pdf_path'    => $path,
            'status'      => 'generated'
        ]);

        return response()->json($contract, 201);
    }
}
