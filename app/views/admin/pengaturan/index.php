<!-- Edit Pengaturan -->
<div class="w-full gap-4 flex flex-col">
    <div class="flex max-md:flex-col max-md:gap-2 justify-between items-start w-full">
        <h2 class="text-2xl font-semibold">Edit Pengaturan</h2>
        <p class="text-xs text-gray-600">Terakhir diupdate: <?= date('d/m/Y, h:i:s', strtotime(Settings::set()[0]['updated_at'])) ?></p>
    </div>
    <form action="<?= Helpers::url('/admin/pengaturan/update') ?>" method="POST" enctype="multipart/form-data" class="form space-y-4" autocomplete="off" spellcheck="false">
        <div class="w-full flex flex-col gap-1">
            <label class="text-sm">
                Logo
            </label>

            <!-- Upload Area -->
            <label
                for="pengaturan-image"
                id="upload-area"
                class="relative flex flex-col items-center justify-center
               w-56 aspect-square
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
                        Klik untuk mengunggah logo
                    </span>
                </div>

                <!-- Image Preview -->
                <img
                    id="image-preview"
                    src="<?= Helpers::url(Settings::set()[0]['logo']) ?>"
                    alt="Preview"
                    class="absolute inset-0 w-full h-full object-cover">

                <!-- Hidden Input -->
                <input
                    type="file"
                    name="logo"
                    id="pengaturan-image"
                    accept="image/jpeg,image/png,image/webp"
                    class="hidden">
            </label>

            <p class="mt-1 text-[10px] text-gray-400">
                JPG, PNG atau WebP. Maksimal 2MB.
            </p>

            <?php if (isset($_SESSION['errors']['logo'])): ?>
                <p class="text-xs text-red-500"><?= $_SESSION['errors']['logo'] ?></p>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4">
            <div class="w-full flex flex-col gap-1">
                <label class="text-sm" for="nama_restoran">
                    Nama Restoran
                </label>

                <input type="text" id="nama_restoran" name="nama_restoran" value="<?= $_SESSION['old']['nama_restoran'] ?? Settings::set()[0]['nama_restoran'] ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300 focus:outline-lime-600 ">

                <?php if (isset($_SESSION['errors']['nama_restoran'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['nama_restoran'] ?></p>
                <?php endif; ?>
            </div>

            <div class="w-full flex flex-col gap-1">
                <label class="text-sm" for="deskripsi">
                    Deskripsi
                </label>

                <input type="text" id="deskripsi" name="deskripsi" value="<?= $_SESSION['old']['deskripsi'] ?? Settings::set()[0]['deskripsi'] ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300 focus:outline-lime-600 ">

                <?php if (isset($_SESSION['errors']['deskripsi'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['deskripsi'] ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4">
            <div class="w-full flex flex-col gap-1">
                <label class="text-sm" for="email">
                    Email
                </label>

                <input type="text" id="email" name="email" value="<?= $_SESSION['old']['email'] ?? Settings::set()[0]['email'] ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300 focus:outline-lime-600 ">

                <?php if (isset($_SESSION['errors']['email'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="w-full flex flex-col gap-1">
                <label class="text-sm" for="no_telepon">
                    No Telepon
                </label>

                <input type="text" id="no_telepon" name="no_telepon" value="<?= $_SESSION['old']['no_telepon'] ?? Settings::set()[0]['no_telepon'] ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300 focus:outline-lime-600 ">

                <?php if (isset($_SESSION['errors']['no_telepon'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['no_telepon'] ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4">
            <div class="w-full flex flex-col gap-1">
                <label class="text-sm" for="alamat">
                    Alamat
                </label>

                <textarea type="text" id="alamat" name="alamat" class="resize-none h-40 w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300 focus:outline-lime-600 "><?= $_SESSION['old']['alamat'] ?? Settings::set()[0]['alamat'] ?></textarea>

                <?php if (isset($_SESSION['errors']['alamat'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['alamat'] ?></p>
                <?php endif; ?>
            </div>

            <div class="w-full flex flex-col gap-1">
                <label class="text-sm" for="pesan_struk">
                    Pesan Struk
                </label>

                <textarea type="text" id="pesan_struk" name="pesan_struk" class="resize-none h-40 w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300 focus:outline-lime-600 "><?= $_SESSION['old']['pesan_struk'] ?? Settings::set()[0]['pesan_struk'] ?></textarea>

                <?php if (isset($_SESSION['errors']['pesan_struk'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['pesan_struk'] ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="w-full flex gap-4">
            <button type="submit" class="form-btn w-fit bg-lime-600 text-gray-50 px-6 py-2 rounded-lg cursor-pointer"><span class="form-btntext">Edit</span></button>
        </div>
    </form>
</div>
<!-- End Pengaturan -->


<script>
    const input = document.getElementById('pengaturan-image');
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