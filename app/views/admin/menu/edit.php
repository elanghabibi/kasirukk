<!-- Edit Menu -->
<div class="w-full gap-4 flex flex-col">
    <form action="<?= Helpers::url('/admin/menu/edit/' . $menu['id']) ?>" method="POST" enctype="multipart/form-data" class="form space-y-4" autocomplete="off" spellcheck="false">
        <div class="w-full flex flex-col gap-2">
            <label>
                Foto Menu
            </label>

            <!-- Upload Area -->
            <label
                for="menu-image"
                id="upload-area"
                class="relative flex flex-col items-center justify-center
               w-56 h-34
               border-2 border-dashed border-gray-300
               rounded-xl
               cursor-pointer
               bg-white
               hover:bg-gray-50
               transition overflow-hidden">

                <!-- Default Content -->
                <div id="upload-content" class="hidden flex flex-col items-center">
                    <!-- Camera Icon -->
                    <i class="bx bx-camera text-2xl text-gray-600"></i>

                    <span class="text-sm text-gray-500">
                        Klik untuk mengunggah foto
                    </span>
                </div>

                <!-- Image Preview -->
                <img
                    id="image-preview"
                    src="<?= Helpers::url($menu['foto']) ?>"
                    alt="Preview"
                    class="absolute inset-0 w-full h-full object-cover">

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
            <input type="text" id="nama" name="nama" placeholder="Mie Ayam" value="<?= $_SESSION['old']['nama'] ?? $menu['nama'] ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300">
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
                        <option value="<?= $kategori['id'] ?>" <?= $kategori['nama'] === $menu['nama_kategori'] ? 'selected' : '' ?>><?= $kategori['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($_SESSION['errors']['kategori_id'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['kategori_id'] ?></p>
                <?php endif; ?>
            </div>

            <div class="w-full flex flex-col gap-2">
                <label for="harga">Harga</label>
                <input type="harga" id="harga" name="harga" placeholder="10000" value="<?= $_SESSION['old']['harga'] ?? $menu['harga'] ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300">
                <?php if (isset($_SESSION['errors']['harga'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['harga'] ?></p>
                <?php endif; ?>
            </div>

            <div class="w-full flex flex-col gap-2 col-span-2">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" placeholder="Ini adalah menu..." class="resize-none h-30 w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300"><?= $_SESSION['old']['deskripsi'] ?? $menu['deskripsi'] ?></textarea>
                <?php if (isset($_SESSION['errors']['deskripsi'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['deskripsi'] ?></p>
                <?php endif; ?>
            </div>

            <div class="w-full flex flex-col gap-2">
                <label for="ketersediaan">Ketersediaan</label>
                <select name="ketersediaan" id="ketersediaan" class="w-full border border-gray-200 rounded-md py-2 pl-2">
                    <option value="tersedia" <?= $menu['ketersediaan'] === "tersedia" ? 'selected' : '' ?>>Tersedia</option>
                    <option value="habis" <?= $menu['ketersediaan'] === "habis" ? 'selected' : '' ?>>Habis</option>
                </select>
                <?php if (isset($_SESSION['errors']['ketersediaan'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['ketersediaan'] ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="w-full flex gap-4">
            <button type="submit" class="form-btn w-fit bg-lime-600 text-gray-50 px-6 py-2 rounded-lg cursor-pointer"><span class="form-btntext">Edit</span></button>
            <a href="<?= Helpers::url("/admin/menu") ?>" class="w-fit bg-gray-100 text-gray-600 px-6 py-2 rounded-lg cursor-pointer">Batal</a>
        </div>
    </form>
</div>
<!-- End Edit User -->


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