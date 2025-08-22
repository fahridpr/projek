<?php
include '../koneksi.php';

$result = mysqli_query($conn, "DELETE FROM admin WHERE level = 'off'");

if ($result) {
    echo "berhasil";
} else {
    echo "Gagal";
}
?>
