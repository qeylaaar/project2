@section('title', 'Data Pengaduan')
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Data Pengaduan'])
    <div class="container-fluid py-4">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2000
                    });
                });
            </script>
        @endif

        @push('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endpush
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h6 class="mb-0">Daftar Pengaduan</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Form Filter -->
                        <form action="{{ route('pengaduan.index') }}" method="GET" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="jenis_pengaduan">Jenis Pengaduan</label>
                                        <select name="jenis_pengaduan" id="jenis_pengaduan" class="form-control">
                                            <option value="">Semua Jenis</option>
                                            @foreach($jenisPengaduan as $jenis)
                                                <option value="{{ $jenis }}" {{ request('jenis_pengaduan') == $jenis ? 'selected' : '' }}>
                                                    {{ $jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Semua Status</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nama_pelapor">Nama Pelapor</label>
                                        <input type="text" name="nama_pelapor" id="nama_pelapor" class="form-control"
                                               value="{{ request('nama_pelapor') }}" placeholder="Cari nama pelapor...">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-group w-100">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Tombol Print -->
                        <div class="mb-3">
                            <a href="{{ route('pengaduan.print', request()->query()) }}" class="btn btn-success" target="_blank">
                                <i class="fas fa-print"></i> Print Rekap
                            </a>
                        </div>

                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Pelapor</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Pengaduan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengaduans as $pengaduan)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $loop->iteration }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ date('d/m/Y', strtotime($pengaduan->tanggal)) }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $pengaduan->nama_pelapor }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $pengaduan->jenis_pengaduan }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <span class="badge badge-sm bg-gradient-{{ $pengaduan->status == 'Selesai' ? 'success' : ($pengaduan->status == 'Proses' ? 'warning' : 'danger') }}">
                                                        {{ $pengaduan->status }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <a href="{{ route('pengaduan.show', $pengaduan->id) }}" class="btn btn-link text-dark px-3 mb-0">
                                                    <i class="fas fa-eye text-dark me-2"></i>Detail
                                                </a>
                                                <a href="{{ route('pengaduan.edit', $pengaduan->id) }}" class="btn btn-link text-dark px-3 mb-0">
                                                    <i class="fas fa-pencil-alt text-dark me-2"></i>Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
