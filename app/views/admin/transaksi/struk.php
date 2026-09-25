
    <?php $transaksi = $data['transaksi']; ?>

    <div class="struk">

        <div class="header">
            <h2>505 COFFEE</h2>
            <p>Jl. Contoh No. 123</p>
            <p>Pemalang</p>
        </div>

        <div class="divider"></div>

        <div class="info">
            <div>
                <span>Kode</span>
                <span><?= $transaksi['kode_transaksi'] ?></span>
            </div>
            <div>
                <span>Tanggal</span>
                <span>
                    <?= date('d/m/y H:i', strtotime($transaksi['created_at'])) ?>
                </span>
            </div>
            <div>
                <span>Kasir</span>
                <span><?= $transaksi['kasir'] ?></span>
            </div>
            <div>
                <span>Pelanggan</span>
                <span><?= $transaksi['nama_pelanggan'] ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Daftar Item -->
        <?php foreach ($transaksi['details'] as $item): ?>
            <div class="item">
                <div class="item-name"><?= $item['nama_menu'] ?></div>
                <div class="item-detail">
                    <span>
                        <?= $item['qty'] ?> x Rp<?= number_format($item['harga_menu'], 0, ',', '.') ?>
                    </span>
                    <span>
                        Rp<?= number_format($item['subtotal'], 0, ',', '.') ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="divider"></div>

        <div class="summary">
            <div>
                <span>Total Item</span>
                <span><?= $transaksi['total_qty'] ?></span>
            </div>
            <div>
                <strong>Total</strong>
                <strong>
                    Rp<?= number_format($transaksi['total'], 0, ',', '.') ?>
                </strong>
            </div>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>Terima kasih!</p>
            <p>Selamat menikmati</p>
        </div>

    </div>

    <button onclick="window.print()" class="print-button">
        <i class="bx bx-printer"></i>
        Cetak Struk
    </button>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>