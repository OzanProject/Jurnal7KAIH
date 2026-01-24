<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\ClassRoom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class JournalRecapExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $classId;
    protected $month;
    protected $year;
    protected $filterType;
    protected $semester;

    public function __construct($classId, $month, $year, $filterType = 'month', $semester = 1)
    {
        $this->classId = $classId;
        $this->month = $month;
        $this->year = $year;
        $this->filterType = $filterType;
        $this->semester = $semester;
    }

    public function collection()
    {
        $students = Student::where('class_id', $this->classId)
            ->withCount(['journals' => function($query) {
                $query->whereYear('tanggal', $this->year);
                
                if ($this->filterType == 'semester') {
                     if ($this->semester == 1) {
                         $query->whereMonth('tanggal', '>=', 7)->whereMonth('tanggal', '<=', 12);
                     } else {
                         $query->whereMonth('tanggal', '>=', 1)->whereMonth('tanggal', '<=', 6);
                     }
                } else {
                    $query->whereMonth('tanggal', $this->month);
                }
            }])
            ->orderBy('no_urut')
            ->orderBy('nama')
            ->get();

        $data = [];
        $no = 1;

        foreach ($students as $student) {
            $data[] = [
                'no' => $student->no_urut ?? $no++,
                'nama' => $student->nama,
                'kelas' => $student->classRoom->nama_kelas,
                'total_hari' => $student->journals_count,
            ];
        }

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Kelas',
            'Total Hari Isi Jurnal',
        ];
    }

    public function title(): string
    {
        return 'Rekap Jurnal';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
