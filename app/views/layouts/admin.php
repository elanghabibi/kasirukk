<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?? "" ?> | Admin</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/output.css">
    <!-- Basic Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <!-- Filled Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/filled/boxicons-filled.min.css" rel="stylesheet">
    <!-- Brand Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/brands/boxicons-brands.min.css" rel="stylesheet">
  </head>
  <body class="flex w-full h-screen overflow-hidden bg-white">
    <!-- Side Bar -->
    <section class="w-2/10 p-4">
      <aside
        class="w-full h-full bg-gray-50 rounded-2xl border-2 border-gray-200 p-2 flex flex-col justify-between"
      >
        <div>
          <!-- Profile Toko -->
          <div class="flex gap-2 items-center p-4">
            <div
              class="w-10 h-10 rounded-xl bg-gray-300 flex items-center justify-center text-xl"
            >
              T
            </div>
            <div class="flex flex-col -space-y-1">
              <h2 class="font-bold">RM. Maju Jaya</h2>
              <p class="text-xs">Sistem Kasir</p>
            </div>
          </div>

          <hr class="my-6 mx-4 text-gray-300" />

          <nav>
            <ul class="flex flex-col gap-4">
              <li
                class="w-full border-l-4 border-lime-600 py-2 pl-6 rounded-xl bg-lime-50 flex items-center"
              >
                <a
                  href=""
                  class="flex items-center text-lime-600 gap-3 font-medium"
                  ><i class="bx bx-home-alt text-xl"></i> Beranda</a
                >
              </li>
              <li class="w-full pl-4 flex items-center">
                <a
                  href=""
                  class="flex items-center text-gray-600 gap-3 font-medium"
                  ><i class="bx bx-user text-xl"></i> User</a
                >
              </li>
              <li class="w-full pl-4 flex items-center">
                <a
                  href=""
                  class="flex items-center text-gray-600 gap-3 font-medium"
                  ><i class="bx bx-fork text-xl"></i> Menu</a
                >
              </li>
              <li class="w-full pl-4 flex items-center">
                <a
                  href=""
                  class="flex items-center text-gray-600 gap-3 font-medium"
                  ><i class="bx bx-categories text-xl"></i> Kategori</a
                >
              </li>
              <li class="w-full pl-4 flex items-center">
                <a
                  href=""
                  class="flex items-center text-gray-600 gap-3 font-medium"
                  ><i class="bx bx-history text-xl"></i> Riwayat Transaksi</a
                >
              </li>
            </ul>
          </nav>
        </div>

        <div class="p-4 flex flex-col">
          <!-- Profile User -->
          <div class="flex gap-2 items-center">
            <div class="flex items-center justify-center w-10 aspect-square rounded-full bg-lime-600 text-white font-bold overflow-hidden">
              <span><?= Helpers::initials($_SESSION['user']['nama'], 2) ?></span>
            </div>
            <div>
              <h2 class="font-bold"><?= Helpers::firstNameInitial($_SESSION['user']['nama']) ?></h2>
              <p class="text-xs text-gray-600"><?= $_SESSION['user']['email'] ?></p>
            </div>
          </div>
          <!-- End Profile User -->

          <hr class="my-4 text-gray-300" />

          
            <div class="modal">
                <button class="modal-btn font-medium flex text-gray-600 items-center gap-4 cursor-pointer"><i class="bx bx-arrow-out-right-square-half text-xl"></i> Keluar</button>

                <div
                class="modal-panel opacity-0 pointer-events-none absolute top-0 left-0 w-full h-screen flex justify-center items-center bg-gray-950/10"
                style="z-index: 200"
                >
                <div
                    class="bg-gray-50 p-6 text-center rounded-xl shadow-lg shadow-gray-600/5 w-80 max-md:w-7/10 flex flex-col gap-6"
                >
                    <i class="bx bx-alert-triangle text-red-500 text-5xl"></i>
                    <h2 class="text-2xl font-bold leading-6">
                    Keluar
                    </h2>
                    <p class="text-sm text-gray-600">
                    Apakah anda yakin ingin keluar?
                    </p>

                    <div class="w-full grid grid-cols-2 gap-4">
                    <button
                        class="modal-close w-full bg-gray-200 text-gray-950 font-bold py-2 text-sm rounded-lg cursor-pointer"
                    >
                        Batal
                    </button>
                    <form action="<?= BASEURL . "/logout" ?>" method="POST" class="form">
                        <button
                        type="submit"
                        class="form-btn w-full bg-red-500 text-gray-50 font-bold py-2 text-sm rounded-lg cursor-pointer"
                        >
                        <span class="form-btntext"
                            >Ya, keluar</span
                        >
                        </button>
                    </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
      </aside>
    </section>

    <!-- Main/Right Section -->
    <section class="flex-1 min-w-0 flex flex-col">
      <header class="flex items-center justify-between w-full rounded-bl-2xl bg-gray-50 border-2 border-gray-200 p-4">
        <div class="text-lg font-medium">
            <h1><?= $title ?? "" ?></h1>
        </div>

        <div class="flex gap-2">
          <div class="text-right">
            <h2 class="font-bold"><?= Helpers::firstNameInitial($_SESSION['user']['nama']) ?></h2>
            <p class="text-xs text-gray-600"><?= ucfirst($_SESSION['user']['role']) ?></p>
          </div>
          <div class="flex items-center justify-center w-10 aspect-square rounded-full bg-lime-600 text-white font-bold overflow-hidden">
            <span><?= Helpers::initials($_SESSION['user']['nama'], 2) ?></span>
        </div>
        </div>
      </header>

      <main class="flex-1 min-h-0 overflow-y-auto flex flex-col gap-4 p-4">
        <?php require_once $view ?>
      </main>
    </section>

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
  <script src="<?= BASEURL . "/js/script.js" ?>"></script>

  <?php if ($flash): ?>

    <script>
        ToastManager.show(
            <?= json_encode($flash) ?>
        );
    </script>

    <?php endif; ?>
</html>

<!-- SELESAIIN HALAMAN ADMIN WOIII -->