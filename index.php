<?php
include 'kalkulator.php';

// Memproses input dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset ($_POST['delete'])) {
        $calculator = new Calculator($conn);
        $calculator->clearHistory();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $expression = $_POST["expression"];

        $calculator = new Calculator($conn);
        $result = $calculator->calculate($expression);
        $calculator->saveCalculation($expression, $result);
        // Redirect untuk menghindari pengiriman ulang formulir saat meng-refresh halaman
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Menampilkan data riwayat
$calculator = new Calculator($conn);
$history = $calculator->getHistory();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator</title>
    <link href="assets/css/style.css" rel="stylesheet">
    <script>
          function isiKolomPerhitungan(ekspresi) {
            document.forms[0].expression.value = ekspresi;
        }

        function satuKlikPerhitungan(ekspresi) {
            isiKolomPerhitungan(ekspresi);
            document.forms[0].submit();
        }
    </script>
</head>

<body>
    <h2>Kalkulator</h2>
    <form action="" method="post">
        <input type="text" name="expression" placeholder="Masukkan angka" required>
        <button type="submit">Hitung</button>
        <!-- Tombol operasi matematika -->
        <button type="button" name="plus" onclick="document.forms[0].expression.value += '+';">+</button>
        <button type="button" name="minus" onclick="document.forms[0].expression.value += '-';">-</button>
        <button type="button" name="times" onclick="document.forms[0].expression.value += '*';">*</button>
        <button type="button" name="divide" onclick="document.forms[0].expression.value += '/';">/</button>
    </form>

    <h3>Riwayat Perhitungan</h3>
    <div class="scrollable-table">
    <table>
        <thead>
            <tr>
                <th>Ekspresi</th>
                <th>Hasil</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php $index = 1; ?>
            <?php foreach ($history as $calculation): ?>
                <tr>
                    <td><?= $index++ ?></td>
                    <td><?= $calculation['expression'] ?></td>
                    <td><?= $calculation['result'] ?></td>
                    <td>
                        <!-- Menambahkan tombol untuk menggunakan kembali perhitungan -->
                        <form action="" method="post" style="display: inline;">
                    <!-- Menambahkan atribut onclick untuk memanggil fungsi isiKolomPerhitungan -->
                    <button type="button" onclick="isiKolomPerhitungan('<?= $calculation['expression'] ?>')">Gunakan Kembali</button>
                </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="delete-button">
        <form action="" method="post">
            <button type="submit" name="delete">Hapus Riwayat</button>
        </form>
</body>

</html>
