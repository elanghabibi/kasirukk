<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?? "" ?> | Struk</title>
    <!-- Boxicons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">

    <style>
        /* Styling Tampilan Layar Web */
        body {
            margin: 0;
            background: #f3f4f6;
            font-family: 'Courier New', Courier, monospace, sans-serif;
            color: #000;
        }

        .struk {
            width: 58mm;
            margin: 20px auto;
            padding: 8px;
            background: white;
            box-sizing: border-box;
            font-size: 11px;
            line-height: 1.3;
        }

        .header, .footer {
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
        }

        .header p, .footer p {
            margin: 2px 0;
            font-size: 10px;
        }

        /* Garis Putus-putus Thermal */
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .info > div,
        .summary > div {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .item {
            margin: 5px 0;
        }

        .item-name {
            font-weight: bold;
            word-break: break-word;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .print-button {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 20px auto;
            padding: 8px 16px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
        }

        /* ==========================================
           ATURAN CETAK (@media print)
           ========================================== */
        @media print {
            @page {
                size: 58mm auto; /* Lebar 58mm, tinggi otomatis */
                margin: 0;      /* Hilangkan header/footer browser */
            }

            html, body {
                background: #fff !important;
                height: auto !important;
                overflow: visible !important;
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
                width: 58mm !important;
                margin: 0 !important;
                padding: 4mm !important;
                box-shadow: none !important;
            }

            .print-button {
                display: none !important;
            }
        }
    </style>
</head>
<!-- NANTI FIX STRUK INI WOIII -->
<!-- Menghapus h-screen dan overflow-hidden pada body saat diprint -->
<body class="flex flex-col items-center min-h-screen bg-gray-100">

    <?php require_once $view ?>
</body>
</html>
