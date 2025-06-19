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
                                        @php
                                            $alamat = $pengaduan->alamat;
                                            $alamatParts = explode('|||', $alamat);
                                            $alamatTeks = $alamatParts[0];
                                            $koordinat = isset($alamatParts[1]) ? $alamatParts[1] : null;
                                        @endphp
                                        <td>:
                                            @if($koordinat)
                                                <a href="https://maps.google.com/?q={{ $koordinat }}" target="_blank">{{ $alamatTeks }}</a>
                                            @else
                                                {{ $alamatTeks }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
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
                                    @php
                                        $mediaUris = json_decode($pengaduan->media_uri, true) ?? [];
                                        $mediaTypes = json_decode($pengaduan->media_type, true) ?? [];
                                    @endphp
                                    @if(count($mediaUris) > 0)
                                    <tr>
                                        <th class="ps-0">Bukti Media</th>
                                        <td>:
                                            <div id="carouselBukti" class="carousel slide" data-bs-ride="carousel" style="max-width:300px;">
                                                <div class="carousel-inner">
                                                    @foreach($mediaUris as $i => $url)
                                                        <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                                            @if(isset($mediaTypes[$i]) && Str::contains($mediaTypes[$i], 'image'))
                                                                <img src="{{ url($url) }}" class="d-block w-100 bukti-hover" style="max-height:250px;object-fit:contain;" alt="Bukti" onclick="showZoomCarousel(@json($mediaUris), {{ $i }}, false)">
                                                            @elseif(isset($mediaTypes[$i]) && Str::contains($mediaTypes[$i], 'video'))
                                                                <video controls class="d-block w-100 bukti-hover" style="max-height:250px;object-fit:contain;" onclick="showZoomCarousel(@json($mediaUris), {{ $i }}, false)">
                                                                    <source src="{{ url($url) }}" type="{{ $mediaTypes[$i] }}">
                                                                </video>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if(count($mediaUris) > 1)
                                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselBukti" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselBukti" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                @endif
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
                                        @php
                                            $buktiAdmin = json_decode($pengaduan->bukti, true) ?? [];
                                        @endphp
                                        @if($pengaduan->feedback)
                                            <p>{{ $pengaduan->feedback }}</p>
                                            @if(count($buktiAdmin) > 0)
                                                <div id="carouselBuktiAdmin" class="carousel slide" data-bs-ride="carousel" style="max-width:400px;">
                                                    <div class="carousel-inner">
                                                        @foreach($buktiAdmin as $i => $url)
                                                            @php
                                                                $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                                                            @endphp
                                                            <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                                                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                                                    <img src="{{ asset('storage/' . $url) }}" class="d-block w-100 bukti-hover" style="max-height:300px;object-fit:contain;" alt="Bukti Admin" onclick="showZoomCarousel(@json($buktiAdmin), {{ $i }}, true)">
                                                                @elseif(in_array($ext, ['mp4','webm','ogg']))
                                                                    <video controls class="d-block w-100 bukti-hover" style="max-height:300px;object-fit:contain;" onclick="showZoomCarousel(@json($buktiAdmin), {{ $i }}, true)">
                                                                        <source src="{{ asset('storage/' . $url) }}" type="video/{{ $ext }}">
                                                                        Browser tidak mendukung video.
                                                                    </video>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if(count($buktiAdmin) > 1)
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
                                        @else
                                            <p class="text-muted mb-0">Belum ada feedback dari admin.</p>
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
    <!-- Modal untuk Zoom Media dengan Carousel -->
    <div class="modal fade" id="zoomModal" tabindex="-1" aria-labelledby="zoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-center p-0">
                    <div id="zoomModalCarouselWrapper"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    .bukti-hover {
        transition: transform 0.3s;
        cursor: pointer;
    }
    .bukti-hover:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        z-index: 2;
    }
    /* Panah carousel hitam untuk modal zoom */
    #zoomModal .carousel-control-prev-icon,
    #zoomModal .carousel-control-next-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='black' viewBox='0 0 8 8'%3E%3Cpath d='M4.854 1.146a.5.5 0 0 0-.708.708L6.293 4H1.5a.5.5 0 0 0 0 1h4.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3z'/%3E%3C/svg%3E");
        filter: none;
        box-shadow: 0 0 0 2px #fff, 0 0 6px 2px rgba(0,0,0,0.3);
        border-radius: 50%;
        width: 2.5rem;
        height: 2.5rem;
    }
    #zoomModal .carousel-control-prev-icon.invert,
    #zoomModal .carousel-control-next-icon.invert {
        filter: invert(1);
        box-shadow: 0 0 0 2px #000, 0 0 6px 2px rgba(255,255,255,0.3);
    }
    #zoomModal .carousel-control-prev,
    #zoomModal .carousel-control-next {
        background: rgba(255,255,255,0.7);
        border-radius: 50%;
        width: 48px;
        height: 48px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    #zoomModal .carousel-control-prev.invert,
    #zoomModal .carousel-control-next.invert {
        background: rgba(0,0,0,0.7);
    }
    #zoomModal .carousel-control-prev:hover,
    #zoomModal .carousel-control-next:hover {
        background: rgba(0,0,0,0.15);
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/medium-zoom@1.0.8/dist/medium-zoom.min.js"></script>
<script>
// Fungsi untuk menampilkan carousel di modal zoom
function showZoomCarousel(mediaArr, idx, isAdmin = false) {
    let carouselId = isAdmin ? 'zoomCarouselAdmin' : 'zoomCarouselUser';
    let carouselInner = '';
    let indicators = '';
    for(let i=0; i<mediaArr.length; i++) {
        let media = mediaArr[i];
        let ext = media.split('.').pop().toLowerCase();
        let active = (i === idx) ? 'active' : '';
        let src = media;
        if(isAdmin) {
            src = '/storage/' + media;
        }
        carouselInner += `<div class='carousel-item ${active}'>`;
        if(['jpg','jpeg','png','gif','webp'].includes(ext)) {
            carouselInner += `<img src='${src}' class='d-block w-100 zoomable-img' style='max-height:80vh;object-fit:contain;'>`;
        } else if(['mp4','webm','ogg'].includes(ext)) {
            carouselInner += `<video controls class='d-block w-100' style='max-height:80vh;'><source src='${src}' type='video/${ext}'></video>`;
        }
        carouselInner += `</div>`;
        indicators += `<button type='button' data-bs-target='#${carouselId}' data-bs-slide-to='${i}' class='${active}' aria-current='${active ? 'true' : 'false'}' aria-label='Slide ${i+1}'></button>`;
    }
    let controls = '';
    if(mediaArr.length > 1) {
        controls = `
            <button class='carousel-control-prev' type='button' data-bs-target='#${carouselId}' data-bs-slide='prev'>
                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                <span class='visually-hidden'>Previous</span>
            </button>
            <button class='carousel-control-next' type='button' data-bs-target='#${carouselId}' data-bs-slide='next'>
                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                <span class='visually-hidden'>Next</span>
            </button>`;
    }
    let html = `
        <div id='${carouselId}' class='carousel slide' data-bs-ride='carousel' data-bs-interval='false'>
            <div class='carousel-indicators'>${indicators}</div>
            <div class='carousel-inner'>${carouselInner}</div>
            ${controls}
        </div>
    `;
    document.getElementById('zoomModalCarouselWrapper').innerHTML = html;
    var myModal = new bootstrap.Modal(document.getElementById('zoomModal'));
    myModal.show();
    // Set slide ke index yang diklik (Bootstrap 5)
    var carousel = document.getElementById(carouselId);
    var bsCarousel = bootstrap.Carousel.getOrCreateInstance(carousel);
    bsCarousel.to(idx);
    // Aktifkan medium-zoom pada gambar di dalam modal
    setTimeout(function() {
        mediumZoom('.zoomable-img', {
            margin: 24,
            background: '#000'
        });
    }, 300);
}

// Tambahkan observer untuk invert panah jika background modal gelap
function setCarouselArrowColor() {
    var modal = document.getElementById('zoomModal');
    if (!modal) return;
    var bg = window.getComputedStyle(modal.querySelector('.modal-content')).backgroundColor;
    // Cek jika background gelap (pakai luminance)
    function isDark(rgb) {
        if (!rgb) return false;
        var c = rgb.match(/\d+/g);
        if (!c) return false;
        var luminance = (0.299 * c[0] + 0.587 * c[1] + 0.114 * c[2]);
        return luminance < 128;
    }
    var invert = isDark(bg);
    var prev = modal.querySelector('.carousel-control-prev-icon');
    var next = modal.querySelector('.carousel-control-next-icon');
    if (prev && next) {
        if (invert) {
            prev.classList.add('invert');
            next.classList.add('invert');
        } else {
            prev.classList.remove('invert');
            next.classList.remove('invert');
        }
    }
}
// Panggil fungsi ini setiap kali modal carousel muncul
const origShowZoomCarousel = showZoomCarousel;
showZoomCarousel = function() {
    origShowZoomCarousel.apply(this, arguments);
    setTimeout(setCarouselArrowColor, 400);
}
</script>
@endpush
