<div class="flex gap-4 w-full h-26">
    <div class="flex flex-col justify-between p-4 w-56 h-full border-2 border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-sm text-gray-600">Total Transaksi</h3>
        <p class="text-3xl font-semibold"><?= $totalTransaksi ?></p>
    </div>
    <div class="flex flex-col justify-between p-4 w-70 h-full border-2 border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-sm text-gray-600">Total Pendapatan</h3>
        <p class="text-3xl font-semibold"><?= Helpers::rupiah($totalPendapatan) ?></p>
    </div>
</div>

<div class="w-full flex justify-between items-center h-fit gap-4">
    <div class="w-8/10">
        <form action="<?= Helpers::url('/admin/riwayat-transaksi') ?>" method="GET" class="relative w-full" autocomplete="off" spellcheck="false">
            <button
                type="submit"
                class="flex items-center justify-center cursor-pointer absolute top-1/2 -translate-y-1/2 left-5 text-xl text-gray-600">
                <i class="bx bx-search"></i>
            </button>
            <input
                type="search"
                name="search"
                class="w-full py-2 border-2 border-gray-200 rounded-lg pl-12 focus:outline-lime-600"
                placeholder="Cari transaksi..."
                value="<?= htmlspecialchars($keyword ?? '') ?>" />
        </form>
    </div>

    <div class="flex-1 h-full">
        <a
            href="<?= Helpers::url('/admin/riwayat-transaksi') ?>"
            class="flex h-full w-full bg-gray-50 border border-gray-600 rounded-lg text-gray-600 items-center justify-center font-medium gap-2 text-sm">Hapus Filter</a>
    </div>
</div>

<div class="flex gap-4">
    <!-- Table -->
    <div class="w-full overflow-hidden rounded-lg border border-gray-200">
        <div class="w-full overflow-x-auto">
            <table class="min-w-[1400px] text-left text-sm">
                <thead class="bg-gray-100 text-gray-600 font-semibold">
                    <tr class="border-b border-gray-200">
                        <td class="w-14 py-3 px-6">ID</td>
                        <td class="w-40 py-3 px-6">Kode Transaksi</td>
                        <td class="w-38 py-3 px-6">Kasir</td>
                        <td class="w-38 py-3 px-6">Customer</td>
                        <td class="w-36 py-3 px-6">Tipe Order</td>
                        <td class="w-22 py-3 px-6">Item</td>
                        <td class="w-38 py-3 px-6">Total Harga</td>
                        <td class="w-44 py-3 px-6">Tanggal </td>
                        <td class="w-28 py-3 px-6">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dataTransaksi)) : ?>
                        <?php foreach ($dataTransaksi as $transaksi) : ?>

                            <tr>
                                <td class="w-14 py-3 px-6"><?= $transaksi['id'] ?></td>
                                <td class="w-40 py-3 px-6"><?= $transaksi['kode_transaksi'] ?></td>
                                <td class="w-38 py-3 px-6"><?= $transaksi['kasir'] ?></td>
                                <td class="w-38 py-3 px-6"><?= $transaksi['nama_pelanggan'] ?></td>
                                <td class="w-36 py-3 px-6"><?= $transaksi['tipe_order'] == 'dine_in' ? "Dine In" : "Take Away" ?></td>
                                <td class="w-22 py-3 px-6"><?= $transaksi['total_qty'] ?> item</td>
                                <td class="w-32 py-3 px-6"><?= Helpers::rupiah($transaksi['total']) ?></td>
                                <td class="w-44 py-3 px-6"><?= Helpers::formatDate($transaksi['tanggal']) ?></td>
                                <td class="w-28 py-3 px-6">
                                    <div class="flex gap-2">
                                        <div>
                                            <butto type="button"
                                                class="btn-open-modal cursor-pointer w-fit aspect-square p-2 bg-orange-100 text-orange-600 rounded flex items-center gap-1" data-transaksi='<?= htmlspecialchars(json_encode($transaksi), ENT_QUOTES, 'UTF-8') ?>'>
                                            <i class="bx bx-info-circle"></i>
                                            </button>
                                        </div>
                                        <div>
                                            <a href="<?= Helpers::url('/admin/riwayat-transaksi/struk/' . $transaksi['id']) ?>"
                                                class="cursor-pointer w-fit aspect-square p-2 bg-blue-100 text-blue-600 rounded flex items-center gap-1">
                                                <i class="bx bx-printer"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="py-3 px-6 text-center">Tidak ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginate -->
        <?php
        $currentPage = $pagination['currentPage'];
        $totalPages = $pagination['totalPages'];
        ?>

        <div class="flex items-center justify-between text-xs p-4">

            <p class="text-gray-500">
                Menampilkan
                <?= $pagination['from'] ?>
                sampai
                <?= $pagination['to'] ?>
                dari
                <?= $pagination['total'] ?>
                data
            </p>

            <div class="flex items-center gap-2">

                <?php if ($pagination['hasPreviousPage']): ?>

                    <a
                        href="?page=<?= $currentPage - 1 ?>&search=<?= urlencode($keyword) ?>"
                        class="w-8 aspect-square flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100">
                        <i class="bx bx-chevrons-left"></i>
                    </a>

                <?php endif; ?>


                <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                    <a
                        href="?page=<?= $i ?>&search=<?= urlencode($keyword) ?>"
                        class="w-8 aspect-square flex items-center justify-center rounded-lg
                <?= $i === $currentPage
                        ? 'bg-lime-600 text-white'
                        : 'border border-gray-200 hover:bg-gray-100'
                ?>">
                        <?= $i ?>
                    </a>

                <?php endfor; ?>


                <?php if ($pagination['hasNextPage']): ?>

                    <a
                        href="?page=<?= $currentPage + 1 ?>&search=<?= urlencode($keyword) ?>"
                        class="w-8 aspect-square flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100">
                        <i class="bx bx-chevrons-right"></i>
                    </a>

                <?php endif; ?>

            </div>
        </div>
        <!-- Paginate -->
    </div>
    <!-- End Table -->
