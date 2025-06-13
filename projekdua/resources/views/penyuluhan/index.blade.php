@section('title', 'Jadwal Penyuluhan')
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Jadwal Penyuluhan'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Daftar Jadwal Penyuluhan</h6>
                        <a href="{{ route('penyuluhan.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Jadwal
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Judul</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Waktu</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lokasi</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pemateri</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penyuluhan as $p)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $p->judul }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $p->tanggal }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $p->waktu }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $p->lokasi }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $p->pemateri }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $p->status == 'aktif' ? 'success' : ($p->status == 'selesai' ? 'secondary' : 'danger') }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('penyuluhan.edit', $p->id) }}" class="btn btn-link text-warning px-3 mb-0">
                                                <i class="fas fa-pencil-alt text-warning me-2"></i>Edit
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
</div>
@endsection
