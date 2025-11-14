<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NilaiPerMatkulExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Matkul $matkul)
    {
        return view('dosen.nilai.index', [
            'matkul' => $matkul
        ]);
    }

    public function exportExcel(Matkul $matkul)
    {
        $filename = 'Nilai_' . $matkul->kode_matkul . '_' . now()->timestamp . '.xlsx';

        return Excel::download(new NilaiPerMatkulExport($matkul), $filename);
    }

    public function exportPdf(Matkul $matkul)
    {
        $dosen = Auth::user()->dosenProfile;
        $dosen?->load('user'); // aman dari null

        $nilaiList = $matkul->nilai()
            ->with('mahasiswa.user')
            ->get();

        $data = [
            'matkul' => $matkul,
            'dosen' => $dosen,
            'nilaiList' => $nilaiList,
        ];

        $pdf = Pdf::loadView('dosen.nilai.pdf', $data);

        $filename = 'Nilai_' . $matkul->kode_matkul . '_' . now()->timestamp . '.pdf';

        return $pdf->download($filename);
    }
}
