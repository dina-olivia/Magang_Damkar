<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Laporan Kejadian</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="main-content" style="margin-left: 0; padding: 50px;">

        <div class="tabel-box" style="max-width: 600px; margin: auto;">

            <h3>Tambah Laporan Kejadian</h3>
            <hr>

            <form action="proses_tambah_laporan.php" method="POST">

                <label>Nomor Laporan:</label><br>
                <input type="text" name="nomor_laporan" placeholder="Contoh: LPK-001" required
                    style="width: 96%; padding: 8px; margin: 10px 0;">

                <label>Tanggal:</label><br>
                <input type="date" name="tanggal" required style="width: 96%; padding: 8px; margin: 10px 0;">

                <label>Nama Pelapor:</label><br>
                <input type="text" name="pelapor" placeholder="Nama Pelapor" required
                    style="width: 96%; padding: 8px; margin: 10px 0;">

                <label>No HP:</label><br>
                <input type="text" name="no_hp" placeholder="08xxxxxxxxxx"
                    style="width: 96%; padding: 8px; margin: 10px 0;">

                <label>Lokasi Kejadian:</label><br>
                <textarea name="lokasi" placeholder="Masukkan lokasi kejadian" required
                    style="width: 96%; padding: 8px; margin: 10px 0;"></textarea>

                <label>Latitude:</label><br>
                <input type="text" name="latitude" placeholder="-0.947083"
                    style="width: 96%; padding: 8px; margin: 10px 0;">

                <label>Longitude:</label><br>
                <input type="text" name="longitude" placeholder="100.417181"
                    style="width: 96%; padding: 8px; margin: 10px 0;">

                <label>Jenis Kejadian:</label><br>

                <select name="jenis_kejadian" required style="width: 100%; padding: 8px; margin: 10px 0;">

                    <option value="">-- Pilih Jenis --</option>
                    <option value="kebakaran">Kebakaran</option>
                    <option value="banjir">Banjir</option>
                    <option value="rescue">Rescue</option>
                    <option value="lainnya">Lainnya</option>

                </select>

                <label>Deskripsi:</label><br>

                <textarea name="deskripsi" placeholder="Deskripsi kejadian"
                    style="width: 96%; padding: 8px; margin: 10px 0;"></textarea>

                <label>Status:</label><br>

                <select name="status" required style="width: 100%; padding: 8px; margin: 10px 0;">

                    <option value="masuk">Masuk</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>

                </select>

                <div style="margin-top: 20px;">

                    <button type="submit" name="simpan" class="btn-tambah" style="border: none; cursor: pointer;">

                        Simpan Data

                    </button>

                    <a href="laporan.php"
                        style="margin-left: 10px; color: #666; text-decoration: none; font-size: 14px;">

                        Batal

                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>