<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= ucfirst($transaksi['nama_pelanggan']) . " Struk RM Maju Jaya" ?></title>

    <style>
        /* ==========================================
           STYLING TAMPILAN LAYAR (DESKTOP / MOBILE)
           ========================================== */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 0;
            background: #e5e7eb;
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Container Utama Struk Thermal */
        .struk {
            width: 80mm;
            background: #ffffff;
            padding: 24px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            font-size: 11px;
            line-height: 1.35;
        }

        /* Header Struk */
        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .header h2 {
            margin: 0 0 8px 0;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
            text-transform: uppercase;
        }

        /* Garis Pemisah Karakter Thermal */
        .divider-double,
        .divider-single {
            font-size: 10px;
            letter-spacing: -0.5px;
            overflow: hidden;
            white-space: nowrap;
            user-select: none;
            margin: 4px 0;
        }

        /* Seksi Informasi Transaksi */
        .info {
            margin: 6px 0;
        }

        .info div {
            margin: 2px 0;
        }

        /* Item Belanja */
        .item {
            margin: 6px 0;
        }

        .item-name {
            font-weight: bold;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            padding-left: 12px;
        }

        /* Keterangan Tambahan (jika ada) */
        .item-keterangan {
            font-style: italic;
            margin-top: 2px;
            font-size: 10px;
        }

        /* Ringkasan Pembayaran & Total */
        .summary-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin: 6px 0;
        }

        .summary-left {
            width: 35%;
        }

        .summary-right {
            width: 65%;
        }

        .summary-right table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-right td {
            padding: 1px 0;
        }

        .text-right {
            text-align: right;
        }

        /* Footer Struk */
        .footer {
            text-align: center;
            margin-top: 6px;
            font-size: 10px;
        }

        .footer p {
            margin: 2px 0;
        }

        /* Tombol Aksi Layar */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            padding: 10px 28px;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.2s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-primary {
            background-color: #d9f99d; /* Hijau Muda pastel */
            color: #365314;
            border: 1px solid #bef264;
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        /* ==========================================
           ATURAN PENCETAKAN (@media print)
           ========================================== */
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            html, body {
                background: #ffffff !important;
                height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            body * {
                visibility: hidden;
            }

            .struk, .struk * {
                visibility: visible;
            }

            .struk {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm !important;
                margin: 0 !important;
                padding: 4mm !important;
                box-shadow: none !important;
            }

            .action-buttons {
                display: none !important;
            }
        }
    </style>
</head>

<body>
        <?php require_once $view ?>
</body>
</html>