<?php
require 'vendor/autoload.php'; // Pastikan sudah terpasang dengan composer

// Koneksi MongoDB
$uri = 'mongodb+srv://HansDB:Hansmoses2007#@lisensi.98zue9l.mongodb.net/?retryWrites=true&w=majority';
$client = new MongoDB\Client($uri);
$database = $client->selectDatabase('Lisensi');  // Nama database
$collection = $database->selectCollection('Licenses'); // Nama koleksi

session_start();

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
    $password_input = $_POST['password'];
    $correct_password = 'password123'; // Ganti dengan password yang kamu inginkan

    if ($password_input == $correct_password) {
        $_SESSION['logged_in'] = true;
    } else {
        $error = 'Password salah!';
    }
}

// Proses tambah lisensi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['license'])) {
    $license = $_POST['license'];

    // Cek apakah database sudah ada
    $dbList = $client->listDatabases();
    $isDbExist = false;
    foreach ($dbList as $db) {
        if ($db['name'] == 'Lisensi') {
            $isDbExist = true;
            break;
        }
    }

    // Jika database belum ada, buat database baru
    if (!$isDbExist) {
        $client->selectDatabase('Lisensi'); // MongoDB otomatis akan membuat database saat data pertama kali ditambahkan
    }

    // Insert data lisensi
    $collection->insertOne(['license' => $license, 'created_at' => new MongoDB\BSON\UTCDateTime()]);
    $success = 'Lisensi berhasil dibuat!';
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
    <h1>Login Lisensi</h1>

    <?php if (!isset($_SESSION['logged_in'])) { ?>
        <form method="POST" action="">
            <input type="password" name="password" placeholder="Masukkan password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <?php } else { ?>
        <h2>Tambah Lisensi</h2>
        <form method="POST" action="">
            <input type="text" name="license" placeholder="Masukkan lisensi baru" required>
            <button type="submit">Tambah Lisensi</button>
        </form>

        <?php if (isset($success)) { echo "<p class='message'>$success</p>"; } ?>
    <?php } ?>
</div>

</body>
</html>
