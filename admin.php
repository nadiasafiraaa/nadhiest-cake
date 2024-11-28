<?php
include 'db.php';

// Tambah atau Edit Produk
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $query = "";

    if ($id == "") { // Jika ID kosong, tambahkan produk baru
        $target = "uploads/" . basename($image);
        $query = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$image')";
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else { // Jika ID ada, lakukan edit
        if (!empty($image)) {
            $target = "uploads/" . basename($image);
            $query = "UPDATE products SET name='$name', price='$price', image='$image' WHERE id=$id";
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        } else {
            $query = "UPDATE products SET name='$name', price='$price' WHERE id=$id";
        }
    }

    if ($conn->query($query) === TRUE) {
        header("Location: admin.php");
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// Hapus Produk
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: admin.php");
}

// Ambil Data Produk untuk Edit
$editProduct = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM products WHERE id=$id");
    $editProduct = $result->fetch_assoc();
}

// Ambil Semua Produk
$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Nadhiest Cake</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .header {
            background: linear-gradient(90deg, #e6cb7d, #ce8f1b);
            padding: 20px;
            text-align: center;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            border-radius: 8px;
        }
        .header a {
            color: #914f04;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
        }
        .header a:hover {
            color: #fae4bc;
        }
        main {
            text-align: center;
            margin-top: 20px;
        }
        h2 {
            font-size: 28px;
            color: #444;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
            margin-bottom: 30px;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="file"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            background: #ce8f1b;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background: #ffd359;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table thead {
            background: #ce8f1b;
            color: #fff;
        }
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        table img {
            width: 80px;
            height: auto;
            border-radius: 5px;
        }
        table td a {
            text-decoration: none;
            color: #fff;
            padding: 7px 14px;
            border-radius: 5px;
        }
        table td a.delete {
            background: #e74c3c;
        }
        table td a.edit {
            background: #3498db;
        }
        table td a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Admin Nadhiest Cake</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <h2>Manajemen Produk</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $editProduct['id'] ?? ''; ?>">
            <input type="text" name="name" placeholder="Nama Cake" value="<?php echo $editProduct['name'] ?? ''; ?>" required>
            <input type="number" name="price" placeholder="Harga Cake" value="<?php echo $editProduct['price'] ?? ''; ?>" required>
            <input type="file" name="image" accept="image/*">
            <?php if ($editProduct): ?>
                <button type="submit" name="save">Simpan Perubahan</button>
            <?php else: ?>
                <button type="submit" name="save">Tambah Produk</button>
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                        <td>
                            <a class="edit" href="admin.php?edit=<?php echo $row['id']; ?>">Edit</a>
                            <a class="delete" href="admin.php?delete=<?php echo $row['id']; ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
