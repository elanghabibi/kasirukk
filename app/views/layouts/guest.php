<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Guest" ?></title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/output.css">
    <!-- Basic Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <!-- Filled Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/filled/boxicons-filled.min.css" rel="stylesheet">
    <!-- Brand Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/brands/boxicons-brands.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center w-full h-screen bg-gray-100">
    <?php require_once $view ?>

    <?php $flash = Flasher::getFlash() ?>
    <?php if ($flash): ?>
        <div
            id="toast-container"
            class="fixed top-6 right-6 z-50 flex w-full max-w-sm flex-col gap-3"
        ></div>
    <?php endif ?>
    <?php
    unset($_SESSION['old']);
    unset($_SESSION['errors']);

    ?>
</body>
<script src="<?= BASEURL ?>/js/script.js"></script>
<?php if ($flash): ?>

<script>
    ToastManager.show(
        <?= json_encode($flash) ?>
    );
</script>

<?php endif; ?>

</html>

<!-- BENERIN HALAMAN ADMIN DULU WOIII -->