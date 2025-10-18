<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-sky-900 via-cyan-900 to-blue-900 text-white min-h-screen p-8">

    <div class="max-w-5xl mx-auto bg-white/10 backdrop-blur-xl border border-cyan-600 rounded-2xl shadow-2xl p-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 to-blue-400 bg-clip-text text-transparent">
                🌊 Data Mahasiswa
            </h1>
            <a href="tambah.php" 
               class="bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-500
                      hover:from-sky-400 hover:via-cyan-500 hover:to-blue-400
                      text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg
                      hover:shadow-cyan-400/40 transition duration-300 transform hover:-translate-y-0.5">
               + Tambah Data
            </a>
        </div>

        <!-- Notifikasi -->
        <?php if (isset($_GET['pesan'])): ?>
            <div class="mb-4 p-3 rounded-lg text-center font-medium
                <?= $_GET['pesan'] === 'sukses' 
                    ? 'bg-green-800/40 text-green-300 border border-green-600' 
                    : 'bg-red-800/40 text-red-300 border border-red-600' ?>">
                <?= $_GET['pesan'] === 'sukses' ? '✅ Data berhasil disimpan!' : '❌ Terjadi kesalahan!' ?>
            </div>
        <?php endif; ?>

        <!-- Tabel -->
        <div class="overflow-hidden rounded-xl border border-cyan-600 shadow-lg">
            <table class="w-full text-sm text-cyan-100">
                <thead class="bg-gradient-to-r from-cyan-600 to-blue-500 text-white uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Nama</th>
                        <th class="py-3 px-4 text-left">Jurusan</th>
                        <th class="py-3 px-4 text-left">Umur</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-700">
                    <?php
                    // Query data mahasiswa dan jurusan
                    $query = "SELECT m.id, m.nama, m.umur, j.nama AS jurusan 
                              FROM mahasiswa m 
                              JOIN jurusan j ON m.jurusan_id = j.id
                              ORDER BY m.id ASC";

                    $result = mysqli_query($koneksi, $query);

                    // Jika gagal, fallback ke tabel mahasiswa saja
                    if (!$result) {
                        $fallbackQuery = "SELECT id, nama, umur, jurusan FROM mahasiswa ORDER BY id ASC";
                        $result = mysqli_query($koneksi, $fallbackQuery);
                    }

                    if (!$result) {
                        echo "<tr><td colspan='5' class='text-center py-5 text-red-300'>
                            ⚠️ Terjadi kesalahan query: " . htmlspecialchars(mysqli_error($koneksi)) . "
                        </td></tr>";
                    } else {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $jurusan = isset($row['jurusan']) ? $row['jurusan'] : '-';
                                echo "
                                <tr class='hover:bg-cyan-800/40 transition-all duration-200'>
                                    <td class='py-3 px-4'>{$row['id']}</td>
                                    <td class='py-3 px-4'>{$row['nama']}</td>
                                    <td class='py-3 px-4'>{$jurusan}</td>
                                    <td class='py-3 px-4'>{$row['umur']}</td>
                                    <td class='py-3 px-4 text-center'>
                                        <a href=\"edit.php?id={$row['id']}\" 
                                           class=\"text-cyan-300 hover:text-cyan-200 font-semibold transition\">Edit</a>
                                        <span class=\"text-gray-500 mx-1\">|</span>
                                        <a href=\"hapus.php?id={$row['id']}\" 
                                           onclick=\"return confirm('Yakin mau dihapus nih serius? 🥺')\" 
                                           class=\"text-red-400 hover:text-red-300 font-semibold transition\">Hapus</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-5 text-cyan-300/70'>
                                    Belum ada data mahasiswa 🌙
                                  </td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-cyan-300 text-sm">
            <p>© <?= date('Y'); ?> UTS Pemrograman Web 3 | by 
                <span class="font-semibold text-cyan-400">Muhamad wildan ridlo</span>
            </p>
        </div>
    </div>

</body>
</html>
