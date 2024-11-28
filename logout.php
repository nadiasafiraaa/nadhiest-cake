<?php
session_start();

// Hapus semua sesi
session_unset();
session_destroy();

// Redirect ke halaman beranda
header('Location: index.php');
exit;
?>
