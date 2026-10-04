<div class="w-full flex h-screen overflow-hidden">
    <!-- Konten Kiri (Scrollable) -->
    <div class="overflow-y-auto w-3/4 flex flex-col gap-4 h-full pl-4 py-4 pr-4">
        
        <!-- Search & Reset Filter -->
        <div class="w-full flex justify-between items-center h-fit gap-4">
            <div class="w-4/5 h-fit">
                <form action="<?= Helpers::url('/kasir/transaksi') ?>" method="GET" class="relative w-full" autocomplete="off" spellcheck="false">
                    <button
                        type="submit"
                        class="flex items-center justify-center cursor-pointer absolute top-1/2 -translate-y-1/2 left-5 text-xl text-gray-600">
                        <i class="bx bx-search"></i>
                    </button>
                    <input
                        type="search"
                        name="search"
                        class="w-full h-10 border-2 border-gray-200 rounded-lg pl-12 focus:outline-lime-600"
                        placeholder="Cari transaksi..."
                        value="<?= htmlspecialchars($keyword ?? '') ?>" />
                </form>
            </div>

            <div class="flex-1">
                <a
                    href="<?= Helpers::url('/kasir/transaksi') ?>"
                    class="flex h-10 w-full bg-gray-50 border border-gray-600 rounded-lg text-gray-600 items-center justify-center font-medium gap-2 text-sm">Hapus Filter</a>
            </div>
        </div>

        <!-- Filter Kategori (Horizontal Scroll) -->
        <div class="flex w-full overflow-x-auto scrollbar-none gap-4 shrink-0 py-1">
            <a
                href="<?= Helpers::url('/kasir/transaksi?kategori=semua' . (!empty($keyword) ? '&search=' . urlencode($keyword) : "")) ?>"
                class="text-sm shrink-0 whitespace-nowrap w-fit h-fit px-6 py-1 rounded-full border <?= $filterKategori === 'semua' ? 'bg-lime-50 text-lime-600 border-lime-600' : 'text-gray-600 border-gray-600' ?>">Semua</a>
            <?php foreach ($kategoriData as $kategori): ?>
                <a
                    href="<?= Helpers::url('/kasir/transaksi?kategori=' . $kategori['slug'] . (!empty($keyword) ? '&search=' . urlencode($keyword) : "")) ?>"
                    class="text-sm shrink-0 whitespace-nowrap w-fit h-fit px-6 py-1 rounded-full border <?= $filterKategori === $kategori['slug'] ? 'bg-lime-50 text-lime-600 border-lime-600' : 'text-gray-600 border-gray-600' ?>"><?= $kategori['nama'] ?></a>
            <?php endforeach; ?>
        </div>
        <!-- End Filter -->

        <!-- Tempat Grid Item/Produk Kamu Nanti -->
        <div class="flex-1">

        </div>

    </div>

    <!-- Sidebar Kanan (Fixed Layout via Flexbox) -->
    <div class="w-1/4 flex flex-col justify-between bg-gray-100 h-full border-l-2 border-gray-200 shrink-0">
        <div class="border-b border-gray-200 bg-white w-full h-fit p-4 shrink-0">
            <h2 class="text-xl font-semibold">Detail Transaksi</h2>
            <p class="text-xs text-gray-600">2 item dalam keranjang</p>
        </div>

        <!-- Item Keranjang (Scrollable jika item banyak) -->
        <div class="flex-1 w-full overflow-y-auto p-4 bg-gray-100">
            <!-- List item keranjang -->
             
        </div>

        <!-- Total & Payment Footer -->
        <div class="flex flex-col gap-4 h-fit bg-white border-t border-gray-200 items-end shrink-0 p-4">
            <div class="w-full">
                <div class="flex justify-between items-center text-sm w-full font-medium">
                    <p class="text-gray-400">Subtotal</p>
                    <p class="font-semibold">Rp14.000</p>
                </div>
                <div class="flex justify-between items-center text-sm w-full font-medium">
                    <p class="text-gray-400">Pajak (10%)</p>
                    <p class="font-semibold">Rp1.400</p>
                </div>
            </div>

            <div class="w-full flex justify-between items-center">
                <p class="text-lg font-semibold">Total</p>
                <p class="text-lg font-semibold text-lime-600">Rp15.400</p>
            </div>

            <div class="w-full h-fit">
                <button class="text-sm rounded-lg w-full h-fit py-3 bg-lime-600 text-white cursor-pointer">
                    Lanjutkan Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>