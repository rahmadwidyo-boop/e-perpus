@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Manajemen Siswa')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-700 mb-0" style="font-weight:700;">Data Siswa</h5>
        <small class="text-muted">Total: {{ $students->total() }} siswa</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('students.import.form') }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;">
            <i class="bi bi-file-earmark-arrow-up me-1"></i>Import Excel
        </a>
        <a href="{{ route('students.create') }}" class="btn btn-sm" style="background:#1e3a5f;color:#fff;border-radius:8px;">
            <i class="bi bi-person-plus me-1"></i>Tambah Siswa
        </a>
    </div>
</div>

<div class="table-card p-3 mb-3">
    <form action="{{ route('students.index') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label small mb-1">Cari Siswa</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Nama atau NIS..."
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Filter Kelas</label>
            <select name="class" class="form-select form-select-sm">
                <option value="">Semua Kelas</option>
                @foreach([
                    'X'   => ['X 1','X 2','X 3','X 4','X 5','X 6','X 7'],
                    'XI'  => ['XI 1','XI 2','XI 3','XI 4','XI 5','XI 6','XI 7'],
                    'XII' => ['XII 1','XII 2','XII 3','XII 4','XII 5','XII 6','XII 7'],
                ] as $tingkat => $kelasList)
                    <optgroup label="Kelas {{ $tingkat }}">
                        @foreach($kelasList as $k)
                            <option value="{{ $k }}" {{ request('class') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>No. HP</th>
                    <th class="text-center">Pinjaman Aktif</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                <tr>
                    <td>{{ $students->firstItem() + $i }}</td>
                    <td><code>{{ $student->nis }}</code></td>
                    <td class="fw-500" style="font-weight:500;">{{ $student->name }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $student->class }}</span>
                    </td>
                    <td>{{ $student->phone ?? '-' }}</td>
                    <td class="text-center">
                        @php $active = $student->activeLoans()->count(); @endphp
                        @if($active > 0)
                            <span class="badge bg-warning text-dark">{{ $active }}</span>
                        @else
                            <span class="badge bg-light text-muted">0</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-xs btn-outline-info" title="Detail"
                               style="padding:.2rem .5rem;font-size:.75rem;border-radius:6px;">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-xs btn-outline-warning" title="Edit"
                               style="padding:.2rem .5rem;font-size:.75rem;border-radius:6px;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('students.destroy', $student) }}" method="POST"
                                  onsubmit="return confirm('Hapus data siswa ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger" title="Hapus"
                                        style="padding:.2rem .5rem;font-size:.75rem;border-radius:6px;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-3 d-block mb-2"></i>
                        Belum ada data siswa
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
    <div class="p-3 border-top">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection
