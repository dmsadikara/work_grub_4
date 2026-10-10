## SORTING
```

<?php

// 1. Data barang inventaris ATK (STRUKTUR DATA ARRAY)
// Saru baris = satu barang (array asosiatif: kode, nama, stok harga).
// Semua barang disimpan dalam satu array, bisa diakses lewat indeks: $barang[0], $barang[1], dst.

$barang = [
    ["kode" => "PNS-01", "nama" => "Pensil 2B",     "stok" => 120,  "harga" => 3500],
    ["kode" => "SPD-01", "nama" => "Spidol Hitam",  "stok" => 80,   "harga" => 4000],
    ["kode" => "PNG-01", "nama" => "Penghapus",     "stok" => 60,   "harga" => 2500],
    ["kode" => "KRT-01", "nama" => "Kertas HVS A4", "stok" => 25,   "harga" => 52000],
    ["kode" => "KRT-02", "nama" => "Kertas Folio",  "stok" => 45,   "harga" => 38000],
    ["kode" => "STP-01", "nama" => "Stapler",       "stok" => 15,   "harga" => 18000],
    ["kode" => "SLT-01", "nama" => "Selotip",       "stok" => 30,   "harga" => 5000],
    ["kode" => "STC-01", "nama" => "Sticky Notes",  "stok" => 20,   "harga" => 3000],
    ["kode" => "GNT-01", "nama" => "Gunting",       "stok" => 10,   "harga" => 12000],
    ["kode" => "MPK-01", "nama" => "Map Kertas",    "stok" => 25,   "harga" => 8000],
];

// 2. Ambil pilihan algoritma dari form
$metode = $_GET["metode"] ?? "";
$hasil = $barang;

// 3. Bubble Sort
if ($metode == "bubble") {
    $n = count($hasil);

    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {

            if (strcasecmp(
                $hasil[$j]["nama"],
                $hasil[$j + 1]["nama"]
            ) > 0) {

                // Tukar dua barang bersebelahan
                $temp = $hasil[$j];
                $hasil[$j] = $hasil[$j + 1];
                $hasil[$j + 1] = $temp;
            }
        }
    }
}

// 4. Selection Sort
elseif ($metode === "selection") {
    $n = count($hasil);

    for ($i = 0; $i < $n - 1; $i++) {
        $min = $i;

        // Cari nama terkecil di sisa data
        for ($j = $i + 1; $j < $n; $j++) {
            if (strcasecmp(
                $hasil[$j]["nama"],
                $hasil[$min]["nama"]
            ) < 0) {
                $min = $j;
            }
        }
        // Tukar dengan posisi saat ini
        $temp = $hasil[$i];
        $hasil[$i] = $hasil[$min];
        $hasil[$min] = $temp;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inventaris ATK Kantor</title>
</head>
<body>

    <h2>Inventaris ATK Kantor</h2>

    <h3>Sorting / Pengurutan Barang</h3>

    <form method="GET">
        <label>Pilih metode sorting:</label>

        <select name="metode">
            <option value="">-- Data awal</option>
            <option value="bubble"
                <? $metode === "bubble" ? "selected" : "" ?>>
                Bubble Sort
            </option>
        </select>

        <button type="submit">Urutkan</button>
    </form>

    <br>

    <table border="1" cellpading="10"
            cellspacing="0">
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Harga</th>
        </tr>

        <?php foreach ($hasil as $i => &item): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($item["kode"]) ?></td>
            <td><?= htmlspecialchars($item["nama"]) ?></td>
            <td><?= $item["stok"] ?></td>
            <td>Rp<?= number_format($item["harga"], 0, ",", ",") ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
