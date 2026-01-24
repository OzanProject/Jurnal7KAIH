<x-backend-layout>
    <x-slot name="header">
        Pengaturan Aplikasi
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">Umum</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-controls="appearance" aria-selected="false">Tampilan</button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab" aria-controls="system" aria-selected="false">Sistem</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">Email / SMTP</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-4" id="myTabContent">
                        @foreach(['general', 'appearance', 'system', 'email'] as $group)
                         <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $group }}" role="tabpanel" aria-labelledby="{{ $group }}-tab">
                            @php
                                $route = route('admin.settings.update');
                                $labels = [
                                    'habit_1_limit' => 'Batas Waktu Bangun Pagi (Target: Sebelum jam ini)',
                                    'habit_7_limit' => 'Batas Waktu Tidur Cepat (Target: Sebelum jam ini)',
                                    'app_name' => 'Nama Aplikasi',
                                    'app_description' => 'Deskripsi Aplikasi',
                                    'app_logo' => 'Logo Aplikasi',
                                    'app_favicon' => 'Favicon',
                                    'school_name' => 'Nama Sekolah',
                                    'school_address' => 'Alamat Sekolah',
                                    'habit_threshold_sudah' => 'Ambang Batas "Sudah Terbiasa" (%)',
                                    'habit_threshold_cukup' => 'Ambang Batas "Cukup Terbiasa" (%)',
                                    'login_bg_image' => 'Background Login (Gambar)',
                                    'smtp_host' => 'SMTP Host',
                                    'smtp_port' => 'SMTP Port',
                                    'smtp_username' => 'SMTP Username',
                                    'smtp_password' => 'SMTP Password',
                                    'smtp_encryption' => 'Encryption (tls/ssl)',
                                    'mail_from_address' => 'Email Pengirim (From Address)',
                                    'mail_from_name' => 'Nama Pengirim (From Name)',
                                ];
                            @endphp
                            <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                @if(isset($settings[$group]))
                                    @foreach($settings[$group] as $setting)
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">{{ $labels[$setting->key] ?? ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                                            
                                            @if($setting->type == 'text')
                                                <input type="text" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                            @elseif($setting->type == 'textarea')
                                                <textarea name="{{ $setting->key }}" class="form-control" rows="4">{{ $setting->value }}</textarea>
                                            @elseif($setting->type == 'time')
                                                <input type="time" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}" style="max-width: 200px;">
                                            @elseif($setting->type == 'boolean')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="{{ $setting->key }}" value="1" {{ $setting->value == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Aktifkan</label>
                                                </div>
                                                 <!-- Hidden input for unchecked state -->
                                                <input type="hidden" name="{{ $setting->key }}" value="0" disabled> 
                                            @elseif($setting->type == 'image')
                                                @if($setting->value)
                                                    <div class="mb-2">
                                                        <img src="{{ asset($setting->value) }}" alt="" style="height: 50px;">
                                                    </div>
                                                @endif
                                                <input type="file" name="{{ $setting->key }}" class="form-control">
                                            @elseif($setting->type == 'select')
                                                <select name="{{ $setting->key }}" class="form-select">
                                                    @if($setting->key == 'app_timezone')
                                                        <option value="Asia/Jakarta" {{ $setting->value == 'Asia/Jakarta' ? 'selected' : '' }}>WIB (Asia/Jakarta)</option>
                                                        <option value="Asia/Makassar" {{ $setting->value == 'Asia/Makassar' ? 'selected' : '' }}>WITA (Asia/Makassar)</option>
                                                        <option value="Asia/Jayapura" {{ $setting->value == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Asia/Jayapura)</option>
                                                    @elseif($setting->key == 'smtp_encryption')
                                                        <option value="tls" {{ $setting->value == 'tls' ? 'selected' : '' }}>TLS</option>
                                                        <option value="ssl" {{ $setting->value == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                        <option value="null" {{ $setting->value == 'null' ? 'selected' : '' }}>None</option>
                                                    @else
                                                        <option value="{{ $setting->value }}">{{ $setting->value }}</option>
                                                    @endif
                                                </select>
                                            @elseif($setting->type == 'number')
                                                <div class="input-group">
                                                    <input type="number" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                @else
                                    <p class="text-muted">Tidak ada pengaturan di grup ini.</p>
                                @endif
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Simple script to handle checkbox unchecked state sending 0
        document.querySelectorAll('.form-check-input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const hiddenInput = this.parentElement.querySelector('input[type="hidden"]');
                if(hiddenInput) {
                     hiddenInput.disabled = this.checked;
                     if(!this.checked) {
                         hiddenInput.disabled = false;
                         hiddenInput.value = "0";
                     }
                }
            });
        });
    </script>
</x-backend-layout>
