<div class="card stretch stretch-full border-0 shadow-sm mb-4">
    <div class="card-header border-bottom-0 pb-0 text-center">
        <h4 class="card-title fw-bold mb-1" style="color: #1a237e;">Peringkat Kebiasaan 7KAIH</h4>
    </div>
    <div class="card-body pt-3">
        <div class="d-flex flex-column gap-3">
            @forelse($leaderboard as $index => $student)
                @php
                    // Styling for rank numbers based on the user's reference image
                    $rankStyle = 'background-color: #f8f9fa; color: #495057;'; // Default 4th, 5th
                    $borderStyle = 'border-color: #f1f3f5;';
                    
                    if ($index == 0) {
                        $rankStyle = 'background-color: #febb02; color: white;'; // Gold
                        $borderStyle = 'border-color: #fce8a1;';
                    } elseif ($index == 1) {
                        $rankStyle = 'background-color: #94a3b8; color: white;'; // Silver
                        $borderStyle = 'border-color: #cbd5e1;';
                    } elseif ($index == 2) {
                        $rankStyle = 'background-color: #d97706; color: white;'; // Bronze
                        $borderStyle = 'border-color: #fef08a;';
                    }
                @endphp
                
                <div class="d-flex align-items-center justify-content-between p-3 rounded" style="border: 1px solid; {{ $borderStyle }} background-color: #ffffff;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold fs-5 shadow-sm" style="width: 45px; height: 45px; {{ $rankStyle }}">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 15px;">{{ $student->nama }}</h6>
                            <div class="fs-12 text-muted d-flex align-items-center gap-1">
                                Kelas {{ $student->classRoom->nama_kelas ?? '-' }} 
                                <span class="mx-1">&bull;</span> 
                                Streak 🔥 {{ $student->streak }} Hari
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <h4 class="fw-bold mb-0" style="color: #0c4a6e;">{{ $student->points }}</h4>
                        <span class="text-muted fw-semibold text-uppercase" style="font-size: 10px;">Poin Kebiasaan</span>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="feather-award fs-1 text-muted opacity-50 mb-2"></i>
                    <p class="mb-0">Belum ada data peringkat saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
