<!-- Edit Kategori -->
<div class="w-full gap-4 flex flex-col">
    <form action="<?= Helpers::url('/admin/kategori/edit/' . $kategori['id']) ?>" method="POST" class="form space-y-4" autocomplete="off" spellcheck="false">
        <div class="flex flex-col gap-2">
            <label for="nama">Nama Kategori</label>
            <input type="text" id="nama" name="nama" placeholder="Mie Ayam" value="<?= $_SESSION['old']['nama'] ?? $kategori['nama'] ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300">
            <?php if (isset($_SESSION['errors']['nama'])): ?>
                <p class="text-xs text-red-500"><?= $_SESSION['errors']['nama'] ?></p>
            <?php endif; ?>
        </div>
        <div class="w-full flex gap-4">
            <button type="submit" class="form-btn w-fit bg-lime-600 text-gray-50 px-6 py-2 rounded-lg cursor-pointer"><span class="form-btntext">Edit</span></button>
            <a href="<?= Helpers::url("/admin/kategori") ?>" class="w-fit bg-gray-100 text-gray-600 px-6 py-2 rounded-lg cursor-pointer">Batal</a>
        </div>
    </form>
</div>
<!-- End Edit Kategori -->