<!DOCTYPE html>
<html>
<head>
    <title>Form Pembelian Bahan Bakar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php

    class Shell {
        public $jenis;
        public $harga;
        public $ppn;
    
        public function __construct($jenis, $harga) {
            $this->jenis = $jenis;
            $this->harga = $harga;
            $this->ppn = 0.10; // PPN 10%
        }
    
        public function totalHarga($jumlah) {
            $hargaTotal = $this->harga * $jumlah;
            $ppn = $hargaTotal * $this->ppn;
            $hargaTotal += $ppn;
            return $hargaTotal;
        }
    }
    
    class Beli extends Shell {
        public $jumlah;
    
        public function __construct($jenis, $harga, $jumlah) {
            parent::__construct($jenis, $harga);
            $this->jumlah = $jumlah;
        }
    
        public function buktiTransaksi() {
            $total = $this->totalHarga($this->jumlah);
            return "Anda membeli bahan bakar minyak tipe : {$this->jenis},<br>Dengan jumlah : {$this->jumlah} liter: , Total yang harus anda bayar: Rp. " . number_format($total, 2);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        // Ambil data dari formulir
        $liter = $_POST["liter"];
        $jenis = $_POST["jenis"];

        // Validasi input
        if (!empty($jenis) && $liter > 0) {
            // Hitung harga berdasarkan liter
            $harga = 0;
            switch ($jenis) {
                case "Shell Super":
                    $harga = 15420;
                    break;
                case "Shell V-Power":
                    $harga = 16130;
                    break;
                case "Shell V-Power Diesel":
                    $harga = 18310;
                    break;
                case "Shell V-Power Nitro":
                    $harga = 16510;
                    break;
            }
            $pembelian = new Beli($jenis, $harga, $liter);

        }elseif(isset($_POST['hapus'])){
            $jenis=null;
            $liter=null;
        }

    }
    ?>
   
               
        <body class=" bg-dark px-5">
        <div class="container">
            <div class="row justify-content-center align-items-center" style="height: 600px;">
                <div class="col-10">
                    <div class="card bg-info">
                        <div class="card-body">
                            <div class="row my-3">
                                <div class="col-12 text-center ">
                                <p>
                                    <?php 
                                      if (isset($_POST["submit"])){
                                        $pembelian = new Beli($jenis, $harga, $liter);
                                        echo "<h3>Bukti Transaksi:</h3>";
                                        echo $pembelian->buktiTransaksi();
                                      }
                                    ?>
                                </p>
                               
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="liter">Jumlah Liter:</label>
        <input type="number" id="liter" name="liter" min="1">
        <br><br>
        <label for="jenis">Jenis Bahan Bakar:</label>
        <select id="jenis" name="jenis">
            <option value="Shell Super">Shell Super</option>
            <option value="Shell V-Power">Shell V-Power</option>
            <option value="Shell V-Power Diesel">Shell V-Power Diesel</option>
            <option value="Shell V-Power Nitro">Shell V-Power Nitro</option>
        </select>
        <br><br>
        <div class="row justify-content-center pt-3">
                                            <div class="col-lg-2">
                                                <button class="btn btn-primary" name="submit">kirim</button>
                                            </div>
                                            <div class="col-lg-2">
                                                <button class="btn btn-danger" name="reset">hapus</button>
                                            </div>
                                        </div>

    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</body>
</html>
