<?php
if (isset($_GET['tahun'])) {
    $menu = json_decode(file_get_contents("http://tes-web.landa.id/intermediate/menu"));
    $transaksi = json_decode(file_get_contents("http://tes-web.landa.id/intermediate/transaksi?tahun=" . $_GET['tahun']));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        td,
        th {
            font-size: 11px;
        }
    </style>


    <title>TES - Venturo Camp Tahap 2</title>
</head>

<body>
    <div class="container-fluid">
        <div class="card" style="margin: 2rem 0rem;">
            <div class="card-header">
                Venturo - Laporan penjualan tahunan per menu
            </div>
            <div class="card-body">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                <select id="my-select" class="form-control" name="tahun">
                                    <option value="null" <?php echo ($_GET['tahun'] === 'null') ? 'selected' : ''; ?>>Pilih Tahun</option>
                                    <option value="2021" <?php echo ($_GET['tahun'] === '2021') ? 'selected' : ''; ?>>2021</option>
                                    <option value="2022" <?php echo ($_GET['tahun'] === '2022') ? 'selected' : ''; ?>>2022</option>
                                </select>
                            </div>
                            
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">
                                Tampilkan
                            </button>
                            <a href="http://tes-web.landa.id/intermediate/menu" target="_blank" rel="Array Menu" class="btn btn-secondary">
                                Json Menu
                            </a>
                            <a href="http://tes-web.landa.id/intermediate/transaksi?tahun=2021" target="_blank" rel="Array Transaksi" class="btn btn-secondary">
                                Json Transaksi
                            </a>
                        </div>
                    </div>
                </form>
                <?php

                if (isset($menu) && $_GET['tahun'] !== 'null') {
                ?>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" style="margin: 0;">
                            <thead>
                                <tr class="table-dark">
                                    <th rowspan="2" style="text-align:center;vertical-align: middle;width: 250px;">Menu</th>
                                    <th colspan="12" style="text-align: center;">Periode Pada 2021
                                    </th>
                                    <th rowspan="2" style="text-align:center;vertical-align: middle;width:75px">Total</th>
                                </tr>
                                <tr class="table-dark">
                                    <th style="text-align: center;width: 75px;">Jan</th>
                                    <th style="text-align: center;width: 75px;">Feb</th>
                                    <th style="text-align: center;width: 75px;">Mar</th>
                                    <th style="text-align: center;width: 75px;">Apr</th>
                                    <th style="text-align: center;width: 75px;">Mei</th>
                                    <th style="text-align: center;width: 75px;">Jun</th>
                                    <th style="text-align: center;width: 75px;">Jul</th>
                                    <th style="text-align: center;width: 75px;">Ags</th>
                                    <th style="text-align: center;width: 75px;">Sep</th>
                                    <th style="text-align: center;width: 75px;">Okt</th>
                                    <th style="text-align: center;width: 75px;">Nov</th>
                                    <th style="text-align: center;width: 75px;">Des</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $kategori = "";
                                $totalMonthly = array_fill(1, 12, 0); 
                                $totalRow = array(); 

                                foreach ($menu as $test) {
                                    if ($kategori != $test->kategori) {
                                        echo "<tr><td class='table-secondary' colspan='14' style='font-weight: bold; text-align: left; padding-left: 10px;'>" . ($test->kategori) . "</td></tr>";

                                        $kategori = $test->kategori;
                                    }
                                    echo "<tr><td>" . $test->menu . "</td>";

                                    $totalRow[$test->menu] = 0; 

                                    foreach (range(1, 12) as $month) {
                                        $total = 0;

                                        foreach ($transaksi as $trans) {
                                            $transDate = new DateTime($trans->tanggal);
                                            $transMonth = $transDate->format('n');

                                            if ($test->menu == $trans->menu && $month == $transMonth) {
                                                $total += $trans->total;
                                                $totalMonthly[$month] += $trans->total; 
                                                $totalRow[$test->menu] += $trans->total; 
                                            }
                                        }

                                        echo "<td style='text-align: center;width: 75px;'>" . ($total > 0 ? number_format($total) : '') . "</td>";
                                    }

                                    echo "<td style='text-align:center;vertical-align: middle;width:75px;font-weight: bold;'>" . ($totalRow[$test->menu] > 0 ? number_format($totalRow[$test->menu]) : '') . "</td></tr>";
                                }
                                ?>
                                <tr class="table-dark">
                                    <td class="font-weight-bold">Total</td>
                                    <?php
                                    foreach (range(1, 12) as $month) {
                                        echo "<td style='text-align: center;width: 75px;'>" . ($totalMonthly[$month] > 0 ? number_format($totalMonthly[$month]) : '') . "</td>";
                                    }
                                    ?>
                                  
                                    <?php
                                    $grandTotal = array_sum($totalMonthly);
                                    echo "<td style='text-align:center;vertical-align: middle;width:75px;font-weight: bold;'>" . ($grandTotal > 0 ?  number_format($grandTotal) : '') . "</td>";
                                    ?>
                                    
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary">
                                    
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            <?php if (isset($menu)) { ?>

                <div class="row m-3">
                    {{-- <div class="col-6"><h4>Isi Json Menu</h4><pre style="height: 400px; background:#eaeaea;"><?php var_dump($menu) ?></pre></div>
                    <div class="col-6"><h4>Isi Json Transaksi</h4><pre style="height: 400px; background:#eaeaea;"><?php var_dump($transaksi) ?></pre></div> --}}
                </div>
            <?php } ?>
        <?php } ?>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


</body>

</html>
