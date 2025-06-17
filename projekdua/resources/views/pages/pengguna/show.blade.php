@section('title', 'Detail Pengguna')
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Pengguna'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h6 class="mb-0">Detail Pengguna</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a class="btn bg-gradient-dark mb-0" href="{{ route('pengguna.index') }}">
                                    <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Kembali
                                </a>
                                <a class="btn bg-gradient-dark mb-0" href="{{ route('pengguna.edit', $pengguna->id_user) }}">
                                    <i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;Edit
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="row p-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Nama</label>
                                    <p class="form-control-static">{{ $pengguna->nama_user }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Email</label>
                                    <p class="form-control-static">{{ $pengguna->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">No. Telepon</label>
                                    <p class="form-control-static">{{ $pengguna->no_telepon }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Username</label>
                                    <p class="form-control-static">{{ $pengguna->username }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Role</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-sm bg-gradient-{{ $pengguna->role == 'admin' ? 'primary' : 'info' }}">
                                            {{ $pengguna->role }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Status</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-sm bg-gradient-{{ $pengguna->status == 'aktif' ? 'success' : 'danger' }}">
                                            {{ $pengguna->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
