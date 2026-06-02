<div class="container-fluid">
    <h2>Form Pelaporan Kejadian (Damkar)</h2>
    <hr>
    <form action="pages/proses_laporan.php" method="POST" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label>Nama Pelapor / Saksi</label>
            <input type="text" name="nama_pelapor" class="form-control" required placeholder="Nama lengkap Anda">
        </div>

        <div class="form-group mb-3">
            <label>Nomor Telepon/WhatsApp (Aktif untuk Validasi)</label>
            <input type="tel" name="no_telp" class="form-control" required placeholder="Contoh: 0812345678xx">
        </div>

        <div class="form-group mb-3">
            <label>Kategori Kejadian</label>
            <select name="kategori_kejadian" class="form-control" required>
                <option value="">-- Pilih Kejadian --</option>
                <option value="Kebakaran Pemukiman">Kebakaran Pemukiman / Gedung</option>
                <option value="Kebakaran Lahan">Kebakaran Lahan / Hutan</option>
                <option value="Penyelamatan">Penyelamatan / Evakuasi (Animal Rescue, dll)</option>
                <option value="Lainnya">Kejadian Darurat Lainnya</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Alamat Lengkap / Patokan Lokasi</label>
            <textarea name="lokasi_kejadian" class="form-control" rows="3" required
                placeholder="Tuliskan alamat jelas dan patokan terdekat..."></textarea>
        </div>

        <div class="form-group mb-3">
            <label>Keterangan Tambahan / Kondisi Terkini</label>
            <textarea name="keterangan" class="form-control" rows="3"
                placeholder="Contoh: Api sudah membesar, ada korban terjebak, atau sumber air dekat kolam."></textarea>
        </div>

        <div class="form-group mb-3">
            <label>Foto Bukti Kejadian (Opsional)</label>
            <input type="file" name="foto_kejadian" class="form-control">
        </div>

        <button type="submit" name="btn_laporkan" class="btn btn-danger w-100">
            <i class="fa fa-paper-plane"></i> KIRIM LAPORAN DARURAT
        </button>
    </form>
</div>