</div>

<!-- GLOBAL MODAL DETAIL TRANSAKSI -->
<div id="modalDetail" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 p-4">
    <!-- Box Modal Card -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all duration-300 p-6 text-gray-800">

        <!-- Header Modal: Judul & Close Button -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Detail Transaksi</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Section 1: Kode Transaksi & Tanggal -->
        <div class="flex justify-between items-center mb-4 text-xs">
            <span id="modalKode" class="font-bold text-gray-900 text-sm">#TX-20260808-002</span>
            <span id="modalTanggal" class="text-gray-400">8 Agu 2026, 10:32</span>
        </div>

        <!-- Section 2: Customer & Tipe Pemesanan -->
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Customer</p>
                <p id="modalCustomer" class="font-bold text-base text-gray-900">Papoy</p>
            </div>
            <div class="text-right">
                <span id="modalTipeOrder" class="text-xs text-gray-400 capitalize">Dine in</span>
            </div>
        </div>

        <hr class="border-gray-100 my-4" />

        <!-- Section 3: Ringkasan Item / Qty -->
        <div class="mb-4">
            <div class="flex justify-between items-center text-xs text-gray-400 mb-2">
                <span>Item</span>
                <span id="modalTotalQty">2 qty</span>
            </div>

            <!-- List Items Dynamic -->
            <div id="modalItemsList" class="space-y-3">
                <!-- Item row template JS -->
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-sm text-gray-900">Mie Ayam</p>
                        <p class="text-xs text-gray-400">2 x Rp7.000</p>
                    </div>
                    <span class="font-medium text-sm text-gray-900">Rp14.000</span>
                </div>
            </div>
        </div>

        <hr class="border-gray-100 my-4" />

        <!-- Section 4: Subtotal, Pajak, Total -->
        <div class="space-y-1.5 text-sm mb-4">
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-800">Subtotal</span>
                <span id="modalSubtotal" class="font-bold text-gray-900">Rp14.000</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-800">Pajak</span>
                <span id="modalPajak" class="font-bold text-gray-900">Rp1.400</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-800">Total</span>
                <span id="modalTotal" class="font-bold text-gray-900">Rp15.400</span>
            </div>
        </div>

        <!-- Section 5: Pembayaran & Kembalian -->
        <div class="space-y-1 text-sm pt-2">
            <p class="text-xs text-gray-400 mb-1">Pembayaran</p>
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-800">Tunai</span>
                <span id="modalTunai" class="font-bold text-gray-900">Rp20.000</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-800">Kembalian</span>
                <span id="modalKembalian" class="font-bold text-gray-900">Rp4.600</span>
            </div>
        </div>

    </div>
</div>

<script>
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
                const data = JSON.parse(this.getAttribute('data-transaksi'));

                console.log(data);
                // Set Informasi Atas
                document.getElementById('modalKode').textContent = '#' + (data.kode_transaksi || '');
                document.getElementById('modalTanggal').textContent = formatTanggal(data.created_at);
                document.getElementById('modalCustomer').textContent = data.nama_pelanggan || '-';
                document.getElementById('modalTipeOrder').textContent = data.tipe_order || 'Dine in';

                // Set Total Qty & List Item
                document.getElementById('modalTotalQty').textContent = (data.total_qty || 0) + ' qty';

                const itemsContainer = document.getElementById('modalItemsList');
                itemsContainer.innerHTML = ''; // Reset item list

                if (data.details && data.details.length > 0) {
                    data.details.forEach(item => {
                        const itemRow = document.createElement('div');
                        itemRow.className = 'flex justify-between items-start';
                        itemRow.innerHTML = `
                        <div>
                            <p class="font-bold text-sm text-gray-900">${item.nama_menu}</p>
                            <p class="text-xs text-gray-400">${item.qty} x ${formatRupiah(item.harga_menu)}</p>
                        </div>
                        <span class="font-medium text-sm text-gray-900">${formatRupiah(item.subtotal)}</span>
                    `;
                        itemsContainer.appendChild(itemRow);
                    });
                } else {
                    itemsContainer.innerHTML = '<p class="text-xs text-gray-400">Tidak ada detail item</p>';
                }

                // Set Rincian Harga
                document.getElementById('modalSubtotal').textContent = formatRupiah(data.subtotal);
                document.getElementById('modalPajak').textContent = formatRupiah(data.pajak);
                document.getElementById('modalTotal').textContent = formatRupiah(data.total);

                // Set Pembayaran
                document.getElementById('modalTunai').textContent = formatRupiah(data.tunai);
                document.getElementById('modalKembalian').textContent = formatRupiah(data.kembalian);

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
</script>