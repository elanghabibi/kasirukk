<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?? "" ?> | Kasir</title>
  <link rel="stylesheet" href="<?= BASEURL ?>/css/output.css">
  <!-- Basic Icons -->
  <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
  <!-- Filled Icons -->
  <link href="https://cdn.boxicons.com/3.0.8/fonts/filled/boxicons-filled.min.css" rel="stylesheet">
  <!-- Brand Icons -->
  <link href="https://cdn.boxicons.com/3.0.8/fonts/brands/boxicons-brands.min.css" rel="stylesheet">
</head>

<body class="flex w-full h-screen overflow-hidden bg-white">
  <!-- Main/Right Section -->
  <section class="w-full flex flex-col">
    <header class="flex items-center justify-between w-full bg-gray-50 border-2 border-gray-200 p-4">
      <div class="flex items-center text-lg font-medium">
        <?php if (isset($back) && parse_url(Helpers::currentUrl(), PHP_URL_PATH) != "/kasir"): ?>
          <a href="<?= Helpers::url($back) ?>" class="flex items-center justify-center cursor-pointer"><i class="bx bx-arrow-left-stroke text-3xl"></i></a>
        <?php endif; ?>
        <h1><?= $title ?? "" ?></h1>
      </div>

      <a href="<?= Helpers::url('/profil?redirect=' . str_replace("/", "_", ltrim(Helpers::currentUrl(), '/'))) ?>" class="flex gap-2">
        <div class="text-right">
          <h2 class="font-bold"><?= Helpers::firstNameInitial(Auth::user()['nama']) ?></h2>
          <p class="text-xs text-gray-600"><?= ucfirst(Auth::user()['role']) ?></p>
        </div>
        <div class="flex items-center justify-center w-10 aspect-square rounded-full overflow-hidden">
          <img class="w-full h-full object-cover" src="<?= Helpers::url(Auth::user()['foto_profil']) ?>" alt="">
        </div>
      </a>
    </header>

    <main class="flex-1 min-h-0 overflow-y-auto flex flex-col gap-4 w-full max-md:pb-20">
      <?php require_once $view ?>
    </main>
  </section>

  <?php $flash = Flasher::getFlash() ?>
  <?php if ($flash): ?>
    <div
      id="toast-container"
      class="fixed top-6 right-6 z-50 flex w-full max-w-sm flex-col gap-3"></div>
  <?php endif ?>
  <?php
  unset($_SESSION['old']);
  unset($_SESSION['errors']);

  ?>
</body>
<script src="<?= BASEURL . "/js/script.js" ?>"></script>

<?php if ($flash): ?>

  <script>
    ToastManager.show(
      <?= json_encode($flash) ?>
    );
  </script>

<?php endif; ?>

</html>