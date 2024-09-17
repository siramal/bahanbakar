<!DOCTYPE html>
<html>
<head>
    <title>Form Pembelian Bahan Bakar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

    <?php
    // Define the Shell and Beli classes here
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
            $hargaDasar = $this->harga * $jumlah;
            $ppn = $hargaDasar * $this->ppn;
            $hargaTotal = $hargaDasar + $ppn;
            return ['hargaDasar' => $hargaDasar, 'ppn' => $ppn, 'hargaTotal' => $hargaTotal];
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
            return "
                Jenis Bahan Bakar : {$this->jenis}<br>
                Jumlah Liter : {$this->jumlah} liter<br>
                Harga per Liter: Rp. " . number_format($this->harga, 2) . "<br>
                Harga Dasar: Rp. " . number_format($total['hargaDasar'], 2) . "<br>
                PPN (10%): Rp. " . number_format($total['ppn'], 2) . "<br>
                Total Harga (termasuk PPN): Rp. " . number_format($total['hargaTotal'], 2);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        $liter = $_POST["liter"];
        $jenis = $_POST["jenis"];

        if (!empty($jenis) && $liter > 0) {
            $harga = 0;
            switch ($jenis) {
                case "Shell Super":
                    $harga = 15000;
                    break;
                case "Shell V-Power":
                    $harga = 16000;
                    break;
                case "Shell V-Power Diesel":
                    $harga = 18000;
                    break;
                case "Shell V-Power Nitro":
                    $harga = 15500;
                    break;
            }
            $pembelian = new Beli($jenis, $harga, $liter);
            
            // Store data in session or pass via query string
            session_start();
            $_SESSION['pembelian'] = $pembelian;
            header("Location: bukti_transaksi.php");
            exit();
        } elseif (isset($_POST['reset'])) {
            $liter = null;
            $jenis = null;
            $pembelian= null ;
        }
    }
    ?>
   
    <div class="col-6 container">
        <div class="row justify-content-center align-items-center" style="height: 600px;">
            <div class="col-12">
                <div>
                    <div class="card-body">
                        <div class="row my-3">
                            <div class="col-12 text-center">
                                <h1>Pembelian Bahan Bakar</h1>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <label for="liter">Jumlah Liter:</label>
                                    <input type="number" id="liter" name="liter" min="1" required>
                                    <br><br>
                                    <label for="jenis">Jenis Bahan Bakar:</label>
                                    <select id="jenis" name="jenis" required>
                                        <option value="Shell Super">Shell Super</option>
                                        <option value="Shell V-Power">Shell V-Power</option>
                                        <option value="Shell V-Power Diesel">Shell V-Power Diesel</option>
                                        <option value="Shell V-Power Nitro">Shell V-Power Nitro</option>
                                    </select>
                                    <br><br>
                                    <div class="row justify-content-center pt-3">
                                        <div class="col-lg-2">
                                            <button class="btn btn-primary" name="submit">Kirim</button>
                                        </div>
                                        <div class="col-lg-2">
                                            <button class="btn btn-danger" name="reset">Hapus</button>
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
