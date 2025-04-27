<?php
require 'vendor/autoload.php'; // Pastikan MongoDB sudah terpasang via composer

// Koneksi MongoDB
$uri = 'mongodb+srv://HansDB:Hansmoses2007#@lisensi.98zue9l.mongodb.net/?retryWrites=true&w=majority';
$client = new MongoDB\Client($uri);
$database = $client->selectDatabase('Lisensi');  // Nama database
$collection = $database->selectCollection('Licenses'); // Nama koleksi

session_start();

// Cek apakah password benar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
    $password_input = $_POST['password'];
    $correct_password = 'password123'; // Ganti dengan password yang kamu inginkan

    if ($password_input == $correct_password) {
        $_SESSION['logged_in'] = true;
    } else {
        $error = 'Password salah!';
    }
}

// Proses untuk menambah lisensi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['license']) && isset($_SESSION['logged_in'])) {
    $license = $_POST['license'];

    // Insert lisensi ke MongoDB
    $collection->insertOne(['license' => $license, 'created_at' => new MongoDB\BSON\UTCDateTime()]);
    $success = 'Lisensi berhasil ditambahkan!';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisensi System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) { ?>
        <h1>Tambah Lisensi</h1>
        <form method="POST" action="action.php">
            <input type="text" name="license" placeholder="Masukkan lisensi baru" required>
            <button type="submit">Tambah Lisensi</button>
        </form>
        <?php if (isset($success)) { echo "<p class='message'>$success</p>"; } ?>
    <?php } else { ?>
        <h1>Login Lisensi</h1>
        <form method="POST" action="action.php">
            <input type="password" name="password" placeholder="Masukkan password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <?php } ?>
</div>

</body>
</html>
