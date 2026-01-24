<x-backend-layout>
    <x-slot name="header">
        Isi Jurnal Harian
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    
                    <form action="{{ route('journals.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="tanggal" class="form-label fw-bold">Pilih Tanggal Pengisian</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                            <div class="form-text text-muted">Anda bisa mengisi jurnal untuk hari ini atau hari sebelumnya (Backdate).</div>
                        </div>
                        
                        <h4 class="fw-bold mb-3">7 Kebiasaan Anak Indonesia Hebat</h4>
                        <p class="text-muted mb-4">Centang kotak jika kamu sudah melakukan kebiasaan ini hari ini.</p>

                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="list-group list-group-flush">
                                    @foreach($habits as $habit)
                                        <div class="card mb-3 border">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center mb-3">
                                                    @if($habit->icon)
                                                        <i class="{{ $habit->icon }} me-2 text-primary fs-4"></i>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $habit->name }}</h6>
                                                        <small class="text-muted">{{ $habit->description }}</small>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    {{-- Radio "Dilaksanakan" --}}
                                                    <div class="col-md-6">
                                                        <div class="form-check p-3 border rounded clickable-radio">
                                                            <input class="form-check-input" type="radio" name="habits[{{ $habit->id }}][status]" id="habit_{{ $habit->id }}_yes" value="1" required onchange="toggleHabitInput({{ $habit->id }})">
                                                            <label class="form-check-label fw-bold text-success" for="habit_{{ $habit->id }}_yes">
                                                                <i class="feather-check-circle me-1"></i> Dilaksanakan
                                                            </label>
                                                            
                                                            {{-- Time Input if Yes --}}
                                                            @if($habit->input_type == 'time')
                                                                <div id="habit_{{ $habit->id }}_time_container" class="mt-2 d-none">
                                                                    <label class="form-label fs-12 text-muted">Jam Berapa?</label>
                                                                    <input type="time" name="habits[{{ $habit->id }}][time]" class="form-control form-control-sm">
                                                                    @if($habit->id == 1) <small class="text-info fs-11">Target: < {{ \App\Models\Setting::where('key', 'habit_1_limit')->value('value') ?? '05:00' }}</small> @endif
                                                                    @if($habit->id == 7) <small class="text-info fs-11">Target: < {{ \App\Models\Setting::where('key', 'habit_7_limit')->value('value') ?? '21:00' }}</small> @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Radio "Tidak Dilaksanakan" --}}
                                                    <div class="col-md-6">
                                                        <div class="form-check p-3 border rounded clickable-radio bg-soft-danger">
                                                            <input class="form-check-input" type="radio" name="habits[{{ $habit->id }}][status]" id="habit_{{ $habit->id }}_no" value="0" required onchange="toggleHabitInput({{ $habit->id }})">
                                                            <label class="form-check-label fw-bold text-danger" for="habit_{{ $habit->id }}_no">
                                                                <i class="feather-x-circle me-1"></i> Tidak Dilaksanakan
                                                            </label>

                                                            {{-- Reason Input --}}
                                                            <div id="habit_{{ $habit->id }}_reason_container" class="mt-2 d-none">
                                                                <label class="form-label fs-12 text-muted">Mengapa?</label>
                                                                <textarea name="habits[{{ $habit->id }}][note]" class="form-control form-control-sm" rows="2" placeholder="Tulis alasan jujur..."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <label for="catatan_siswa" class="form-label fw-bold">Catatan / Refleksi Harian (Opsional)</label>
                                <textarea name="catatan_siswa" id="catatan_siswa" rows="3" class="form-control" placeholder="Ceritakan pengalaman seru atau hal baik yang kamu lakukan hari ini..."></textarea>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-send me-1"></i> Kirim Jurnal
                            </button>
                            <a href="{{ route('journals.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    
    @push('styles')
    <style>
        .clickable-radio:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function toggleHabitInput(habitId) {
            const yesRadio = document.getElementById(`habit_${habitId}_yes`);
            const noRadio = document.getElementById(`habit_${habitId}_no`);
            
            const timeContainer = document.getElementById(`habit_${habitId}_time_container`);
            const reasonContainer = document.getElementById(`habit_${habitId}_reason_container`);
            
            if (yesRadio.checked) {
                if(timeContainer) {
                    timeContainer.classList.remove('d-none');
                    const timeInput = timeContainer.querySelector('input[type="time"]');
                    if(timeInput) {
                        timeInput.required = true;
                        // Auto-fill time if empty (Requested Feature: "Siang 13:07, Malam 19:00")
                        if (!timeInput.value) {
                            const now = new Date();
                            const hours = String(now.getHours()).padStart(2, '0');
                            const minutes = String(now.getMinutes()).padStart(2, '0');
                            timeInput.value = `${hours}:${minutes}`;
                        }
                    }
                }
                if(reasonContainer) {
                    reasonContainer.classList.add('d-none');
                    const reasonInput = reasonContainer.querySelector('textarea');
                    if(reasonInput) reasonInput.required = false;
                    reasonInput.value = ''; 
                }
            } else if (noRadio.checked) {
                if(timeContainer) {
                    timeContainer.classList.add('d-none');
                    const timeInput = timeContainer.querySelector('input[type="time"]');
                    if(timeInput) timeInput.required = false;
                    timeInput.value = ''; 
                }
                if(reasonContainer) {
                    reasonContainer.classList.remove('d-none');
                    const reasonInput = reasonContainer.querySelector('textarea');
                    if(reasonInput) reasonInput.required = true;
                }
            }
        }
    </script>
    @endpush
</x-backend-layout>
