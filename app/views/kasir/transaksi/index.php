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

        <!-- Tempat Grid Item/Produk -->
        <div class="flex-1 grid grid-cols-4 gap-6">
            <!-- Card Menu -->


            <?php foreach ($menuData as $menu): ?>

                <div class="relative w-full h-fit flex flex-col items-center border-2 border-gray-200 rounded-2xl shadow-sm p-4 gap-4">
                    <p class="text-xs text-gray-400"><?= strtoupper($menu['nama_kategori']) ?></p>

                    <!-- <?php if (isset($cart[$menu['id']])): ?>
                        <div class="absolute -top-3 right-1/2 translate-x-1/2 flex items-center justify-center w-10 aspect-square border-4 border-gray-200 bg-white rounded-full text-lime-500 font-bold ">
                            <p><?= $cart[$menu['id']]['qty'] ?></p>
                        </div>
                    <?php endif; ?> -->
                    <div class="cursor-pointer btn-open-modal w-full aspect-[4/3] rounded-lg overflow-hidden shrink-0" data-menu='<?= htmlspecialchars(json_encode($menu), ENT_QUOTES, 'UTF-8') ?>'>
                        <img class="w-full h-full object-cover" src="<?= Helpers::url($menu['foto']) ?>" alt="">
                    </div>

                    <div class="flex flex-col items-center">
                        <p class="font-semibold"><?= $menu['nama'] ?></p>
                        <p class="font-semibold text-lime-600 text-sm"><?= Helpers::rupiah($menu['harga']) ?></p>
                    </div>


                    <?php if ($menu['ketersediaan'] == "tersedia"): ?>
                        <?php if (isset($cart[$menu['id']])): ?>
                            <!-- Indikator Jumlah Beli -->
                            <div class="flex w-full justify-between items-center gap-4">
                                <!-- Kurangi Item -->
                                <form
                                    class="w-fit"
                                    action="<?= Helpers::url('/kasir/transaksi/cart/update') ?>"
                                    method="POST">
                                    <input
                                        type="hidden"
                                        name="menu_id"
                                        value="<?= $menu['id'] ?>">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="decrease">

                                    <button class="flex items-center justify-center w-fit h-fit p-2 rounded-lg border border-gray-200 text-xs cursor-pointer text-gray-600"><i class="bx bx-minus"></i></button>
                                </form>
                                <!-- End Kurangi Item -->

                                <p class="font-semibold text-sm"><?= $cart[$menu['id']]['qty'] ?></p>

                                <!-- Tambah Item -->
                                <form
                                    class="w-fit"
                                    action="<?= Helpers::url('/kasir/transaksi/cart/update') ?>"
                                    method="POST">
                                    <input
                                        type="hidden"
                                        name="menu_id"
                                        value="<?= $menu['id'] ?>">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="increase">

                                    <button type="submit" class="flex items-center justify-center w-fit h-fit p-2 rounded-lg border border-lime-600 bg-lime-600 text-xs cursor-pointer text-lime-50"><i class="bx bx-plus"></i></button>
                                </form>
                                <!-- End Tambah Item -->

                            </div>
                        <?php else: ?>
                            <form class="w-full" action="<?= Helpers::url("/kasir/transaksi/cart/add") ?>" method="POST">
                                <input
                                    type="hidden"
                                    name="menu_id"
                                    value="<?= $menu['id'] ?>">
                                <button type="submit" class="cursor-pointer relative w-full p-2 border-2 border-lime-600 rounded-full overflow-hidden bg-lime-50">
                                    <p class="text-sm text-lime-600">ADD</p>

                                    <div class="flex items-center justify-center absolute right-0 top-1/2 -translate-y-1/2 h-full aspect-square p-1 rounded-full overflow-hidden">
                                        <div class="flex items-center justify-center w-full aspect-square rounded-full bg-lime-600 ">
                                            <i class="bx bx-plus text-lime-50"></i>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="flex items-center justify-center">
                            <p class="text-lg text-gray-600">Menu Habis</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- End Card Menu -->

            <div class="w-full col-span-4 flex items-center justify-center">
                <?php if (empty($menuData)): ?>
                    <?php if (isset($_GET['kategori'])): ?>
                        <p class="text-gray-600 text-sm">Menu dengan kategori "<?= Helpers::slugToText($_GET['kategori']) ?>" tidak ditemukan.</p>
                    <?php else: ?>
                        <p class="text-gray-600 text-sm">Menu masih kosong! Silahkan tambah menu terlebih dahulu.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Sidebar Kanan (Fixed Layout via Flexbox) -->
    <div class="w-1/4 flex flex-col justify-between bg-gray-100 h-full border-l-2 border-gray-200 shrink-0">
        <div class="flex justify-between border-b border-gray-200 bg-white w-full h-fit p-4 shrink-0">
            <div>
                <h2 class="text-xl font-semibold">Detail Transaksi</h2>
                <p class="text-xs text-gray-600"><?= $totalItem ?> item dalam keranjang</p>
            </div>
            <?php if (!empty($cart)): ?>
                <div>
                    <form action="<?= Helpers::url('/kasir/transaksi/cart/clear') ?>" method="POST">
                        <button class="cursor-pointer text-xs underline text-red-500" type="submit">Bersihkan</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Item Keranjang (Scrollable jika item banyak) -->
        <div class="flex-1 flex flex-col w-full overflow-y-auto p-4 bg-gray-100 gap-2">
            <!-- List item keranjang -->
            <?php if (empty($cart)) : ?>
                <p class="text-gray-600 text-sm">Keranjang masih kosong.</p>

            <?php else : ?>

                <?php foreach ($cart as $item) : ?>
                    <!-- Card -->
                    <div class="w-full h-fit flex flex-col bg-white rounded-lg p-3 gap-2">
                        <div class="flex justify-between">
                            <div class="flex gap-2">
                                <div class="w-14 aspect-square rounded-lg shrink-0 overflow-hidden">
                                    <img class="w-full h-full object-cover" src="<?= Helpers::url($item['foto']) ?>" alt="">
                                </div>
                                <div>
                                    <p class="font-semibold text-base"><?= $item['nama_menu'] ?></p>
                                    <p class="text-xs font-semibold text-lime-600"><?= Helpers::rupiah($item['harga']) ?></p>
                                </div>
                            </div>
                            <div>
                                <form
                                    action="<?= Helpers::url('/kasir/transaksi/cart/remove') ?>"
                                    method="POST">
                                    <input
                                        type="hidden"
                                        name="menu_id"
                                        value="<?= $item['menu_id'] ?>">

                                    <button type="submit" class="cursor-pointer flex items-center justify-center"><i class="bx bx-trash text-red-500"></i></button>
                                </form>
                            </div>
                        </div>

                        <div class="w-full h-fit flex items-center justify-between">
                            <!-- Indikator Jumlah Beli -->
                            <div class="flex items-center gap-4">
                                <!-- Kurangi Item -->
                                <form
                                    class="w-full"
                                    action="<?= Helpers::url('/kasir/transaksi/cart/update') ?>"
                                    method="POST">
                                    <input
                                        type="hidden"
                                        name="menu_id"
                                        value="<?= $item['menu_id'] ?>">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="decrease">

                                    <button class="flex items-center justify-center w-fit h-fit p-2 rounded-lg border border-gray-200 text-xs cursor-pointer text-gray-600"><i class="bx bx-minus"></i></button>
                                </form>
                                <!-- End Kurangi Item -->

                                <p class="font-semibold text-sm"><?= $item['qty'] ?></p>

                                <!-- Tambah Item -->
                                <form
                                    class="w-full"
                                    action="<?= Helpers::url('/kasir/transaksi/cart/update') ?>"
                                    method="POST">
                                    <input
                                        type="hidden"
                                        name="menu_id"
                                        value="<?= $item['menu_id'] ?>">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="increase">

                                    <button type="submit" class="flex items-center justify-center w-fit h-fit p-2 rounded-lg border border-lime-600 bg-lime-600 text-xs cursor-pointer text-lime-50"><i class="bx bx-plus"></i></button>
                                </form>
                                <!-- End Tambah Item -->
                            </div>

                            <!-- Subtotal -->
                            <div>
                                <p class="font-semibold"><?= Helpers::rupiah($item['subtotal']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- End Card -->
        </div>

        <!-- Total & Payment Footer -->
        <div class="flex flex-col gap-4 h-fit bg-white border-t border-gray-200 items-end shrink-0 p-4">
            <div class="w-full">
                <div class="flex justify-between items-center text-sm w-full font-medium">
                    <p class="text-gray-400">Subtotal</p>
                    <p class="font-semibold"><?= Helpers::rupiah($totalBeforeTax ?? 0) ?></p>
                </div>
                <div class="flex justify-between items-center text-sm w-full font-medium">
                    <p class="text-gray-400">Pajak (10%)</p>
                    <p class="font-semibold"><?= Helpers::rupiah($tax ?? 0) ?></p>
                </div>
            </div>

            <div class="w-full flex justify-between items-center">
                <p class="text-lg font-semibold">Total</p>
                <p class="text-lg font-semibold text-lime-600"><?= Helpers::rupiah($total ?? 0) ?></p>
            </div>

            <div class="w-full h-fit">
                <a href="<?= Helpers::url('/kasir/transaksi/pembayaran') ?>" class="flex items-center justify-center text-sm rounded-lg w-full h-fit py-3 bg-lime-600 text-white cursor-pointer">
                    Lanjutkan Pembayaran
                </a>
            </div>
        </div>
    </div>
</div>


<!-- GLOBAL MODAL DETAIL MENU -->
<div id="modalDetail" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
    <!-- Box Modal Card -->
    <div class="space-y-4 bg-white rounded-lg shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300 p-6 text-gray-800">

        <!-- Header Modal: Judul & Close Button -->
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Detail Menu</h3>
            <button id="closeModal" class="cursor-pointer text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-2 w-full gap-4">
            <div class="w-full aspect-square overflow-hidden shrink-0 rounded-lg">
                <img id="modalFoto" class="w-full h-full object-cover" src="<?= Helpers::url('/uploads/foto_menu/default.png') ?>" alt="">
            </div>

            <div class="w-full flex flex-col justify-between">
                <div>
                    <p id="modalKategori" class="text-xs">MAKANAN</p>
                    <h2 id="modalNamaMenu" class="text-2xl font-bold">Pizza</h2>
                    <div id="modalKetersediaan" class="mt-2 text-xs rounded-full px-3 py-0.5 w-fit border">Tersedia</div>
                </div>
                <div>
                    <p id="modalHarga" class="text-lime-600 font-bold text-2xl">Rp89.999</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <h3 class="text-xs text-gray-800">Deskripsi</h3>
            <p id="modalDeskripsi" class="text-xs text-gray-600">Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium repellat quam obcaecati optio dolore eum esse, itaque quidem aliquam unde.</p>
        </div>

    </div>
</div>

<script>
    // Modal Detail

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalDetail');
        const closeModalBtn = document.getElementById('closeModal');

        // Helper Format Rupiah (Tanpa Angka Desimal)
        function formatRupiah(angka) {
            return 'Rp' + new Intl.NumberFormat('id-ID').format(angka || 0);
        }

        // Helper Format Tanggal
        function formatTanggal(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Event Delegation / Event Listener tombol detail
        document.querySelectorAll('.btn-open-modal').forEach(button => {
            button.addEventListener('click', function() {
                const data = JSON.parse(this.getAttribute('data-menu'));

                console.log(data);
                document.getElementById('modalFoto').src = "../" + (data.foto || '');
                document.getElementById('modalKategori').textContent = (data.nama_kategori || '');
                document.getElementById('modalNamaMenu').textContent = data.nama || '';

                document.getElementById('modalKetersediaan').textContent = data.ketersediaan == "tersedia" ? "Tersedia" : "Habis";
                if (data.ketersediaan == 'tersedia') {
                    document.getElementById('modalKetersediaan').classList.remove("border-red-600", "text-red-600", "bg-red-50");
                    document.getElementById('modalKetersediaan').classList.add("border-green-600", "text-green-600", "bg-green-50");
                } else {
                    document.getElementById('modalKetersediaan').classList.remove("border-green-600", "text-green-600", "bg-green-50");
                    document.getElementById('modalKetersediaan').classList.add("border-red-600", "text-red-600", "bg-red-50");
                }
                
                document.getElementById('modalHarga').textContent = formatRupiah(data.harga) || "-";
                document.getElementById('modalDeskripsi').textContent = data.deskripsi || "-";
                
                // Tampilkan Modal
                modal.classList.remove('hidden');
            });
        });

        // Tutup Modal via Tombol X
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Tutup Modal via Klik Backdrop
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

    // End Modal Detail
</script>