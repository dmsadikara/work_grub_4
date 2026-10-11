## STACK DAN QUEUE

```
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

