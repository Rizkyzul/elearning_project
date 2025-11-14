<?php

namespace App\Exports;

use App\Models\Nilai;
use App\Models\Matkul;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NilaiPerMatkulExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $matkul;

    public function __construct(Matkul $matkul)
    {
        $this->matkul = $matkul;
    }

    public function collection()
    {
        return Nilai::where('matkul_id', $this->matkul->id)
            ->with(['mahasiswa.user'])
            ->get();
    }

    public function map($nilai): array
    {
        $nilaiAkhir = 
            ($nilai->nilai_tugas * 0.20) +
            ($nilai->nilai_uts * 0.40) +
            ($nilai->nilai_uas * 0.40);

        $grade = match (true) {
            $nilaiAkhir >= 80 => 'A',
            $nilaiAkhir >= 70 => 'B',
            $nilaiAkhir >= 60 => 'C',
            $nilaiAkhir >= 50 => 'D',
            default => 'E',
        };

        return [
            $nilai->mahasiswa->nim,
            $nilai->mahasiswa->user->name ?? '-',
            $nilai->nilai_tugas,
            $nilai->nilai_uts,
            $nilai->nilai_uas,
            number_format($nilaiAkhir, 2),
            $grade,
        ];
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama Mahasiswa',
            'Tugas',
            'UTS',
            'UAS',
            'Nilai Akhir',
            'Grade',
        ];
    }
}
