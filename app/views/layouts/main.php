<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Latihan LKS" ?></title>
</head>

<body>
    <?php require_once $view ?>

    <?php $flash = Flasher::getFlash() ?>
    <?php if ($flash): ?>
        <div>
            <p><?= $flash['message'] ?></p>
        </div>
    <?php endif ?>

    <?php
    unset($_SESSION['old']);
    unset($_SESSION['errors']);

    ?>
</body>

</html>