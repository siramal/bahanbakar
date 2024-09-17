<!DOCTYPE html>
<html>
<head>
    <title>Bukti Transaksi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php
    session_start();
    
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

    if (isset($_SESSION['pembelian'])) {
        $pembelian = $_SESSION['pembelian'];
        $buktiTransaksi = $pembelian->buktiTransaksi();
        
        // Clear the session after displaying
        unset($_SESSION['pembelian']);
    } else {
        $buktiTransaksi = "<p>Data tidak ditemukan.</p>";
    }
    ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h3 class="text-center">Bukti Transaksi</h3>
                <div class="mb-4">
                    <?php echo $buktiTransaksi; ?>
                </div>
                
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-primary">Kembali ke Form</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
