@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Pengaduan'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h6 class="mb-0">Detail Pengaduan</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('pengaduan.edit', $pengaduan->id) }}" class="btn bg-gradient-dark mb-0">
                                    <i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;Edit
                                </a>
                                <a href="{{ route('pengaduan.index') }}" class="btn bg-gradient-secondary mb-0">
                                    <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Tanggal</label>
                                    <p class="form-control-static">{{ $pengaduan->tanggal->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Nama Pelapor</label>
                                    <p class="form-control-static">{{ $pengaduan->nama_pelapor }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Jenis Pengaduan</label>
                                    <p class="form-control-static">{{ $pengaduan->jenis_pengaduan }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Status</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-sm bg-gradient-{{ $pengaduan->status == 'Selesai' ? 'success' : ($pengaduan->status == 'Proses' ? 'warning' : 'danger') }}">
                                            {{ $pengaduan->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Deskripsi</label>
                                    <p class="form-control-static">{{ $pengaduan->deskripsi }}</p>
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
