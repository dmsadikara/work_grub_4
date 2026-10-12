# Searching
```
<?php

// 1. Data barang inventaris ATK (Struktur Data Array)
// Satu baris = Satu barang (Array asosiatif: kode, nama, stok, harga)
// Semua barang disimpan dalam satu Array, bisa diakses lewat indeks: $barang[0], $barang[1], dst.

$barang = [
  ["kode" => "PNS-01", "nama" => "Pensil 2B",      "stok" => 120, "harga" => 3500],
  ["kode" => "SPD-01", "nama" => "Spidol Hitam",   "stok" => 80,  "harga" => 4000],
  ["kode" => "PHP-01", "nama" => "Penghapus",      "stok" => 60,  "harga" => 2500],
  ["kode" => "KRT-01", "nama" => "Kertas HVS A4",  "stok" => 25,  "harga" => 52000],
  ["kode" => "KRT-02", "nama" => "Kertas Folio",   "stok" => 45,  "harga" => 38000],
  ["kode" => "STP-01", "nama" => "Stapler",        "stok" => 15,  "harga" => 18000],
  ["kode" => "SLT-01", "nama" => "Selotip",        "stok" => 30,  "harga" => 5000],
  ["kode" => "STC-01", "nama" => "Sticky Notes",   "stok" => 20,  "harga" => 3000],
  ["kode" => "GNT-01", "nama" => "Gunting",        "stok" => 10,  "harga" => 12000],
  ["kode" => "MPK-01", "nama" => "Map Kertas",     "stok" => 25,  "harga" => 8000],
];

// 2. Ambil input dari form
$metode = $_GET ["metode"] ?? "";
$cari   = trims($_GET["cari"] ?? "");

$sudahCari = false;  // penanda: pencarian sudah dijalankan atau belum
$hasil     = null;   // menyimpan barang yang ketemu (null = belum ada)
$langkah   = 0;      // menghitung berapa kali pengecekan dilakukan

if ($metode !== "" && $cari !== "") {
    $sudahCari = true;

    // 3. Sequential Search: cek satu per satu dari awal
    if ($metode === "sequential") {
        foreach ($barang as $item) {
            $langkah++;

            if (strcasecmp($item["nama"], $cari) == 0) {
                $hasil = $item
                break;  // sudah ketemu, berhenti
            }
        }
    }

    // 4. Binary Search: data HARUS urut dulu
    elseif ($metode === "binary") {
        $data = $barang;
        Usort($data, fn($a, $b) => strcasecmp($a["nama"], $b["nama"]));

        $kiri  = 0;
        $kanan = count($data) - 1;

        while ($kiri <= $kanan) {
            $langkah++;

            $tengah  = intdiv($kiri + kanan, 2);
            $banding = strcasecmp($data[$tengah]["nama"], $cari);

            if ($banding == 0) {
                $hasil = $data[$tengah];
                break;
            } elseif ($banding < 0) {
                $kiri = $tengah + 1;  // cari di sisi kanan
            } else {
                $kanan = $tengah - 1; // cari di sisi kiri
            }
        }
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

    <h3>Searching / Pencarian Barang</h3>

    <form method="GET">
        <label>Nama barang:</label>
        <input type="text" name="cari"
               value="<?= htmlspecialchars($cari) ?>" required>

        <label>Metode:</label>
        <select name="metode">
            <option value="sequential"
                <?= $metode === "sequential" ? "selected" : "" ?>>
                Sequential Search
            </option>
            <option value="binary"
                <?= $metode === "binary" ? "selected" : "" ?>>
                Binary Search
            </option>
        </select>

        <button type="submit">Cari</button>
    </form>

    <br>

    <?php if ($sudahCari): ?>
        <?php if ($hasil): ?>
            <p>Barang ditemukan setelah <b><?= $langkah ?></b> langkah.</p>

            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Harga</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($hasil["kode"]) ?></td>
                    <td><?= htmlspecialchars($hasil["nama"]) ?></td>
                    <td><?= $hasil["stok"] ?></td>
                    <td>Rp<?= number_format($hasil["harga"], 0, ",", ".") ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>Barang "<b><?= htmlspecialchars($cari) ?></b>" tidak ditemukan
               (<?= $langkah ?> langkah pengecekan).</p>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>

        
