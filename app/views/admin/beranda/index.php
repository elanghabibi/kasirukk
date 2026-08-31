
<h1 class="text-2xl font-semibold">Halo, <?= $_SESSION['user']['nama'] ?></h1>

<div class="w-full grid grid-cols-3 gap-4 h-32">
    <div class="w-full bg-white border-2 border-gray-200 rounded-xl text-gray-600 p-6 flex flex-col gap-4 shadow-md">
        <h2 class="font-medium">Transaksi Bulanan</h2>
        <p class="text-3xl font-semibold"><?= $totalTransaksi ?></p>
    </div>
    <div class="w-full bg-white border-2 border-gray-200 rounded-xl text-gray-600 p-6 flex flex-col gap-4 shadow-md">
        <h2 class="font-medium">Pendapatan Bulanan</h2>
        <p class="text-3xl font-semibold"><?= Helpers::rupiah($totalPendapatan) ?></p>
    </div>
    <div class="w-full bg-white border-2 border-gray-200 rounded-xl text-gray-600 p-6 flex flex-col gap-4 shadow-md">
        <h2 class="font-medium">Total Menu</h2>
        <p class="text-3xl font-semibold"><?= $totalMenu ?></p>
    </div>
</div>

<div class="mt-4 flex flex-col gap-2">
    <h2>Jelajahi Aplikasi</h2>
    <div class="flex gap-4">
        <a href="" class="w-28 aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
            <i class="bx bx-user text-lime-600 text-5xl"></i>
            <p class="text-sm font-medium">User</p>
        </a>
        <a href="" class="w-28 aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
            <i class="bx bx-fork text-lime-600 text-5xl"></i>
            <p class="text-sm font-medium">Menu</p>
        </a>
        <a href="" class="w-28 aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
            <i class="bx bx-categories text-lime-600 text-5xl"></i>
            <p class="text-sm font-medium">Kategori</p>
        </a>
        <a href="" class="w-28 aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
            <i class="bx bx-history text-lime-600 text-5xl"></i>
            <p class="text-sm font-medium w-full truncate">Riwayat Transaksi</p>
        </a>
    </div>
</div>
