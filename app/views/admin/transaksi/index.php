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
        <form action="<?= Helpers::url('/admin/transaksi') ?>" method="GET" class="relative w-full" autocomplete="off" spellcheck="false">
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
            <input
                type="hidden"
                name="status"
                value="<?= htmlspecialchars($status ?? 'aktif') ?>">
        </form>
    </div>

    <div class="flex-1 h-full">
        <a
            href="<?= Helpers::url('/admin/transaksi') ?>"
            class="flex h-full w-full bg-gray-50 border border-gray-600 rounded-lg text-gray-600 items-center justify-center font-medium gap-2 text-sm">Hapus Filter</a>
    </div>
</div>

<div class="flex gap-4">
    <!-- Table -->
    <div class="w-full overflow-hidden rounded-lg border border-gray-200">
        <div class="w-full overflow-x-auto">
            <table class="min-w-[1200px] text-left text-sm">
                <thead class="bg-gray-100 text-gray-600 font-semibold">
                    <tr class="border-b border-gray-200">
                        <td class="w-14 py-3 px-6">ID</td>
                        <td class="w-40 py-3 px-6">Kode Transaksi</td>
                        <td class="w-38 py-3 px-6">Kasir</td>
                        <td class="w-38 py-3 px-6">Customer</td>
                        <td class="w-32 py-3 px-6">Tipe Order</td>
                        <td class="w-30 py-3 px-6">Item</td>
                        <td class="w-38 py-3 px-6">Total Harga</td>
                        <td class="w-50 py-3 px-6">Tanggal </td>
                        <td class="w-28 py-3 px-6">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dataTransaksi)) : ?>
                        <?php foreach ($dataTransaksi as $transaksi) : ?>
                            <?php foreach ($transaksi['details'] as $detail) : ?>
                                <tr>
                                    <td class="w-14 py-3 px-6"><?= $transaksi['id'] ?></td>
                                    <td class="w-40 py-3 px-6"><?= $transaksi['kode_transaksi'] ?></td>
                                    <td class="w-38 py-3 px-6"><?= $transaksi['kasir'] ?></td>
                                    <td class="w-38 py-3 px-6"><?= $transaksi['nama_pelanggan'] ?></td>
                                    <td class="w-32 py-3 px-6"><?= $transaksi['tipe_order'] == 'dine_in' ? "Dine In" : "Take Away" ?></td>
                                    <td class="w-38 py-3 px-6"><?= $transaksi['total_qty'] ?> item</td>
                                    <td class="w-32 py-3 px-6"><?= Helpers::rupiah($transaksi['total']) ?></td>
                                    <td class="w-50 py-3 px-6"><?= Helpers::formatDate($transaksi['tanggal']) ?></td>
                                    <td class="w-28 py-3 px-6">
                                        <div class="flex gap-2">
                                            <div>
                                                <a href="<?= Helpers::url('/admin/riwayat-transaksi/edit/' . $transaksi['id']) ?>"
                                                    class="cursor-pointer w-fit aspect-square p-2 bg-orange-100 text-orange-600 rounded flex items-center gap-1">
                                                    <i class="bx bx-edit"></i>
                                                </a>
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
        // $currentPage = $pagination['currentPage'];
        // $totalPages = $pagination['totalPages'];
        ?>
        <!-- 
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
                        href="?page=<?= $currentPage - 1 ?>&search=<?= urlencode($keyword) ?>&status=<?= urlencode($status) ?>"
                        class="w-8 aspect-square flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100">
                        <i class="bx bx-chevrons-left"></i>
                    </a>

                <?php endif; ?>


                <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                    <a
                        href="?page=<?= $i ?>&search=<?= urlencode($keyword) ?>&status=<?= urlencode($status) ?>"
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
                        href="?page=<?= $currentPage + 1 ?>&search=<?= urlencode($keyword) ?>&status=<?= urlencode($status) ?>"
                        class="w-8 aspect-square flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100">
                        <i class="bx bx-chevrons-right"></i>
                    </a>

                <?php endif; ?>

            </div>
        </div> -->
        <!-- Paginate -->
    </div>
    <!-- End Table -->
</div>