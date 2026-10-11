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

    public function isEmpty(): bool { return count(this->data) === 0; }

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
        $pesan = :Isi nama peminta dan jumlah minimal 1.";
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
        foreach ($barang as $i = $item) {
            if (strcasecmp($item["nama], $permintaan["barang"]) == 0) {
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


