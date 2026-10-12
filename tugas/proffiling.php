# Sorting Profiling
```
<?php
$barang = [
    ["nama" => "Pensil 2B"],     ["nama" => "Spidol Hitam"], ["nama" =>     "Penghapus"],
    ["nama" => "Kertas HVS A4"], ["nama" => "Kertas Folio"], ["nama" => "Stapler"],
    ["nama" => "Selotip"],       ["nama" => "Sticky Notes"], ["nama" => "Gunting"],
    ["nama" => "Map Kertas"],
];

$jalan = isset($_GET["jalan"]); // true setelah tombol ditekan
$hasil = [];

if ($jalan) {
    $n = count($barang);

    // Bubble Sort
    $data = $barang; $banding = 0; $tukar = 0; $mulai = hrtime(true);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
             $banding++;
             if (strcasecmp($data[$j]["nama"], $data[$j + 1]["nama"]) > 0) {
                [$data[$j], $data[$j + 1]] = [$data[$j + 1], $data[$j]];
                $tukar++;
            }
        }
    }
    $hasil[] = ["Bubble Sort", $banding, $tukar, (hrtime(true) - $mulai) / 1000];

    // Selection Sort
    $data = $barang; $banding = 0; $tukar = 0; $mulai = hrtime(true);
    for ($i = 0; $i < $n - 1; $i++) {
        $min = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            $banding++;
            if (strcasecmp($data[$j]["nama"], $data[$min]["nama"]) < 0) $min = $j;
        }
        if ($min != $i) {
            [$data[$i], $data[$min]] = [$data[$min], $data[$i]];
            $tukar++;
        }
    }
    $hasil[] = ["Selection Sort", $banding, $tukar, (hrtime(true) - $mulai) / 1000];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profiling Sorting</title>
</head>
<body>
    <h2>Profiling Sorting - Inventaris ATK</h2>

    <form method="GET">
        <button type="submit" name="jalan" value="1">Mulai Profiling</button>
    </form>
    <br>

    <?php if ($jalan): ?>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Metode</th><th>Perbandingan</th><th>Pertukaran</th><th>Waktu (&micro;s)</th>
        </tr>
        <?php foreach ($hasil as [$nama, $b, $t, $w]): ?>
        <tr>
           <td><?= $nama ?></td>
           <td><?= $b ?></td>
           <td><?= $t ?></td>
           <td><?= number_format($w, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
 </body>
 </html>
```
# Searching Profiling
```
<?php
$barang = [
    ["kode" => "PNS-01", "nama" => "Pensil 2B",     "stok" => 120, "harga" => 3500],
    ["kode" => "SPD-01", "nama" => "Spidol Hitam",  "stok" => 80, "harga" => 4000],
    ["kode" => "PNG-01", "nama" => "Penghapus",     "stok" => 60, "harga" => 2500],
    ["kode" => "KRT-01", "nama" => "Kertas HVS A4", "stok" => 25, "harga" => 52000],
    ["kode" => "KRT-02", "nama" => "Kertas Folio",  "stok" => 45, "harga" => 38000],
    ["kode" => "STP-01", "nama" => "Stapler",       "stok" => 15, "harga" => 18000],
    ["kode" => "SLT-01", "nama" => "Selotip",       "stok" => 30, "harga" => 5000],
    ["kode" => "STC-01", "nama" => "Sticky Notes",  "stok" => 20, "harga" => 3000],
    ["kode" => "GNT-01", "nama" => "Gunting",       "stok" => 10, "harga" => 12000],
    ["kode" => "MPK-01", "nama" => "Map Kertas",    "stok" => 25, "harga" => 8000],
];

$cari = trim($_GET["cari"] ?? "");

if ($cari !== "") {

   // Sequential Search
   $mulai = hrtime(true);
   $langkahSeq = 0;
   $ketemu = false;

   foreach ($barang as $item) {
       $langkahSeq++;
       if (strcasecmp($item["nama"], $cari) == 0) {
           $ketemu = true;
           break;
       }
   }
   $waktuSeq = (hrtime(true) - $mulai) / 1000; // mikrodetik

   // Binary Search (termasuk sorting)
   $mulai = hrtime(true);
   $data = $barang;
   usort($data, fn($a, $b) => strcasecmp($a["nama"], $b["nama"]));

   $langkahBin = 0;
   $kiri = 0;
   $kanan = count ($data) - 1;

    while ($kiri <= $kanan) {
        $langkahBin++;
        $tengah = intdiv($kiri + $kanan, 2);
        $banding = strcasecmp($data[$tengah]["nama"], $cari);

        if ($banding == 0) {
            break;
        } elseif ($banding < 0) {
            $kiri = $tengah + 1;
        } else }
            $kanan = $tengah - 1;
        {
    }
    $waktuBin = (hrtime(true) - $mulai) / 1000; // mikrodetik
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profiling Searching</title>
</head>
<body>

    <h2>Profiling Searching - Inventaris ATK</h2>

    <form method="GET">
        Nama barang:
        <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" required>
        <button type="submit">Bandingkan</button>
    </form>

    <?php if ($cari !== ""): ?>
        <p><?= $ketemu ? "Barang ditemukan." : "Barang tidak ditemukan." ?></p>

        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Metode</th>
                <th>Langkah</th>
                <th>Waktu (&micro;s)</th>
            </tr>
            <tr>
                <td>Sequential Search</td>
                <td><?= $langkahSeq ?></td>
                <td><?= number_format($waktuSeq, 2) ?>
            </tr>
            <tr>
                <td>Binary Search (+ sorting)</td>
                <td><?= $langkahBin ?></td>
                <td><?= number_format($waktuBin, 2) ?>
            </tr>
        </table>
    <?php endif; ?>

</body>
</html>
    
