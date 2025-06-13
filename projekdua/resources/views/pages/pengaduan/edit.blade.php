@section('title', 'Edit Pengaduan')
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Pengaduan'])
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
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Edit Pengaduan</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengaduan.update', $pengaduan->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal" class="form-control-label">Tanggal</label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d', strtotime($pengaduan->tanggal))) }}" required>
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_pelapor" class="form-control-label">Nama Pelapor</label>
                                        <input type="text" class="form-control @error('nama_pelapor') is-invalid @enderror" id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor', $pengaduan->nama_pelapor) }}" required>
                                        @error('nama_pelapor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_pengaduan" class="form-control-label">Jenis Pengaduan</label>
                                        <input type="text" class="form-control @error('jenis_pengaduan') is-invalid @enderror" id="jenis_pengaduan" name="jenis_pengaduan" value="{{ old('jenis_pengaduan', $pengaduan->jenis_pengaduan) }}" required>
                                        @error('jenis_pengaduan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-control-label">Status</label>
                                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="Menunggu" {{ old('status', $pengaduan->status) == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="Proses" {{ old('status', $pengaduan->status) == 'Proses' ? 'selected' : '' }}>Proses</option>
                                            <option value="Selesai" {{ old('status', $pengaduan->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="deskripsi" class="form-control-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="feedback" class="form-control-label">Feedback</label>
                                        <textarea class="form-control @error('feedback') is-invalid @enderror" id="feedback" name="feedback" rows="4">{{ old('feedback', $pengaduan->feedback) }}</textarea>
                                        @error('feedback')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="bukti" class="form-control-label">Bukti (Foto/Video)</label>
                                        <input type="file" class="form-control @error('bukti') is-invalid @enderror" id="bukti" name="bukti[]" accept="image/*,video/*" multiple>
                                        @error('bukti')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($pengaduan->bukti)
                                            <div class="mt-2">
                                                <p>Bukti saat ini:</p>
                                                @foreach(json_decode($pengaduan->bukti, true) ?? [] as $bukti)
                                                    @if(Str::endsWith(strtolower($bukti), ['.jpg', '.jpeg', '.png', '.gif']))
                                                        <img src="{{ asset('storage/' . $bukti) }}" alt="Bukti" class="img-fluid mb-2" style="max-height: 200px;">
                                                    @elseif(Str::endsWith(strtolower($bukti), ['.mp4', '.mov', '.avi']))
                                                        <video controls class="img-fluid mb-2" style="max-height: 200px;">
                                                            <source src="{{ asset('storage/' . $bukti) }}">
                                                        </video>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn bg-gradient-dark">Simpan Perubahan</button>
                                    <a href="{{ route('pengaduan.show', $pengaduan->id) }}" class="btn bg-gradient-secondary">Batal</a>
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

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
