<?php
session_start();
include '../koneksi.php'; // Koneksi database

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $instansi = $_POST['instansi'];
    $keperluan = $_POST['keperluan'];
    $tanggal_waktu = $_POST['tanggal_waktu'];

    $query = "INSERT INTO tamu (nama, instansi, keperluan, `tanggal/waktu`)
              VALUES ('$nama', '$instansi', '$keperluan', '$tanggal_waktu')";

    if (mysqli_query($conn, $query)) {
        header("location: tamu.php"); // redirect setelah sukses
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Gagal menambahkan data tamu!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tamu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #007eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
            background: #f8f9fa;
        }
        h3 {
            color: #444;
        }
    </style>
</head>
<body>
    <div class="card">
        <h3 class="text-center mb-4">Tambah Data Tamu</h3>
        <?= $message ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Instansi</label>
                <input type="text" name="instansi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Keperluan</label>
                <input type="text" name="keperluan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal/Waktu</label>
                <input type="datetime-local" name="tanggal_waktu" class="form-control" required>
                <div class="form-text">Format: YYYY-MM-DD HH:MM</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Tamu</button>
            <a href="tamu.php" class="btn btn-secondary w-100 mt-2">Batal</a>
        </form>
    </div>
</body>
</html>
