@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pengaduan</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $total_pengaduan }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-email-83 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pengaduan Diproses</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $pengaduan_diproses }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pengaduan Selesai</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $pengaduan_selesai }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pengguna</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $pengguna_terdaftar }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Statistik Pengaduan</h6>
                        <p class="text-sm mb-0">
                            <span class="font-weight-bold">Data pengaduan per bulan</span>
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart text-center py-5">
                            <span class="text-muted">Belum ada data pengaduan</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Kategori Pengaduan</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            @foreach($kategori_pengaduan as $kategori)
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                        <i class="ni ni-bell-55 text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">{{ $kategori->jenis_pengaduan }}</h6>
                                        <span class="text-xs">{{ $kategori->total }} pengaduan</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <a href="{{ route('pengaduan.index', ['jenis_pengaduan' => $kategori->jenis_pengaduan]) }}" class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">Pengaduan Terbaru</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelapor</th>
                                    <th>Jenis Pengaduan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengaduan_terbaru as $pengaduan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pengaduan->nama_pelapor }}</td>
                                    <td>{{ $pengaduan->jenis_pengaduan }}</td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $pengaduan->status == 'Selesai' ? 'success' : ($pengaduan->status == 'Proses' ? 'warning' : 'danger') }}">
                                            {{ $pengaduan->status }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($pengaduan->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('pengaduan.show', $pengaduan->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye">Lihat Detail</i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @if($pengaduan_terbaru->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data pengaduan</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Edukasi Bencana Terbaru</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            @foreach($edukasi_terbaru as $edukasi)
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                        <i class="ni ni-book-bookmark text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">{{ $edukasi->judul }}</h6>
                                        <span class="text-xs">{{ $edukasi->jenis_bencana }} - {{ $edukasi->tanggal->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <a href="{{ route('edukasi-bencana.show', $edukasi->id) }}" class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </li>
                            @endforeach
                            @if($edukasi_terbaru->isEmpty())
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex flex-column">
                                        <span class="text-xs">Belum ada data edukasi bencana</span>
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(251, 99, 64, 0)');
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#fb6340",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
@endpush
