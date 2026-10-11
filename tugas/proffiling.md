# Sorting Profiling
```
<?php
$barang = [
["nama" => "Pensil 2B'],     ["nama" => "Spidol Hitam"], ["nama" => "Penghapus"],
["nama" => "Kertas HVS A4"], ["nama" => "Kertas Folio"], ["nama" => "Stapler"],
["nama" => "Selotip"],       ["nama" => "Sticky Notes"], ["nama" => "Gunting"],
["nama" => "Map Kertas"],
];

$jalan = isset($_GET["jalan"]); // true setelah tombol ditekan
$hasil = [];

if ($jalan) [
    $n = count($barang);

    // Bubble Sort
    $data = $barang; $banding = 0; $tukar = 0; $mulai = hrtime(true);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
             $banding++;
             if (strcasecmp($data[$j]["nama], $data[$j + 1]["nama"]) > 0) {
                [$data[$j], $data[$] + 1]] = [$data[$j + 1], $data[$j]];
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
            if (stracasecmp($data[$j]["nama"], $data[$min]["nama"]) < 0) $min = $j;
        }
        if ($min != $i) {
            [$data[$i], $data[$min]] = [$data[$min], $data[$i];
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
    <h2>Profiling Sorting - Iventaris ATK</h2>

    <form method="GET">
        <button type="submit" name="jalan" value="1">Mulai Profiling</button>
    </from>
    <br>

<?php if ($jalan): ?>
