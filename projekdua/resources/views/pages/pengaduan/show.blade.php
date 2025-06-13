@section('title', 'Detail Pengaduan')
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
                            <!-- Kolom Data Laporan -->
                            <div class="col-md-6 mb-3">
                                <h5 class="mb-3">Data Laporan</h5>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <th class="ps-0" style="width: 120px;">Tanggal</th>
                                        <td>: {{ date('d/m/Y', strtotime($pengaduan->tanggal)) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Waktu</th>
                                        <td>: {{ $pengaduan->waktu }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Jenis</th>
                                        <td>: {{ $pengaduan->jenis_pengaduan }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Kecamatan</th>
                                        <td>: {{ $pengaduan->kecamatan }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Desa</th>
                                        <td>: {{ $pengaduan->desa }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Alamat</th>
                                        <td>: {{ $pengaduan->alamat }}</td>
                                    </tr>
                                    <tr></tr>
                                        <th class="ps-0">Deskripsi</th>
                                        <td>: {{ $pengaduan->deskripsi }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Status</th>
                                        <td>:
                                            <span class="badge badge-sm bg-gradient-{{ $pengaduan->status == 'Selesai' ? 'success' : ($pengaduan->status == 'Proses' ? 'warning' : 'danger') }}">
                                                {{ $pengaduan->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @if($pengaduan->media_uri)
                                    <tr>
                                        <th class="ps-0">Bukti Media</th>
                                        <td>:
                                            <!-- Thumbnail -->
                                            <img src="{{ $pengaduan->media_uri }}" alt="Bukti" class="img-thumbnail bukti-hover" style="max-width:100px;max-height:100px;cursor:pointer;" data-bs-toggle="modal" data-bs-target="#buktiModal">
                                            <!-- Modal -->
                                            <div class="modal fade" id="buktiModal" tabindex="-1" aria-labelledby="buktiModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="buktiModalLabel">Bukti Media</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ $pengaduan->media_uri }}" alt="Bukti" class="img-fluid bukti-modal-img">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <!-- Kolom Data Pelapor -->
                            <div class="col-md-6 mb-3">
                                <h5 class="mb-3">Data Pelapor</h5>
                                @if($pengaduan->user)
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <th class="ps-0" style="width: 120px;">Nama</th>
                                            <td>: {{ $pengaduan->user->nama_user }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Email</th>
                                            <td>: {{ $pengaduan->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">No Telepon</th>
                                            <td>: {{ $pengaduan->user->no_telepon }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Username</th>
                                            <td>: {{ $pengaduan->user->username }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Role</th>
                                            <td>: {{ $pengaduan->user->role }}</td>
                                        </tr>
                                    </table>
                                @else
                                    <span class="text-danger">Data user tidak ditemukan.</span>
                                @endif
                            </div>
                        </div>
                        <!-- Section Feedback Admin -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Feedback Admin</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($pengaduan->feedback)
                                            <p>{{ $pengaduan->feedback }}</p>
                                        @else
                                            <p class="text-muted mb-0">Belum ada feedback dari admin.</p>
                                        @endif
                                        @if($pengaduan->bukti && count(json_decode($pengaduan->bukti, true) ?? []) > 0)
                                            @php
                                                $buktiArray = json_decode($pengaduan->bukti, true) ?? [];
                                            @endphp
                                            <div id="carouselBuktiAdmin" class="carousel slide" data-bs-ride="carousel" style="max-width:300px;">
                                                <div class="carousel-inner">
                                                    @foreach($buktiArray as $i => $bukti)
                                                        <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                                            @if(Str::endsWith(strtolower($bukti), ['.jpg', '.jpeg', '.png', '.gif']))
                                                                <img src="{{ asset('storage/' . $bukti) }}" class="d-block w-100" style="max-height:250px;object-fit:contain;" alt="Bukti Admin">
                                                            @elseif(Str::endsWith(strtolower($bukti), ['.mp4', '.mov', '.avi']))
                                                                <video controls class="d-block w-100" style="max-height:250px;">
                                                                    <source src="{{ asset('storage/' . $bukti) }}">
                                                                </video>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if(count($buktiArray) > 1)
                                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselBuktiAdmin" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselBuktiAdmin" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
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

@push('css')
<style>
    .bukti-hover {
        transition: transform 0.3s;
        cursor: pointer;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
