<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../admin/index.php");
    exit();
}

include '../koneksi.php';
$username = $_SESSION['username'];

// Ambil input filter tanggal
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

// Pagination
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$mulai = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// Filter query
$filter_sql = "";
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    // Tambahkan waktu agar filter mencakup seluruh hari akhir
    $filter_sql = " WHERE `tanggal/waktu` BETWEEN '$tanggal_awal 00:00:00' AND '$tanggal_akhir 23:59:59'";
}

// Query data tamu
$sql = "SELECT * FROM tamu $filter_sql ORDER BY `tanggal/waktu` DESC LIMIT $mulai, $batas";
$data = mysqli_query($conn, $sql);

// Hitung total data
$total_data = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tamu $filter_sql"));
$total_halaman = ceil($total_data / $batas);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Tamu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
      background: linear-gradient(to right, #83a4d4, #b6fbff);
      padding: 30px;
      font-family: Arial, sans-serif;
    }
    .table-container {
      background: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    table thead {
      background: #007bff;
      color: white;
    }
    .pagination .page-item.active .page-link {
      background-color: #007bff;
      border-color: #007bff;
    }
    .sidebar {
      position: fixed;
      left: -250px;
      top: 0;
      width: 250px;
      height: 100%;
      background: #2c3e50;
      color: white;
      transition: left 0.3s ease;
      z-index: 1000;
    }
    .sidebar.active {
      left: 0;
    }
    .sidebar h2 {
      text-align: center;
      padding: 1rem;
      background: #1a252f;
      margin: 0;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li {
      padding: 15px 20px;
      border-bottom: 1px solid #34495e;
    }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
      margin-right: 10px;
      width: 20px;
    }
    .sidebar ul li:hover {
      background: #34495e;
      cursor: pointer;
    }
    .menu-toggle {
      position: fixed;
      top: 15px;
      left: 15px;
      font-size: 24px;
      color: #73945bff;
      cursor: pointer;
      z-index: 1100;
    }
    .content {
      padding: 2rem;
      margin-left: 0;
      transition: margin-left 0.3s ease;
    }
    .sidebar.active ~ .content {
      margin-left: 250px;
    }
    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 2px 10px 10px rgba(0, 0, 0, 0.1);
    }
    .pagination a, .pagination .page-link {
      margin: 0 2px;
      padding: 6px 12px;
      border-radius: 4px;
      border: 1px solid #ddd;
      color: #007bff;
      text-decoration: none;
    }
    .pagination a.active, .pagination .active .page-link {
      background: #007bff;
      color: #fff !important;
      border-color: #007bff;
    }
    .reset-btn {
      margin-left: 10px;
      color: #fff;
      background: #dc3545;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
    }
    .reset-btn:hover {
      background: #c82333;
      color: #fff;
    }
    .delete-btn {
      color: #dc3545;
      text-decoration: none;
    }
</style>
</head>
<body>
    <div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>

  <div class="sidebar" id="sidebar">
    <h2>Menu</h2>
    <ul>
      <li><a href="../admin/dashboard.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a href="../admin/t_admin.php"><i class="fas fa-user-shield"></i> Admin</a></li>
      <li><a href="../tamu/tamu.php"><i class="fas fa-users"></i> Tamu</a></li>
      <li><a href="../tamu/kehadiran.php"><i class="fas fa-user-check"></i> Kehadiran</a></li>
      <li><a href="../laporan/laporan.php"><i class="fas fa-file-alt"></i> Laporan</a></li>
      <li><a href="../admin/index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

    <div class="container table-container">
    <div class="header mb-4">
        <h2>Daftar Tamu</h2>
        
    </div>

    <form class="filter mb-4" method="GET" action="">
        <label><i class="fas fa-calendar-alt"></i> Dari:</label>
        <input type="date" name="tanggal_awal" value="<?= htmlspecialchars($tanggal_awal) ?>">

        <label>Sampai:</label>
        <input type="date" name="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir) ?>">

        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Tampilkan</button>
        <a href="tamu.php" class="reset-btn">Reset</a>
    </form>

    <table class="table table-striped table-hover text-center">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Instansi</th>
                <th>Keperluan</th>
                <th>Tanggal/Waktu</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = $mulai + 1;
            while ($row = mysqli_fetch_assoc($data)) {
                echo "<tr>
                    <td>$no</td>
                    <td>{$row['nama']}</td>
                    <td>{$row['instansi']}</td>
                    <td>{$row['keperluan']}</td>
                    <td>{$row['tanggal/waktu']}</td>
                    <td>
                        <a href='delete.php?id={$row['id']}' class='delete-btn' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">
                        <i class='fas fa-minus-circle'></i>
                        </a></td>
                    </td>
                </tr>";
                $no++;
            }

            if (mysqli_num_rows($data) == 0) {
                echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada data</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="pagination mb-4">
        <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
            <a class="<?= ($i == $halaman) ? 'active' : '' ?>" href="?halaman=<?= $i ?>&tanggal_awal=<?= urlencode($tanggal_awal) ?>&tanggal_akhir=<?= urlencode($tanggal_akhir) ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <a href="../admin/dashboard.php" class="btn btn-secondary mb-5"><i class="fas fa-arrow-left"></i> Kembali</a>

    <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }
    </script>
</body>
</html>