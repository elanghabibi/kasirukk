<?php $transaksi = $data['transaksi']; ?>

<!-- CONTAINER STRUK -->
<div class="struk">

    <!-- Header Store -->
    <div class="header">
        <h2>RM. MAJU JAYA</h2>
        <p>JL. PENUH KENANGAN NO.45</p>
        <p>BOJONGSEMEN, PEMALANG</p>
        <p>JAWA TENGAH, KOREA SELATAN</p>
        <p>Telepon: 081234567890</p>
    </div>

    <div class="divider-double">================================================</div>

    <!-- Info Transaksi -->
    <div class="info">
        <div>Tanggal: <?= date('d/m/Y H:i', strtotime($transaksi['created_at'])) ?></div>
        <div>Pelanggan: <?= $transaksi['nama_pelanggan'] ?></div>
        <div>Tipe Pemesanan: <?= Helpers::formatLabel($transaksi['tipe_order']) ?></div>
        <div>No Meja: <?= $transaksi['no_meja'] == '' ? "-" : $transaksi['no_meja'] ?></div>
        <div>Kasir: <?= $transaksi['kasir'] ?></div>
    </div>

    <div class="divider-double">================================================</div>

    <!-- Daftar Barang -->
    <?php foreach ($transaksi['details'] as $item): ?>
        <div class="item">
            <div class="item-name"><?= $item['nama_menu'] ?></div>
            <div class="item-detail">
                <span><?= $item['qty'] ?> x <?= number_format($item['harga_menu'], 0, ',', '.') ?></span>
                <span><?= number_format($item['subtotal'], 0, ',', '.') ?></span>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (!empty($transaksi['keterangan'])): ?>
        <div class="item-keterangan">
            "<?= $transaksi['keterangan'] ?>"
        </div>
    <?php endif; ?>

    <div class="divider-single">------------------------------------------------</div>

    <!-- Summary & Total -->
    <div class="summary-container">
        <div class="summary-left">
            Total Qty : <?= $transaksi['total_qty'] ?>
        </div>
        <div class="summary-right">
            <table>
                <tr>
                    <td class="text-right">Subtotal :</td>
                    <td class="text-right"><?= number_format($transaksi['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="text-right">Pajak (10%) :</td>
                    <td class="text-right"><?= number_format($transaksi['pajak'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="text-right">Total :</td>
                    <td class="text-right"><?= number_format($transaksi['total'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="text-right">Bayar :</td>
                    <td class="text-right"><?= number_format($transaksi['tunai'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="text-right">Kembalian :</td>
                    <td class="text-right"><?= number_format($transaksi['kembalian'], 0, ',', '.') ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="divider-single">------------------------------------------------</div>

    <!-- Footer -->
    <div class="footer">
        <p>No Transaksi : <?= $transaksi['kode_transaksi'] ?></p>
        <p>Terima Kasih Telah Berkunjung</p>
        <p>Selamat Menikmati</p>
    </div>

</div>

<!-- Tombol Action -->
<div class="action-buttons">
    <button onclick="window.print()" class="btn btn-primary">
        Cetak Ulang
    </button>
    <a href="javascript:history.back()" class="btn btn-secondary">
        Kembali
    </a>
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>