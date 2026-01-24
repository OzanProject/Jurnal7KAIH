<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'avatar' => ['nullable', 'image', 'max:1024'],
        ];

        if ($this->user()->role === 'siswa') {
            $rules = array_merge($rules, [
                'nisn' => 'nullable|string|max:50',
                'gender' => 'nullable|in:L,P',
                'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date',
                'alamat' => 'nullable|string',
                'nama_ayah' => 'nullable|string|max:255',
                'pekerjaan_ayah' => 'nullable|string|max:255',
                'nama_ibu' => 'nullable|string|max:255',
                'pekerjaan_ibu' => 'nullable|string|max:255',
                'no_hp_ortu' => 'nullable|string|max:20',
                'parent_user_id' => 'nullable|exists:users,id',
            ]);
        }

        return $rules;
    }
}
