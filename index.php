<?php
include 'db.php';
session_start();

// Ambil kata kunci pencarian dari parameter GET
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Perbarui query untuk mendukung pencarian
$query = "SELECT * FROM products";
if (!empty($search)) {
    $query .= " WHERE name LIKE '%$search%'";
}
$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Nadhiest Cake</title>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <!-- Logo dan Nama Toko -->
            <div class="center-section">
                <div class="logo">
                    <img src="uploads/logo.jpg" alt="Nadhiest Cake Logo">
                </div>
                <h1>Nadhiest Cake</h1>
                <!-- Form Pencarian -->
                <form action="index.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Cari produk..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Cari</button>
                </form>
                <!-- Navigasi -->
                <nav class="nav-menu">
                    <ul>
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="#contact">Kontak</a></li>
                        <li><a href="#help">Bantuan</a></li>
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                            <li><a href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Login Admin</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <!-- Deskripsi Selamat Datang -->
        <section class="welcome-section">
            <p>
                Kami hadir untuk membawa kelezatan ke setiap momen spesial Anda.<br/>
                Nadhiest Cake adalah tempat di mana cita rasa dan kualitas bertemu dalam setiap gigitan.<br/>
                Kami bangga menyajikan berbagai pilihan kue dan dessert yang dibuat dengan bahan-bahan berkualitas tinggi dan penuh cinta.
            </p>
            <p>
                Baik itu ulang tahun, pernikahan, acara keluarga, atau sekadar memanjakan diri, kami siap membantu Anda menciptakan kenangan manis yang tak terlupakan.<br/>
                Temukan koleksi kue spesial kami yang dapat disesuaikan sesuai selera dan kebutuhan Anda.
            </p>
        </section>

        <!-- Produk -->
        <h2>Semua Cake</h2>
        <div class="product-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <!-- Kontak -->
            <div class="footer-section" id="contact">
                <h3>Kontak Kami</h3>
                <p>Email: info@nadhiestcake.com</p>
                <p>Telepon: +62 812 3456 7890</p>
            </div>

            <!-- Hak Cipta -->
            <div class="footer-section footer-center">
                <p>&copy; 2024 Nadhiest Cake. All rights reserved.</p>
            </div>

            <!-- Bantuan -->
            <div class="footer-section" id="help">
                <h3>Bantuan</h3>
                <p>Untuk informasi lebih lanjut, silakan hubungi tim kami.</p>
            </div>
        </div>
    </footer>
</body>
</html>
