<x-backend-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between w-100 gap-3">
            <span class="fw-bold fs-18">Log Aktivitas</span>
            <form action="{{ route('activity-logs.destroyAll') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh log aktivitas?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="feather-trash-2"></i> <span class="d-none d-sm-inline ms-1">Reset Log</span>
                </button>
            </form>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-header border-bottom-0 pb-0">
                    <h5 class="card-title mb-0">Riwayat Jejak Digital</h5>
                    <p class="fs-12 text-muted mt-1">Memantau seluruh aktivitas pengguna di dalam sistem secara real-time.</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Pengguna</th>
                                    <th>Aktivitas</th>
                                    <th>Keterangan</th>
                                    <th>Waktu & Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-circle fw-bold">
                                                {{ substr($log->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <span class="d-block fw-bold text-dark">{{ $log->user->name ?? 'Unknown' }}</span>
                                                <span class="fs-11 fw-medium text-uppercase text-muted">{{ $log->user->role ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-soft-secondary text-secondary';
                                            $icon = 'feather-activity';
                                            
                                            if (Str::contains(strtolower($log->action), 'login')) {
                                                $badgeClass = 'bg-soft-success text-success';
                                                $icon = 'feather-log-in';
                                            } elseif (Str::contains(strtolower($log->action), 'logout')) {
                                                $badgeClass = 'bg-soft-warning text-warning';
                                                $icon = 'feather-log-out';
                                            } elseif (Str::contains(strtolower($log->action), 'setting') || Str::contains(strtolower($log->action), 'update')) {
                                                $badgeClass = 'bg-soft-info text-info';
                                                $icon = 'feather-edit-3';
                                            } elseif (Str::contains(strtolower($log->action), 'clear') || Str::contains(strtolower($log->action), 'delete')) {
                                                $badgeClass = 'bg-soft-danger text-danger';
                                                $icon = 'feather-trash-2';
                                            }
                                        @endphp
                                        <div class="badge {{ $badgeClass }} border-0 px-3 py-2 d-inline-flex align-items-center gap-2">
                                            <i class="{{ $icon }} fs-12"></i>
                                            <span class="fw-bold">{{ $log->action }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-medium">{{ $log->description }}</span>
                                        @if($log->subject_type)
                                            <div class="mt-1">
                                                <span class="badge bg-light text-muted fw-normal border">
                                                    <i class="feather-link-2 me-1 fs-10"></i>
                                                    {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-bold fs-13">
                                                <i class="feather-clock me-1 text-muted"></i>
                                                {{ $log->created_at->translatedFormat('d M Y, H:i') }}
                                            </span>
                                            <span class="text-muted fs-11 mt-1">
                                                <i class="feather-map-pin me-1"></i>
                                                IP: {{ $log->ip_address }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <img src="{{ asset('template-admin/assets/images/no-data.png') }}" alt="" class="mb-3" style="width: 100px; opacity: 0.5;">
                                        <p class="text-muted">Belum ada riwayat aktivitas yang tercatat.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top-0 pt-4 pb-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
