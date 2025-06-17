<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pengaduan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #fff;
        }
        .kop {
            display: flex;
            align-items: center;
            border-bottom: 4px solid #ff9800;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 90px;
            height: 90px;
            margin-right: 20px;
        }
        .kop-text {
            flex: 1;
            text-align: center;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #ff9800;
            letter-spacing: 2px;
        }
        .kop-text p {
            margin: 0;
            font-size: 1rem;
        }
        .header-info {
            text-align: right;
            margin-bottom: 10px;
        }
        .filter-form {
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #fff3e0;
            border: 1px solid #ff9800;
            border-radius: 6px;
        }
        .filter-form label {
            margin-right: 8px;
            font-weight: bold;
        }
        .filter-form select, .filter-form input[type="text"] {
            margin-right: 15px;
            padding: 3px 8px;
            border: 1px solid #ff9800;
            border-radius: 4px;
        }
        .filter-form button {
            background: #ff9800;
            color: #fff;
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ff9800;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #ff9800;
            color: #fff;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
        }
        @media print {
            .no-print, .filter-form {
                display: none;
            }
            body {
                margin: 0;
            }
        }
        .no-print button {
            background: #ff9800;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(255,152,0,0.15);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .no-print button:hover {
            background: #e65100;
            box-shadow: 0 4px 12px rgba(255,152,0,0.25);
        }
    </style>
</head>
<body>
    <div class="kop">
        <img src="{{ asset('img/logobpbd.jpg') }}" alt="Logo BPBD" class="kop-logo">
        <div class="kop-text">
            <h2>Badan Penanggulangan Bencana Daerah (BPBD)</h2>
            <p>Kab. Subang<br>Jl. Jl. KS Tubun no. 10 Kelurahan Karanganyar Kec. Subang, Kab. Subang 41211<br>0812-2433-4343</p>
        </div>
    </div>
    <div class="header-info">
        <strong>Tanggal Cetak:</strong> <span id="waktu-cetak"></span>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="filter-form no-print">
        <label for="jenis_pengaduan">Jenis Pengaduan</label>
        <select name="jenis_pengaduan" id="jenis_pengaduan">
            <option value="">Semua</option>
            @php $jenisList = $pengaduans->pluck('jenis_pengaduan')->unique(); @endphp
            @foreach($jenisList as $jenis)
                <option value="{{ $jenis }}" {{ request('jenis_pengaduan') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
            @endforeach
        </select>
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="">Semua</option>
            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <label for="nama_pelapor">Nama Pelapor</label>
        <input type="text" name="nama_pelapor" id="nama_pelapor" value="{{ request('nama_pelapor') }}" placeholder="Cari nama pelapor...">
        <button type="submit">Terapkan Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pelapor</th>
                <th>Jenis Pengaduan</th>
                <th>Status</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengaduans as $pengaduan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('d/m/Y', strtotime($pengaduan->tanggal)) }}</td>
                <td>{{ $pengaduan->nama_pelapor }}</td>
                <td>{{ $pengaduan->jenis_pengaduan }}</td>
                <td>{{ $pengaduan->status }}</td>
                <td>{{ $pengaduan->deskripsi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Sistem' }}</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Print</button>
    </div>
    <script>
    function updateWaktuCetak() {
        // Waktu Asia/Jakarta (GMT+7)
        const now = new Date();
        // Konversi ke GMT+7
        const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        const jakarta = new Date(utc + (3600000 * 7));
        // Format: dd/mm/yyyy HH:ii:ss
        const pad = n => n.toString().padStart(2, '0');
        const tgl = pad(jakarta.getDate()) + '/' + pad(jakarta.getMonth() + 1) + '/' + jakarta.getFullYear();
        const jam = pad(jakarta.getHours()) + ':' + pad(jakarta.getMinutes()) + ':' + pad(jakarta.getSeconds());
        document.getElementById('waktu-cetak').textContent = tgl + ' ' + jam;
    }
    updateWaktuCetak();
    setInterval(updateWaktuCetak, 1000);
    </script>
</body>
</html>
