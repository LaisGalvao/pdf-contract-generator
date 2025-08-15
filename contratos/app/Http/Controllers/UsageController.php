<?php
// app/Http/Controllers/UsageController.php
namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UsageController extends Controller
{
    public function me(Request $req)
    {
        $user = $req->attributes->get('auth_user');
        $count = Contract::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        return ['month_used' => $count, 'month_limit' => $user->plan === 'free' ? 3 : null];
    }
}
