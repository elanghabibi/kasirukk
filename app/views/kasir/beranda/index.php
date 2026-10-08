<div class="p-4 flex flex-col gap-4">
    <h1 class="text-2xl font-semibold">Halo, <?= Auth::user()['nama'] ?></h1>
    
    <div class="w-fit flex gap-4 h-32">
        <div class="w-80 bg-white border-2 border-gray-200 rounded-xl text-gray-600 p-6 flex flex-col gap-4 shadow-md">
            <h2 class="font-medium">Transaksi Bulanan (<?= Helpers::currentMonthNameId() ?>)</h2>
            <p class="text-3xl font-semibold"><?= $totalTransaksi ?></p>
        </div>
        <div class="w-80 bg-white border-2 border-gray-200 rounded-xl text-gray-600 p-6 flex flex-col gap-4 shadow-md">
            <h2 class="font-medium">Pendapatan Bulanan (<?= Helpers::currentMonthNameId() ?>)</h2>
            <p class="text-3xl font-semibold"><?= Helpers::rupiah($totalPendapatan) ?></p>
        </div>
        <div class="w-80 bg-white border-2 border-gray-200 rounded-xl text-gray-600 p-6 flex flex-col gap-4 shadow-md">
            <h2 class="font-medium">Total Menu</h2>
            <p class="text-3xl font-semibold"><?= $totalMenu ?></p>
        </div>
    </div>
    
    
    <div class="mt-4 flex flex-col gap-2">
        <h2>Jelajahi Aplikasi</h2>
        <div class="flex gap-4">
            <a href="<?= Helpers::url("/kasir/transaksi") ?>" class="w-28 aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
                <i class="bx bx-list-ul-square text-lime-600 text-5xl"></i>
                <p class="text-sm font-medium">Transaksi</p>
            </a>
            <a href="<?= Helpers::url("/kasir/riwayat-transaksi") ?>" class="w-28 aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
                <i class="bx bx-history text-lime-600 text-5xl"></i>
                <p class="text-sm font-medium w-full truncate">Riwayat Transaksi</p>
            </a>
        </div>
    </div>
    
    <div class="mt-4 flex flex-col gap-2">
        <h2>Keluar Akun</h2>
        <div class="modal">
            <button class="modal-btn cursor-pointer w-28 aspect-square rounded-xl border border-gray-200 shadow-md p-4 flex flex-col gap-2 items-center justify-center">
                <i class="bx bx-arrow-out-right-square-half text-red-600 text-5xl"></i>
                <p class="text-sm font-medium w-full truncate">Keluar</p>
            </button>
            <div
                class="modal-panel opacity-0 pointer-events-none absolute top-0 left-0 w-full h-screen flex justify-center items-center bg-gray-950/10"
                style="z-index: 200">
                <div
                    class="bg-gray-50 p-6 text-center rounded-xl shadow-lg shadow-gray-600/5 w-80 max-md:w-7/10 flex flex-col gap-6">
                    <i class="bx bx-alert-triangle text-red-500 text-5xl"></i>
                    <h2 class="text-2xl font-bold leading-6">
                        Keluar
                    </h2>
                    <p class="text-sm text-gray-600">
                        Apakah anda yakin ingin keluar?
                    </p>
    
                    <div class="w-full grid grid-cols-2 gap-4">
                        <button
                            class="modal-close w-full bg-gray-200 text-gray-950 font-bold py-2 text-sm rounded-lg cursor-pointer">
                            Batal
                        </button>
                        <form action="<?= BASEURL . "/logout" ?>" method="POST" class="form">
                            <button
                                type="submit"
                                class="form-btn w-full bg-red-500 text-gray-50 font-bold py-2 text-sm rounded-lg cursor-pointer">
                                <span class="form-btntext">Ya, keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>