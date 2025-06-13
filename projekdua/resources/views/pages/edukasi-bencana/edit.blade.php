@section('title', 'Edit Edukasi Bencana')
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Edukasi Bencana'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h6 class="mb-0">Form Edit Edukasi Bencana</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a class="btn bg-gradient-dark mb-0" href="{{ route('edukasi-bencana.show', $edukasiBencana->id) }}">
                                    <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <form action="{{ route('edukasi-bencana.update', $edukasiBencana->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row p-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="judul" class="form-control-label">Judul</label>
                                        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $edukasiBencana->judul) }}" required>
                                        @error('judul')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_bencana" class="form-control-label">Jenis Bencana</label>
                                        <input type="text" class="form-control @error('jenis_bencana') is-invalid @enderror" id="jenis_bencana" name="jenis_bencana" value="{{ old('jenis_bencana', $edukasiBencana->jenis_bencana) }}" required>
                                        @error('jenis_bencana')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal" class="form-control-label">Tanggal</label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($edukasiBencana->tanggal)->format('Y-m-d')) }}" required>
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-control-label">Status</label>
                                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="">Pilih Status</option>
                                            <option value="draft" {{ old('status', $edukasiBencana->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ old('status', $edukasiBencana->status) == 'published' ? 'selected' : '' }}>Published</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="deskripsi" class="form-control-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $edukasiBencana->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn bg-gradient-dark mb-0">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
