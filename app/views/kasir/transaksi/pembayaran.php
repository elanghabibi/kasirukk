<div class="flex flex-col gap-4">
    <div class="w-full h-40 bg-lime-50 bg-[url('../../img/food-doodle.png')] bg-repeat object-contain flex flex-col items-center justify-center gap-2">
        <p class="text-base">Total Pembayaran</p>
        <h2 class="text-lime-600 text-4xl font-extrabold"><?= Helpers::rupiah($total) ?></h2>
    </div>
</div>

<div class="grid grid-cols-2 w-full gap-8 py-4 px-40">
    <div class="flex flex-col w-full gap-4">
        <h2 class="text-lg font-semibold">Menu yang dipesan</h2>

        <div class="flex flex-col gap-4">
            <?php foreach ($cart as $item): ?>
                <div class="flex justify-between w-full rounded-lg border border-gray-200 shadow-sm p-4">
                    <div class="flex gap-4">
                        <div class="h-20 aspect-square overflow-hidden rounded-lg shrink-0">
                            <img src="<?= Helpers::url($item['foto']) ?>" alt="" class="w-full h-full object-cover">
                        </div>

                        <div class="flex flex-col justify-between">
                            <h3 class="text-lg font-semibold"><?= $item['nama_menu'] ?></h3>
                            <p class="text-sm text-lime-600 font-semibold"><?= Helpers::rupiah($item['harga']) ?></p>
                        </div>
                    </div>

                    <div class="flex flex-col text-right justify-between">
                        <p class="text-sm text-gray-600 font-semibold">x<?= $item['qty'] ?></p>
                        <p class="text-sm font-semibold"><?= Helpers::rupiah($item['subtotal']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <h2 class="text-lg font-semibold">Informasi Pembayaran</h2>

        <form action="<?= Helpers::url('/kasir/transaksi/store') ?>" method="POST" autocomplete="off" class="flex flex-col gap-4">
            <div class="flex flex-col w-full gap-2">
                <label for="customer" class="text-gray-600">Customer (Opsional)</label>
                <input type="text" name="customer" id="customer" class="border-2 border-gray-200 rounded-lg w-full py-2 pl-4 placeholder:text-gray-400 focus:outline-none" placeholder="Nama">
            </div>

            <div class="flex flex-col w-full max-w-sm gap-2">
                <label class="text-gray-600">Pilih Tipe Order</label>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Dine In -->
                    <label class="cursor-pointer">
                        <input
                            type="radio"
                            name="tipe_order"
                            value="dine_in"
                            class="peer hidden"
                            checked />
                        <div class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-slate-200 bg-white text-slate-600 peer-checked:border-lime-600 peer-checked:bg-lime-50/60 peer-checked:text-lime-600">
                            <i class="bx bx-location text-3xl mb-1"></i>
                            <span class="font-medium text-sm">Dine In</span>
                        </div>
                    </label>

                    <!-- Take Away -->
                    <label class="cursor-pointer">
                        <input
                            type="radio"
                            name="tipe_order"
                            value="take_away"
                            class="peer hidden" />
                        <div class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-slate-200 bg-white text-slate-600 peer-checked:border-lime-600 peer-checked:bg-lime-50/60 peer-checked:text-lime-600">
                            <i class="bx bx-user text-3xl mb-1"></i>
                            <span class="font-medium text-sm">Take Away</span>
                        </div>
                    </label>
                </div>
                <?php if (isset($_SESSION['errors']['tipe_order'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['tipe_order'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Nomor Meja -->
            <div class="flex flex-col w-full gap-2">
                <label for="no_meja" class="text-gray-600">Nomor Meja (Opsional)</label>
                <input type="text" name="no_meja" id="no_meja" class="border-2 border-gray-200 rounded-lg w-full py-2 pl-4 placeholder:text-gray-400 focus:outline-none" placeholder="07">
            </div>

            <!-- Tanggal Transaksi -->
            <div class="flex flex-col w-full gap-2">
                <label for="tanggal_transaksi" class="text-gray-600">Tanggal Transaksi</label>
                <input type="datetime-local" name="tanggal_transaksi" id="tanggal_transaksi" class="border-2 border-gray-200 rounded-lg w-full py-2 pl-4 placeholder:text-gray-400 focus:outline-none" value="<?= date('Y-m-d\TH:i') ?>">
                <?php if (isset($_SESSION['errors']['tanggal_transaksi'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['tanggal_transaksi'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Bayar -->
            <div class="w-full max-w-lg flex flex-col gap-2">
                <label for="bayar" class="text-gray-600">Tunai</label>
                <input type="number" onwheel="this.blur()" name="bayar" id="bayar" class="border-2 border-gray-200 rounded-lg w-full py-2 pl-4 placeholder:text-gray-400 focus:outline-none" placeholder="0">

                <!-- Tombol Quick Nominal -->
                <div class="flex flex-wrap items-center gap-2 mt-1">
                    <button
                        type="button"
                        class="btn-quick-nominal text-xs font-medium text-slate-600 border border-slate-300 px-3 py-1.5 rounded-full hover:bg-slate-100 transition-colors"
                        data-value="10000">
                        Rp10.000
                    </button>
                    <button
                        type="button"
                        class="btn-quick-nominal text-xs font-medium text-slate-600 border border-slate-300 px-3 py-1.5 rounded-full hover:bg-slate-100 transition-colors"
                        data-value="20000">
                        Rp20.000
                    </button>
                    <button
                        type="button"
                        class="btn-quick-nominal text-xs font-medium text-slate-600 border border-slate-300 px-3 py-1.5 rounded-full hover:bg-slate-100 transition-colors"
                        data-value="50000">
                        Rp50.000
                    </button>
                    <button
                        type="button"
                        class="btn-quick-nominal text-xs font-medium text-slate-600 border border-slate-300 px-3 py-1.5 rounded-full hover:bg-slate-100 transition-colors"
                        data-value="100000">
                        Rp100.000
                    </button>
                    <button
                        type="button"
                        id="btn-uang-pas"
                        class="text-xs font-medium text-slate-600 border border-slate-300 px-3 py-1.5 rounded-full hover:bg-slate-100 transition-colors">
                        Uang Pas
                    </button>
                </div>

                <?php if (isset($_SESSION['errors']['bayar'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['bayar'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Keterangan -->
            <div class="flex flex-col w-full gap-2">
                <label for="keterangan" class="text-gray-600">Keterangan (Opsional)</label>
                <textarea name="keterangan" id="keterangan" class="border-2 border-gray-200 rounded-lg w-full h-30 py-2 pl-4 placeholder:text-gray-400 focus:outline-none resize-none" placeholder="Tambahkan micin sedikit..."></textarea>
            </div>

            <div class="flex flex-col gap-1 w-full">
                <div class="flex w-full items-center justify-between">
                    <p>Subtotal (<?= $totalItem ?> qty)</p>
                    <p><?= Helpers::rupiah($totalBeforeTax) ?></p>
                </div>
                <div class="flex w-full items-center justify-between">
                    <p>Pajak (10%)</p>
                    <p><?= Helpers::rupiah($tax) ?></p>
                </div>
                <div class="flex w-full items-center justify-between">
                    <p>Total</p>
                    <p><?= Helpers::rupiah($total) ?></p>
                </div>
                <div class="flex w-full items-center justify-between">
                    <p>Tunai</p>
                    <p id="bayarTxt"><?= Helpers::rupiah(0) ?></p>
                </div>
                <div class="flex w-full items-center justify-between">
                    <p>Kembalian</p>
                    <p id="kembalianTxt"><?= Helpers::rupiah(0) ?></p>
                </div>
            </div>

            <div class="flex items-center gap-4 w-full justify-end">
                <button
                    type="submit"
                    name="action"
                    value="save_print"
                    class="flex items-center justify-center gap-2 w-fit h-fit px-4 py-2 rounded-lg cursor-pointer border border-gray-200 bg-gray-50">
                    <i class="bx bx-printer"></i> Simpan & Cetak
                </button>

                <button
                    type="submit"
                    name="action"
                    value="save"
                    class="flex items-center justify-center gap-2 w-fit h-fit px-4 py-2 rounded-lg cursor-pointer border border-lime-600 bg-lime-600 text-lime-50">
                    <i class="bx bx-save"></i> Simpan Saja
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function formatRupiah(angka) {
        return 'Rp' + new Intl.NumberFormat('id-ID').format(angka || 0);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const tunaiInput = document.getElementById('bayar');
        const quickButtons = document.querySelectorAll('.btn-quick-nominal');
        const btnUangPas = document.getElementById('btn-uang-pas');

        const bayarTxt = document.getElementById("bayarTxt");
        const kembalianTxt = document.getElementById("kembalianTxt");

        // Contoh total tagihan dari backend/PHP (sesuaikan dengan variabel transaksi kamu)
        const totalTagihan = <?= $total ?>;

        tunaiInput.addEventListener("input", () => {
            const val = Number(tunaiInput.value) || 0;
            bayarTxt.textContent = formatRupiah(val);

            const kembalian = val - totalTagihan;
            kembalianTxt.textContent = formatRupiah(kembalian);
        });

        // Event saat tombol nominal cepat diklik (Rp10.000, Rp20.000, dll)
        quickButtons.forEach(button => {
            button.addEventListener('click', () => {
                const val = button.getAttribute('data-value');
                bayarTxt.textContent = formatRupiah(val);

                const kembalian = val - totalTagihan;
                kembalianTxt.textContent = formatRupiah(kembalian);

                tunaiInput.value = val;
                tunaiInput.focus();
            });
        });

        // Event saat tombol "Uang Pas" diklik
        btnUangPas.addEventListener('click', () => {
            tunaiInput.value = totalTagihan;
            bayarTxt.textContent = formatRupiah(totalTagihan);

            const kembalian = tunaiInput.value - totalTagihan;
            kembalianTxt.textContent = formatRupiah(kembalian);

            tunaiInput.focus();
        });
    });
</script>