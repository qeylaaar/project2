@section('title', 'Detail Edukasi Bencana')
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Edukasi Bencana'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h6 class="mb-0">Detail Edukasi Bencana</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a class="btn bg-gradient-dark mb-0" href="{{ route('edukasi-bencana.index') }}">
                                    <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Kembali
                                </a>
                                <a class="btn bg-gradient-dark mb-0" href="{{ route('edukasi-bencana.edit', $edukasiBencana->id) }}">
                                    <i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;Edit
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="row p-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Judul</label>
                                    <p class="form-control-static">{{ $edukasiBencana->judul }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Jenis Bencana</label>
                                    <p class="form-control-static">{{ $edukasiBencana->jenis_bencana }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Tanggal</label>
                                    <p class="form-control-static">{{ \Carbon\Carbon::parse($edukasiBencana->tanggal)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Status</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-sm bg-gradient-{{ $edukasiBencana->status == 'published' ? 'success' : 'warning' }}">
                                            {{ $edukasiBencana->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Deskripsi</label>
                                    <p class="form-control-static">{{ $edukasiBencana->deskripsi }}</p>
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
