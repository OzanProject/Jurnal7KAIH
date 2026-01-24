<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $schoolId;

    public function __construct()
    {
        $this->schoolId = Auth::user()->school_id;
    }

    public function collection()
    {
        return Student::whereHas('user', function($q) {
            $q->where('school_id', $this->schoolId);
        })
        ->join('classes', 'students.class_id', '=', 'classes.id')
        ->select('students.*')
        ->with(['user', 'classRoom'])
        ->orderBy('classes.nama_kelas', 'asc')
        ->orderBy('students.nama', 'asc')
        ->get();
    }

    public function map($student): array
    {
        return [
            $student->nama,
            $student->nis,
            $student->nisn,
            $student->classRoom->nama_kelas ?? '-',
            $student->gender == 'L' ? 'Laki-Laki' : 'Perempuan',
            $student->tempat_lahir,
            $student->tanggal_lahir,
            $student->alamat,
            $student->user->email,
            $student->nama_ayah,
            $student->pekerjaan_ayah,
            $student->nama_ibu,
            $student->pekerjaan_ibu,
            $student->no_hp_ortu,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'NIS',
            'NISN',
            'Kelas',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Email',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'No HP Ortu',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
