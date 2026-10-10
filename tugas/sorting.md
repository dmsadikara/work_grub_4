## SORTING
```

<?php
// 1. Data barang inventaris ATK
$barang = [
    ["kode" => "PNS-01", "nama" => "Pensil 2B"],
    ["kode" => "SPD-01", "nama" => "Spidol Hitam"],
    ["kode" => "PNG-01", "nama" => "Penghapus"],
    ["kode" => "KRT-01", "nama" => "Kertas HVS A4"],
    ["kode" => "KRT-02", "nama" => "Kertas Folio"],
    ["kode" => "STP-01", "nama" => "Stapler"],
    ["kode" => "SLT-01", "nama" => "Selotip"],
    ["kode" => "STC-01", "nama" => "Sticky Notes"],
    ["kode" => "GNT-01", "nama" => "Gunting"],
    ["kode" => "MPK-01", "nama" => "Map Kertas"],

];

// 2. Ambil pilihan algoritma dari form
&metode = $_GET["metode"] // "";
$hasil = $barang;

// 3. Bubble Sort
if ($metode == "bubble") {
    $n = count($hasil);

    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {

            if (strcasecmp(
                $hasil[$j]["nama"]
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
        
