<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class StudentsTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return new Collection([
            // Example Row
            [
                '1',
                'Nama Siswa Contoh',
                'L',
                '12345',
                '0012345678',
                'siswa@sekolah.com',
                'password123',
                'XA'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'Jenis Kelamin',
            'NIS',
            'NISN',
            'Email',
            'Password',
            'Kelas'
        ];
    }
}
