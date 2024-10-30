<?php
session_start();
include "db.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// Cek penghapusan data
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']); // Mencegah SQL Injection
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Data berhasil dihapus!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Ambil data dari tabel user
$query = "SELECT * FROM users"; 
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-100 via-red-50 to-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-6xl p-5 md:p-10 bg-white rounded-3xl shadow-lg transform transition hover:scale-105 duration-200">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <h2 class="text-4xl font-semibold text-red-600 tracking-tight">Dashboard</h2>
            <a href="logout.php" class="bg-red-500 text-white px-6 py-2 rounded-full font-medium shadow-lg hover:bg-red-600 transition duration-300 mt-4 md:mt-0">
                Logout
            </a>
        </div>

        <!-- Tampilkan pesan -->
        <?php if (isset($_SESSION['message'])): ?>
            <div id="message" class="bg-green-500 text-white p-4 rounded mb-4">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
                ?>
            </div>
        <?php endif; ?>

        <!-- Data Pengguna Table -->
        <h3 class="text-2xl font-medium text-gray-700 mb-4">Data Pengguna</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-xl shadow-md">
                <thead>
                    <tr class="bg-gradient-to-r from-red-500 to-red-400 text-white uppercase text-sm leading-normal tracking-wider">
                        <th class="px-4 py-3 border-b-2 text-center font-light">ID</th>
                        <th class="px-4 py-3 border-b-2 text-center font-light">Nama</th>
                        <th class="px-4 py-3 border-b-2 text-center font-light">Username</th>
                        <th class="px-4 py-3 border-b-2 text-center font-light">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-red-50 transition duration-200">
                        <td class="px-4 py-4 border-b text-center"><?php echo $row['id']; ?></td>
                        <td class="px-4 py-4 border-b text-center"><?php echo $row['name']; ?></td>
                        <td class="px-4 py-4 border-b text-center"><?php echo $row['username']; ?></td>
                        <td class="px-4 py-4 border-b text-center">
                            <a href="dashboard.php?hapus=<?php echo $row['id']; ?>" class="bg-red-500 text-white px-4 py-1 rounded-full font-medium shadow hover:bg-red-600 transition duration-300 transform hover:scale-105" onclick="return confirm('Yakin ingin menghapus?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Cek apakah ada pesan
        const message = document.getElementById('message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
