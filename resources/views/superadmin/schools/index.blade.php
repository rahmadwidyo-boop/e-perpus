@extends('layouts.superadmin')

@section('title', 'Manajemen Sekolah')
@section('page-title', 'Manajemen Sekolah')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h6 class="fw-700 mb-1" style="font-weight:700;color:#1e3a5f;">Daftar Sekolah</h6>
        <p class="text-muted small mb-0">Total {{ $schools->count() }} sekolah terdaftar</p>
    </div>
</div>

<div class="table-card">
    @if($schools->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-building fs-1 d-block mb-2"></i>
            <div class="fw-600" style="font-weight:600;">Belum ada sekolah terdaftar</div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Sekolah</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Trial / Berakhir</th>
                        <th>Pending</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schools as $index => $school)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-600" style="font-weight:600;">{{ $school->name }}</div>
                            <small class="text-muted">{{ $school->admin_name }}</small>
                        </td>
                        <td>
                            <code class="small" style="background:#f8f9fa;padding:.2rem .5rem;border-radius:4px;">
                                {{ $school->slug }}
                            </code>
                        </td>
                        <td>
                            @php
                                $statusConfig = [
                                    'trial'     => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'bi-clock-fill',        'label' => 'Trial'],
                                    'active'    => ['bg' => '#d1e7dd', 'color' => '#0f5132', 'icon' => 'bi-check-circle-fill', 'label' => 'Aktif'],
                                    'expired'   => ['bg' => '#f8d7da', 'color' => '#842029', 'icon' => 'bi-x-circle-fill',     'label' => 'Expired'],
                                    'suspended' => ['bg' => '#e2e3e5', 'color' => '#41464b', 'icon' => 'bi-pause-circle-fill', 'label' => 'Suspended'],
                                ];
                                $sc = $statusConfig[$school->subscription_status] ?? $statusConfig['expired'];
                            @endphp
                            <span class="badge rounded-pill px-3 py-2" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
                                <i class="bi {{ $sc['icon'] }} me-1"></i>{{ $sc['label'] }}
                            </span>
                        </td>
                        <td>
                            @if($school->subscription_status === 'trial' && $school->trial_ends_at)
                                <div class="small">
                                    <span class="text-muted">Trial s/d</span><br>
                                    <strong>{{ $school->trial_ends_at->format('d/m/Y') }}</strong>
                                </div>
                            @elseif($school->subscription_ends_at)
                                <div class="small">
                                    <span class="text-muted">Berakhir</span><br>
                                    <strong>{{ $school->subscription_ends_at->format('d/m/Y') }}</strong>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($school->pending_payments_count > 0)
                                <span class="badge rounded-pill" style="background:#fff3cd;color:#856404;">
                                    <i class="bi bi-clock me-1"></i>{{ $school->pending_payments_count }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($school->subscription_status === 'suspended')
                                    <form action="{{ route('superadmin.schools.updateStatus', $school) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-sm px-3 py-1"
                                                style="background:#d1e7dd;color:#0f5132;border:none;border-radius:6px;font-size:.8rem;font-weight:600;"
                                                onclick="return confirm('Aktifkan sekolah {{ addslashes($school->name) }}?')">
                                            <i class="bi bi-check-circle me-1"></i>Aktifkan
                                        </button>
                                    </form>
                                @elseif(in_array($school->subscription_status, ['active', 'trial']))
                                    <form action="{{ route('superadmin.schools.updateStatus', $school) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="suspended">
                                        <button type="submit" class="btn btn-sm px-3 py-1"
                                                style="background:#f8d7da;color:#842029;border:none;border-radius:6px;font-size:.8rem;font-weight:600;"
                                                onclick="return confirm('Suspend sekolah {{ addslashes($school->name) }}?')">
                                            <i class="bi bi-pause-circle me-1"></i>Suspend
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('superadmin.schools.updateStatus', $school) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-sm px-3 py-1"
                                                style="background:#d1e7dd;color:#0f5132;border:none;border-radius:6px;font-size:.8rem;font-weight:600;"
                                                onclick="return confirm('Aktifkan sekolah {{ addslashes($school->name) }}?')">
                                            <i class="bi bi-check-circle me-1"></i>Aktifkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
