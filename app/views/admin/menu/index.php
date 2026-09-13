<div class="w-full flex justify-between items-center h-fit gap-4">
    <div class="w-8/10">
        <form action="<?= Helpers::url('/admin/menu') ?>" method="GET" class="relative w-full">
            <button
                type="submit"
                class="flex items-center justify-center cursor-pointer absolute top-1/2 -translate-y-1/2 left-5 text-xl text-gray-600">
                <i class="bx bx-search"></i>
            </button>
            <input
                type="search"
                name="search"
                class="w-full py-2 border-2 border-gray-200 rounded-lg pl-12 focus:outline-lime-600"
                placeholder="Cari menu..."
                value="<?= htmlspecialchars($keyword ?? '') ?>" />
            <input
                type="hidden"
                name="status"
                value="<?= htmlspecialchars($status ?? 'aktif') ?>">
        </form>
    </div>

    <div class="flex-1 h-full">
        <a
            href="<?= Helpers::url('/admin/menu') ?>"
            class="flex h-full w-full bg-gray-50 border border-gray-600 rounded-lg text-gray-600 items-center justify-center font-medium gap-2 text-sm">Hapus Filter</a>
    </div>
</div>

<!-- Filter -->
<div class="flex gap-4">
    <a
        href="<?= Helpers::url('/admin/menu?status=aktif&search=' . urlencode($keyword ?? '')) ?>"
        class="text-sm w-fit h-fit px-6 py-1 rounded-full border <?= $status === 'aktif' ? 'bg-lime-50 text-lime-600 border-lime-600' : 'text-gray-600 border-gray-600' ?>">Aktif</a>
    <a
        href="<?= Helpers::url('/admin/menu?status=nonaktif&search=' . urlencode($keyword ?? '')) ?>"
        class="text-sm w-fit h-fit px-6 py-1 rounded-full border <?= $status === 'nonaktif' ? 'bg-lime-50 text-lime-600 border-lime-600' : 'text-gray-600 border-gray-600' ?>">Nonaktif</a>
</div>
<!-- End Filter -->

