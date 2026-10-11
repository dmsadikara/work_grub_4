## STACK DAN QUEUE

```
<?php

session_start(); // Aktifkan session supaya antrean, riwayat, dan stok tidak hilang saat halaman dimuat ulang

// 1. Data barang inventaris ATK (STRUKTUR DATA ARRAY)
// Satu baris = satu barang (array asosiatif: kode, nama, stok, harga).
$barang = [
    ["kode" => "PNS-01", "nama" => "Pensil 2B",     "stok" => 120, "harga" => 3500],
    ["kode" => "SPD-01", "nama" => "Spidol Hitam",  "stok" => 80,  "harga" => 4000],
    ["kode" => "PNG-01", "nama" => "Penghapus",     "stok" => 60,  "harga" => 2500],
    ["kode" => "KRT-01", "nama" => "Kertas HVS A4", "stok" => 25,  "harga" => 52000],
    ["kode" => "KRT-02", "nama" => "Kertas Folio",  "stok" => 45,  "harga" => 38000],
    ["kode" => "STP-01", "nama" => "Stapler",       "stok" => 15,  "harga" => 18000],
    ["kode" => "SLT-01", "nama" => "Selotip",       "stok" => 30,  "harga" => 5000],
    ["kode" => "STC-01", "nama" => "Sticky Notes",  "stok" => 20,  "harga" => 3000],
    ["kode" => "GNT-01", "nama" => "Gunting",       "stok" => 10,  "harga" => 12000],
    ["kode" => "MPK-01", "nama" => "Map Kertas",    "stok" => 25,  "harga" => 8000],
];
$barangAwal = $barang; // simpan data awal untuk tombol reset

// Stok bisa berubah karena pelayanan antrean, jadi pakai data dari session kalau sudah ada
if (isset($_SESSION["barang"])) {
    $barang = $_SESSION["barang"];
}

// 2. Stack: item yang MASUK TERAKHIR adalah yang KELUAR PERTAMA (seperti tumpukan piring)
class Stack
{
    private array $data =[];

    public function __construct(array $data = []) { $this->data = $data; }

    // push: taruh item di tumpukan paling atas
    public function push(array $item): void { $this->data[] = $item; }

    // pop: ambil dan buang item paling atas (yang terakhir masuk)
    public function pop(): ?array { return array_pop($this->data); }

    public function isEmpty(): bool { return count($this->data) === 0; }

    public function toArray(): array { return $this->data; }
}

// 3. Queue: item yang MASUK PERTAMA adalah yang KELUAR PERTAMA (seperti antrean di loket)
class Queue
{
    private array $data = [];

    public function __construct(array $data = []) { $this->data = $data; }

    // enqueue: masukkan item di barisan paling belakang
    public function enqueue(array $item): void { $this->data[] = $item; }

    // dequeue: ambil dan buang item paling depan (yang pertana masuk)
    public function dequeue(): ?array { return array_shift($this->data); }

    public function isEmpty(): bool { return count($this->data) === 0; }

    public function toArray(): array { return $this->data; }
}

// Fungsi bantu: htmlspecialchars dan format rupiah
function h($teks) { return htmlspecialchars($teks); }
function rp($angka) { return "Rp" . number_format($angka, 0, ",", "."); }

// 4. Proses antrean (Queue) dan riwayat pelayanan (stack)
$antrean = new Queue($_SESSION["antrean"] ?? []);
$riwayat = new Stack($_SESSION["riwayat"] ?? []);
$pesan = "";
$aksi = $_POST["aksi"] ?? "";

if ($aksi === "tambah") {
    // Tombol "Masuk antrean": enqueue permintaan baru
    $peminta = trim($_POST["peminta"] ?? "");
    $jumlah = (int)($_POST["jumlah"] ?? 0);
    if ($peminta === "" || $jumlah < 1) {
        $pesan = :"Isi nama peminta dan jumlah minimal 1.";
    } else {
        $antrean->enqueue(["peminta" => $peminta, "barang" => $_POST["barang"], "jumlah" => $jumlah]);
        $pesan = $peminta . " masuk antrean.";
    }
} elseif ($aksi === "layani") {
    // Tombol "Layani": dequeue permintaan paling depan, kurangi stok, catat ke stack
    $permintaan = $antrean->dequeue();
    if ($permintaan === null) {
        $pesan = "Antrean kosong.";
    } else {
        $pesan = "Barang '" . $permintaan["barang"] . "' tidak ditemukan.";
        foreach ($barang as $i => $item) {
            if (strcasecmp($item["nama"], $permintaan["barang"]) == 0) {
                if ($item["stok"] >= $permintaan["jumlah"]) {
                    $barang[$i]["stok"] -= $permintaan["jumlah"];
                    $riwayat->push($permintaan);
                    $pesan = $permintaan["peminta"] . " dilayani: " . $permintaan["jumlah"] . " " . $item["nama"] . ".";
                } else {
                    $pesan = $permintaan["peminta"] . " ditolak: stok " . $item["nama"] . " hanya " . $item["stok"] . ".";
                }
            }
        }
    }
} elseif ($aksi === "undo") {
    // Tombol "Undo": pop pelayanan paling baru, kembalikan stoknya
    $terakhir = $riwayat->pop();
    if ($terakhir === null) {
        $pesan = "Riwayat kosong, tidak ada yang bisa dibatalkan.";
    } else {
        foreach ($barang as $i => $item) {
            if (strcasecmp($item["nama"], $terakhir["barang"]) == 0) {
                $barang[$i]["stok"] += $terakhir["jumlah"];
            }
        }
        $pesan = "Dibatalkan: " . $terakhir["peminta"] . " minta " . $terakhir["jumlah"] . " " . $terakhir["barang"] . ". Stok dikembalikan.";
    }
} elseif ($aksi === "reset") {
    // Tombol "Reset": kembalikan semuanya seperti awal
    $barang = $barangAwal;
    $antrean = new Queue();
    $riwayat = new Stack();
    $pesan = "Data dikembalikan seperti awal.";
}

// Simpan keadaan terbaru ke session
$_SESSION["barang"] = $barang;
$_SESSION["antrean"] = $antrean->toArray();
$_SESSION["riwayat"] = $riwayat->toArray();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stack dan Queue - Inventaris ATK Kantor</title>
</head>
<body>

    <h2>Inventaris ATK Kantor</h2>
    <h3>Stack dan Queue</h3>

    <h3>Data Barang</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>No</th><th>Kode</th><th>Nama</th><th>Stok</th><th>Harga</th>
        </tr>
        <?php foreach ($barang as $i => $item): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= h($item["kode"]) ?></td>
            <td><?= h($item["nama"]) ?></td>
            <td><?= $item["stok"] ?></td>
            <td><?= rp($item["harga"]) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>1. Antrean Permintaan Barang (Queue)</h3>
    <?php if ($pesan !== ""): ?>
        <p><b><?= h($pesan) ?></b></p>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="aksi" value="tambah">
        Nama peminta: <input type="text" name="peminta" required>
        Barang:
        <select name="barang">
            <?php foreach ($barang as $item): ?>
                <option><?= h($item["nama"]) ?></option>
            <?php endforeach; ?>
        </select>
        Jumlah: <input type="number" name="jumlah" value="1" min="1">
        <button type="submit">Masuk antrean</button>
    </form>
    <form method="POST">
        <input type="hidden" name="aksi" value="layani">
        <button type="submit">Layani antrean paling depan</button>
    </form>
    <p> Isi antrean (paling atas = paling depan):</p>
    <?php if ($antrean->isEmpty()): ?>
        <p><i>Antrean kosong.</i></p>
    <?php else: ?>
        <ol>
            <?php foreach ($antrean->toArray() as $p): ?>
                <li><?= h($p["peminta"]) ?> minta <?= $p["jumlah"] ?> <?= h($p["barang"]) ?></li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <h3>2. Riwayat Pelayanan (Stack)</h3>
    <form method="POST">
        <input type="hidden" name="aksi" value="undo">
        <button type="submit">Undo (batalkan pelayanan terakhir)</button>
    </form>
    <p>Isi riwayat (paling atas = paling baru):</p>
    <?php if ($riwayat->isEmpty()): ?>
        <p><i>Riwayat kosong.</i></p>
    <?php else: ?>
        <ol>
            <?php foreach (array_reverse($riwayat->toArray()) as $r): ?>
                <li><?= h($r["peminta"]) ?> mengambil <?= $r["jumlah"] ?> <?= h($r["barang"]) ?></li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="aksi" value="reset">
        <button type="submit">Reset semua (stok, antrean, riwayat)</button>
    </form>

</body>
</html>
