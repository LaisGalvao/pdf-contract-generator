<?php
// app/Services/PdfService.php
namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generate(string $bladeView, array $data, string $themeCss, string $fileName): string
    {
        $html = view($bladeView, ['data' => $data, 'themeCss' => $themeCss])->render();
        $pdf  = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        $path = "contracts/{$fileName}.pdf";
        Storage::disk(config('filesystems.default'))->put($path, $pdf->output());
        return $path;
    }
}