<div class="flex gap-4">
    <!-- Table -->
    <div class="w-68/100 h-fit border border-gray-200 overflow-hidden rounded-lg">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 font-semibold">
                    <tr class="border-b border-gray-200">
                        <td class="w-14 py-3 px-6">ID</td>
                        <td class="w-20 py-3 px-6">Foto</td>
                        <td class="w-50 py-3 px-6">Nama</td>
                        <td class="w-50 py-3 px-6">Harga</td>
                        <td class="w-35 py-3 px-6">Kategori</td>
                        <td class="w-30 py-3 px-6">Ketersediaan</td>
                        <td class="w-40 py-3 px-6">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // $kategoriMap = [];

                    // foreach ($dataKategori as $kategori) {
                    //     $kategoriMap[$kategori['id']] = $kategori['nama'];
                    // }

                    ?>
                    <?php if (!empty($dataMenu)) : ?>
                        <?php foreach ($dataMenu as $menu) : ?>

                            <tr>
                                <td class="w-14 py-3 px-6"><?= $menu['id'] ?></td>
                                <td class="w-20 py-3 px-6">
                                    <div class="w-10 aspect-square rounded-lg overflow-hidden shrink-0">
                                        <img class="w-full h-full object-cover" src="<?= Helpers::url("{$menu['foto']}") ?>" alt="">
                                    </div>
                                </td>
                                <td class="w-50 py-3 px-6 truncate"><?= $menu['nama'] ?></td>
                                <td class="w-50 py-3 px-6 truncate"><?= Helpers::rupiah($menu['harga']) ?></td>
                                <td class="w-14 py-3 px-6"><?= $menu['nama_kategori'] ?></td>
                                <td class="w-30 py-3 px-6">
                                    <?php if ($menu['ketersediaan'] === 'tersedia') : ?>
                                        <span
                                            class="w-fit h-fit px-3 py-1 text-xs rounded-sm bg-green-100 text-green-600">Tersedia</span>
                                    <?php else : ?>
                                        <span class="w-fit h-fit px-3 py-1 text-xs rounded-sm bg-red-100 text-red-600">Habis</span>
                                    <?php endif; ?>
                                </td>
                                <td class="w-40 py-3 px-6">
                                    <?php if ($status === "aktif"): ?>
                                        <div class="flex gap-2">
                                            <div class="modal">
                                                <a href="<?= Helpers::url('/admin/menu/edit/' . $menu['id']) ?>"
                                                    class="modal-btn cursor-pointer w-fit aspect-square p-2 bg-blue-100 text-blue-600 rounded flex items-center gap-1">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                            </div>

                                            <?php if (!isset($menu['deleted_at'])): ?>
                                                <!-- Button Nonaktif -->
                                                <div class="modal">
                                                    <button
                                                        class="modal-btn cursor-pointer w-fit aspect-square p-2 bg-gray-100 text-gray-600 rounded flex items-center gap-1">
                                                        <i class="bx bx-block"></i>
                                                    </button>

                                                    <div
                                                        class="modal-panel opacity-0 pointer-events-none absolute top-0 left-0 w-full h-screen flex justify-center items-center bg-gray-950/10"
                                                        style="z-index: 200">
                                                        <div
                                                            class="bg-gray-50 p-6 text-center rounded-xl shadow-lg shadow-gray-600/5 w-80 max-md:w-7/10 flex flex-col gap-6">
                                                            <i class="bx bx-block text-red-500 text-5xl"></i>
                                                            <h2 class="text-2xl font-bold leading-6">
                                                                Nonaktifkan Menu
                                                            </h2>
                                                            <p class="text-sm text-gray-600">
                                                                Apakah anda yakin ingin menonaktifkan user ini?
                                                            </p>

                                                            <div class="w-full grid grid-cols-2 gap-4">
                                                                <button
                                                                    class="modal-close w-full bg-gray-200 text-gray-950 font-bold py-2 text-sm rounded-lg cursor-pointer">
                                                                    Batal
                                                                </button>
                                                                <form action="<?= Helpers::url('/admin/menu/nonaktifkan/' . $menu['id']) ?>" method="POST" class="form">
                                                                    <button
                                                                        type="submit"
                                                                        class="form-btn w-full bg-red-500 text-gray-50 font-bold py-2 text-sm rounded-lg cursor-pointer">
                                                                        <span class="form-btntext">Ya, nonaktifkan</span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Button Nonaktif -->

                                                <!-- Button Hapus -->
                                                <div class="modal">
                                                    <button
                                                        class="modal-btn cursor-pointer w-fit aspect-square p-2 bg-red-100 text-red-600 rounded flex items-center gap-1">
                                                        <i class="bx bx-trash"></i>
                                                    </button>

                                                    <div
                                                        class="modal-panel opacity-0 pointer-events-none absolute top-0 left-0 w-full h-screen flex justify-center items-center bg-gray-950/10"
                                                        style="z-index: 200">
                                                        <div
                                                            class="bg-gray-50 p-6 text-center rounded-xl shadow-lg shadow-gray-600/5 w-80 max-md:w-7/10 flex flex-col gap-6">
                                                            <i
                                                                class="bx bx-alert-triangle text-red-500 text-5xl"></i>
                                                            <h2 class="text-2xl font-bold leading-6">
                                                                Hapus Data
                                                            </h2>
                                                            <p class="text-sm text-gray-600">
                                                                Apakah anda yakin ingin menghapus?
                                                            </p>

                                                            <div class="w-full grid grid-cols-2 gap-4">
                                                                <button
                                                                    class="modal-close w-full bg-gray-200 text-gray-950 font-bold py-2 text-sm rounded-lg cursor-pointer">
                                                                    Batal
                                                                </button>
                                                                <form action="<?= Helpers::url('/admin/menu/destroy/' . $menu['id']) ?>" method="POST" class="form">
                                                                    <button
                                                                        type="submit"
                                                                        class="form-btn w-full bg-red-500 text-gray-50 font-bold py-2 text-sm rounded-lg cursor-pointer">
                                                                        <span class="form-btntext">Ya, Hapus</span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Button Hapus -->
                                            <?php endif; ?>
                                        </div>
                                    <?php else : ?>
                                        <!-- Button Aktif -->
                                        <div class="modal">
                                            <button
                                                class="modal-btn cursor-pointer w-fit aspect-square p-2 bg-green-100 text-green-600 rounded flex items-center gap-1">
                                                <i class="bx bx-power"></i>
                                            </button>

                                            <div
                                                class="modal-panel opacity-0 pointer-events-none absolute top-0 left-0 w-full h-screen flex justify-center items-center bg-gray-950/10"
                                                style="z-index: 200">
                                                <div
                                                    class="bg-gray-50 p-6 text-center rounded-xl shadow-lg shadow-gray-600/5 w-80 max-md:w-7/10 flex flex-col gap-6">
                                                    <i class="bx bx-power text-green-500 text-5xl"></i>
                                                    <h2 class="text-2xl font-bold leading-6">
                                                        Aktifkan User
                                                    </h2>
                                                    <p class="text-sm text-gray-600">
                                                        Apakah anda yakin ingin mengaktifkan user ini?
                                                    </p>

                                                    <div class="w-full grid grid-cols-2 gap-4">
                                                        <button
                                                            class="modal-close w-full bg-gray-200 text-gray-950 font-bold py-2 text-sm rounded-lg cursor-pointer">
                                                            Batal
                                                        </button>
                                                        <form action="<?= Helpers::url('/admin/menu/aktifkan/' . $menu['id']) ?>" method="POST" class="form">
                                                            <button
                                                                type="submit"
                                                                class="form-btn w-full bg-green-500 text-gray-50 font-bold py-2 text-sm rounded-lg cursor-pointer">
                                                                <span class="form-btntext">Ya, aktifkan</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Button Aktif -->
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="py-3 px-6 text-center">Tidak ada data</td>
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
        </div>
        <!-- Paginate -->
    </div>

    <!-- End Table -->

    <!-- Tambah Menu -->
    <div class="flex-1 w-full border border-gray-200 rounded-lg p-4 flex flex-col gap-4 shadow-sm">
        <h2 class="text-lg font-semibold">Tambah Menu</h2>
        <form action="<?= Helpers::url('/admin/menu/tambah') ?>" method="POST" enctype="multipart/form-data" class="form space-y-4 text-sm">
            <div class="w-full flex flex-col gap-2">
                <label>
                    Foto Menu
                </label>

                <!-- Upload Area -->
                <label
                    for="menu-image"
                    id="upload-area"
                    class="relative flex flex-col items-center justify-center
               w-full h-34
               border-2 border-dashed border-gray-300
               rounded-xl
               cursor-pointer
               bg-white
               hover:bg-gray-50
               transition overflow-hidden">

                    <!-- Default Content -->
                    <div id="upload-content" class="flex flex-col items-center">
                        <!-- Camera Icon -->
                        <i class="bx bx-camera text-2xl text-gray-600"></i>

                        <span class="text-sm text-gray-500">
                            Klik untuk mengunggah foto
                        </span>
                    </div>

                    <!-- Image Preview -->
                    <img
                        id="image-preview"
                        src=""
                        alt="Preview"
                        class="hidden absolute inset-0 w-full h-full object-cover">

                    <!-- Hidden Input -->
                    <input
                        type="file"
                        name="foto"
                        id="menu-image"
                        accept="image/jpeg,image/png,image/webp"
                        class="hidden">
                </label>

                <p class="mt-1 text-[10px] text-gray-400">
                    JPG, PNG atau WebP. Maksimal 2MB.
                </p>
            </div>
            <div class="flex flex-col gap-2">
                <label for="nama">Nama Menu</label>
                <input type="text" id="nama" name="nama" placeholder="Joseph" value="<?= $_SESSION['old']['nama'] ?? '' ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300">
                <?php if (isset($_SESSION['errors']['nama'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['nama'] ?></p>
                <?php endif; ?>
            </div>
            <div class="gap-4 grid grid-cols-2">
                <div class="w-full flex flex-col gap-2">
                    <label for="kategori_id">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="w-full border border-gray-200 rounded-md py-2 pl-2">
                        <option value="" <?= (isset($_SESSION['old']['kategori_id']) && $_SESSION['old']['kategori_id'] === '') ? 'selected' : '' ?>>-- Pilih Kategori --</option>
                        <?php foreach ($dataKategori as $kategori): ?>
                            <option value="<?= $kategori['id'] ?>" <?= (isset($_SESSION['old']['kategori_id']) && $_SESSION['old']['kategori_id'] === $kategori['nama']) ? 'selected' : '' ?>><?= $kategori['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($_SESSION['errors']['kategori_id'])): ?>
                        <p class="text-xs text-red-500"><?= $_SESSION['errors']['kategori_id'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="w-full flex flex-col gap-2">
                    <label for="harga">Harga</label>
                    <input type="harga" id="harga" name="harga" placeholder="10000" value="<?= $_SESSION['old']['harga'] ?? '' ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300">
                    <?php if (isset($_SESSION['errors']['harga'])): ?>
                        <p class="text-xs text-red-500"><?= $_SESSION['errors']['harga'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="w-full flex flex-col gap-2 col-span-2">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" placeholder="Ini adalah menu..." class="resize-none h-30 w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300"><?= $_SESSION['old']['deskripsi'] ?? '' ?></textarea>
                    <?php if (isset($_SESSION['errors']['deskripsi'])): ?>
                        <p class="text-xs text-red-500"><?= $_SESSION['errors']['deskripsi'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="w-full flex flex-col gap-2">
                    <label for="ketersediaan">Ketersediaan</label>
                    <select name="ketersediaan" id="ketersediaan" class="w-full border border-gray-200 rounded-md py-2 pl-2">
                        <option value="tersedia" <?= (isset($_SESSION['old']['ketersediaan']) && $_SESSION['old']['ketersediaan'] === 'tersedia') ? 'selected' : '' ?>>Tersedia</option>
                        <option value="habis" <?= (isset($_SESSION['old']['ketersediaan']) && $_SESSION['old']['ketersediaan'] === 'habis') ? 'selected' : '' ?>>Habis</option>
                    </select>
                    <?php if (isset($_SESSION['errors']['ketersediaan'])): ?>
                        <p class="text-xs text-red-500"><?= $_SESSION['errors']['ketersediaan'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <button type="submit" class="form-btn w-full py-2 cursor-pointer bg-lime-600 text-gray-50 rounded-lg font-semibold"><span class="form-btntext">Tambah</span></button>
        </form>
    </div>
    <!-- End Tambah menu -->
</div>

<script>
    const input = document.getElementById('menu-image');
    const preview = document.getElementById('image-preview');
    const uploadContent = document.getElementById('upload-content');

    input.addEventListener('change', function() {
        const file = this.files[0];

        if (!file) {
            preview.src = '';
            preview.classList.add('hidden');
            uploadContent.classList.remove('hidden');
            return;
        }

        // Validasi ukuran
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2MB.');
            this.value = '';
            return;
        }

        // Preview
        const imageUrl = URL.createObjectURL(file);

        preview.src = imageUrl;
        preview.classList.remove('hidden');
        uploadContent.classList.add('hidden');
    });
</script>