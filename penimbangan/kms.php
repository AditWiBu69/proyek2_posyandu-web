<!DOCTYPE html>
<html lang="en">

<head>
    <title>Grafik KMS</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .navbar-logo img {
            width: 50px;
            margin-right: 10px;
        }

        .navbar a {
            text-decoration: none;
        }

        .title {
            text-align: center;
            margin-top: 20px;
            font-size: 26px;
            margin-bottom: 20px;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1;
        }

        canvas {
            max-width: calc(60vw);
            max-height: calc(50vh);
            width: 100%;
            height: auto;
            margin-top: 60px;
        }

        h1 {
            text-align: center;
        }

        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .image-container img {
            width: 300px;
            /* Lebih besar dari sebelumnya */
            margin: 10px;
        }

        .keterangan {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }

        /* Menambahkan gaya untuk mengaktifkan scroll */
        .content {
            overflow-y: auto;
            max-height: calc(100vh - 220px);
        }
    </style>
</head>

<body>
    <div class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="navbar-logo">
            <img src="../img/Favicon.png" alt="Posyandu Logo">
        </div>
        <a class="navbar-brand" href="#">Posyandu</a>
        <ul class="navbar-menu navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="javascript:history.back()">Kembali ke Data Penimbangan</a>
            </li>
        </ul>
    </div>

    <div class="container mt-4">
        <h2 class="title">Grafik KMS Anak</h2>
    </div>

    <canvas id="myChart"></canvas>

    <!-- <div class="image-container">
        <img src="" alt="Gambar 1">
    </div> -->

    <!-- <div class="keterangan">
        <div class="content">
            <div style="clear: both;"></div>
            Jika data berat badan terpaut jauh, harap segera konsultasikan untuk
            mendapatkan saran dari ahli gizi.
            Isi keterangan lainnya -->
    </div>
    </div>

    <?php
    // Menerima parameter id_anak dari URL
    $id_anak = $_GET["id_anak"];
    $conn = mysqli_connect("localhost", "root", "", "dbpy");

    // Query data dari database berdasarkan id_anak
    $query = "SELECT umur, berat_badan FROM t_penimbangan WHERE id_anak = $id_anak";
    $result = mysqli_query($conn, $query);

    // Olah data menjadi format yang sesuai untuk Chart.js
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'umur' => $row['umur'],
            'berat_badan' => $row['berat_badan'],
        ];
    }

    // Menghitung kisaran berat badan normal (contoh statis)
    $normalRanges = [
        ['umur' => 0, 'lower' => 3.2, 'upper' => 3.3],
        ['umur' => 1, 'lower' => 4.2, 'upper' => 4.5],
        ['umur' => 2, 'lower' => 5.1, 'upper' => 5.6],
        ['umur' => 3, 'lower' => 5.8, 'upper' => 6.4],
        ['umur' => 4, 'lower' => 6.4, 'upper' => 7],
        ['umur' => 5, 'lower' => 6.9, 'upper' => 7.5],
        ['umur' => 6, 'lower' => 7.3, 'upper' => 7.9],
        ['umur' => 7, 'lower' => 7.6, 'upper' => 8.3],
        ['umur' => 8, 'lower' => 7.9, 'upper' => 8.6],
        ['umur' => 9, 'lower' => 8.2, 'upper' => 8.9],
        ['umur' => 10, 'lower' => 8.5, 'upper' => 9.2],
        ['umur' => 11, 'lower' => 8.7, 'upper' => 9.4],
        ['umur' => 12, 'lower' => 8.9, 'upper' => 9.6],
        ['umur' => 13, 'lower' => 9.2, 'upper' => 9.9],
        ['umur' => 14, 'lower' => 9.4, 'upper' => 10.1],
        ['umur' => 15, 'lower' => 9.6, 'upper' => 10.3],
        ['umur' => 16, 'lower' => 9.8, 'upper' => 10.5],
        ['umur' => 17, 'lower' => 10, 'upper' => 10.7],
        ['umur' => 18, 'lower' => 10.2, 'upper' => 10.9],
        ['umur' => 19, 'lower' => 10.4, 'upper' => 11.1],
        ['umur' => 20, 'lower' => 10.6, 'upper' => 11.3],
        ['umur' => 21, 'lower' => 10.9, 'upper' => 11.5],
        ['umur' => 22, 'lower' => 11.1, 'upper' => 11.8],
        ['umur' => 23, 'lower' => 11.3, 'upper' => 12],
        ['umur' => 24, 'lower' => 11.5, 'upper' => 12.2],
        // Tambahkan kisaran berat badan normal untuk rentang usia lainnya
    ];
    ?>
    <script>
        var data = <?php echo json_encode($data); ?>;
        var labels = data.map(item => item.umur);
        var values = data.map(item => item.berat_badan);

        // Menghitung kisaran berat badan normal
        var normalLower = labels.map(umur => {
            var range = <?php echo json_encode($normalRanges); ?>;
            var match = range.find(item => item.umur == umur);
            return match ? match.lower : null;
        });
        var normalUpper = labels.map(umur => {
            var range = <?php echo json_encode($normalRanges); ?>;
            var match = range.find(item => item.umur == umur);
            return match ? match.upper : null;
        });

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Berat Bayi',
                        data: values,
                        fill: false,
                        borderColor: 'DarkCyan',
                        borderWidth: 1
                    },
                    {
                        label: 'BB Normal Perempuan',
                        data: normalLower,
                        fill: false,
                        borderColor: 'rgb(255, 105, 180)',
                        borderWidth: 1,
                        borderDash: [5, 5]
                    },
                    {
                        label: 'BB Normal Laki-laki',
                        data: normalUpper,
                        fill: false,
                        borderColor: 'DeepSkyBlue',
                        borderWidth: 1,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
                        title: {
                            display: true,
                            text: 'Usia (bulan)'
                        },
                        beginAtZero: true, // Mulai dari 0
                        stepSize: 1,
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Berat (kg)'
                        }
                    }
                }
            }

        });
    </script>
</body>

</html>