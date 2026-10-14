
<h1 class="text-2xl font-semibold">Halo, <?= Auth::user()['nama'] ?></h1>

<div class="w-full grid grid-cols-3 max-md:grid-cols-1 gap-4 h-32 max-md:h-fit">
    <div class="w-full bg-white border-2 border-gray-200 rounded-xl text-gray-600 p-6 flex flex-col gap-4 shadow-md">
        <h2 class="font-medium">Transaksi Bulanan (<?= Helpers::currentMonthNameId() ?>)</h2>
        <p class="text-3xl font-semibold"><?= $totalTransaksi ?></p>
    </div>
    <div class="w-full bg-white border-2 border-gray-200 rounded-xl text-gray-600 p-6 flex flex-col gap-4 shadow-md">
        <h2 class="font-medium">Pendapatan Bulanan (<?= Helpers::currentMonthNameId() ?>)</h2>
        <p class="text-3xl font-semibold"><?= Helpers::rupiah($totalPendapatan) ?></p>
    </div>
    <div class="w-full bg-white border-2 border-gray-200 rounded-xl text-gray-600 p-6 flex flex-col gap-4 shadow-md">
        <h2 class="font-medium">Total Menu</h2>
        <p class="text-3xl font-semibold"><?= $totalMenu ?></p>
    </div>
</div>


<div class="mt-4 flex flex-col gap-2">
    <h2>Jelajahi Aplikasi</h2>
    <div class="grid grid-cols-8 max-md:grid-cols-3 gap-4">
        <a href="<?= Helpers::url("/admin/user") ?>" class="w-full aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
            <i class="bx bx-user text-lime-600 text-5xl"></i>
            <p class="text-sm font-medium">User</p>
        </a>
        <a href="<?= Helpers::url("/admin/menu") ?>" class="w-full aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
            <i class="bx bx-fork text-lime-600 text-5xl"></i>
            <p class="text-sm font-medium">Menu</p>
        </a>
        <a href="<?= Helpers::url("/admin/kategori") ?>" class="w-full aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
            <i class="bx bx-categories text-lime-600 text-5xl"></i>
            <p class="text-sm font-medium">Kategori</p>
        </a>
        <a href="<?= Helpers::url("/admin/riwayat-transaksi") ?>" class="w-full aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
            <i class="bx bx-history text-lime-600 text-5xl"></i>
            <p class="text-sm font-medium w-full truncate">Riwayat Transaksi</p>
        </a>
    </div>
</div>
