<?php
session_start();
include "db.php";

$error_message = ""; 

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch(); 

    if ($stmt->num_rows > 0) {
        if (password_verify($password, $hashed_password)) {
            $_SESSION['login'] = true;
            $_SESSION['id'] = $id;
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Login gagal. Username atau password salah.";
        }
    } else {
        $error_message = "Login gagal. Username atau password salah.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="flex h-screen w-full">

        <!-- Bagian kiri (sidebar) -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center text-white flex-col justify-center items-center p-12" style="background-image: url('image/bg.jpeg')">
            <h1 class="text-5xl font-bold mb-4">Selamat Datang!</h1>
            <p class="text-xl">Silahkan login terlebih dahulu.</p>
            <p class="mt-6 text-sm opacity-75">Semoga anda terbantu dengan web yang saya buat.</p>
        </div>

        <!-- Bagian kanan (form login) -->
        <div class="flex items-center justify-center w-full md:w-1/2 bg-white p-8 md:p-12">
            <div class="w-full max-w-md">
                <h2 class="text-4xl font-semibold text-center mb-6">Masukan akun anda!</h2>
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-lg font-medium mb-2" for="username">Username</label>
                        <input class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" name="username" id="username" placeholder="Masukan Username" required />
                    </div>
                    <div>
                        <label class="block text-gray-700 text-lg font-medium mb-2" for="password">Password</label>
                        <input class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password" id="password" placeholder="Masukan password" required />
                    </div>
                    <button type="submit" name="login" class="w-full bg-red-600 text-white py-4 rounded-lg text-lg font-semibold hover:bg-red-700 transition duration-200">Login</button>
                </form>

                <!-- Menampilkan pesan error jika login gagal -->
                <?php if (!empty($error_message)): ?>
                    <p class="text-red-500 mt-4 text-center"><?= $error_message ?></p>
                <?php endif; ?>

                <!-- Tautan ke halaman registrasi -->
                <p class="mt-4 text-center">
                    Belum punya akun? <a href="register.php" class="text-red-600 hover:underline">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
