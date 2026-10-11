## STACK DAN QUEUE

```
session_start(); // Aktifkan session supaya antrean, riwayat, dan stok tidak hilang saat halaman dimuat ulang

// 1. Data barang inventaris ATK (STRUKTUR DATA ARRAY)
// Satu baris = satu barang (array asosiatif: kode, nama, stok, harga).
$barang = [
    ["kode" => "PNS-01", "nama" => "Pensil 2B",     "stok" => 120, "harga" => 3500],

