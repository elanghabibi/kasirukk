<div class="<?= $user['role'] == "kasir" ? "p-8" : "p-4" ?> w-full flex justify-center">
    <div class="w-xl">
        <form action="<?= Helpers::url("/profil/update") ?>" method="POST" enctype="multipart/form-data" autocomplete="off" spellcheck="false" class="flex flex-col w-full gap-4">
            <div class="w-full flex flex-col gap-1 items-center">
                <!-- Upload Area -->
                <label
                    for="pengaturan-image"
                    id="upload-area"
                    class="relative flex flex-col items-center justify-center
               w-56 aspect-square
               border-2 border border-gray-300
               rounded-full
               cursor-pointer
               bg-white
               hover:bg-gray-50
               transition overflow-hidden">

                    <!-- Default Content -->
                    <div id="upload-content" class="hidden flex flex-col items-center">
                        <!-- Camera Icon -->
                        <i class="bx bx-camera text-2xl text-gray-600"></i>

                        <span class="text-sm text-gray-500">
                            Klik untuk mengunggah foto_profil
                        </span>
                    </div>

                    <!-- Image Preview -->
                    <img
                        id="image-preview"
                        src="<?= Helpers::url($user['foto_profil'] ?? "/uploads/foto_profil/default.png") ?>"
                        alt="Preview"
                        class="absolute inset-0 w-full h-full object-cover">

                    <!-- Hidden Input -->
                    <input
                        type="file"
                        name="foto_profil"
                        id="pengaturan-image"
                        accept="image/jpeg,image/png,image/webp"
                        class="hidden">
                </label>

                <p class="mt-1 text-[10px] text-gray-400">
                    JPG, PNG atau WebP. Maksimal 2MB.
                </p>

                <?php if (isset($_SESSION['errors']['foto_profil'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['foto_profil'] ?></p>
                <?php endif; ?>
            </div>

            <div class="w-full flex flex-col gap-1">
                <label class="text-sm" for="nama">
                    Nama
                </label>

                <input type="text" id="nama" name="nama" value="<?= $_SESSION['old']['nama'] ?? $user['nama'] ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300 focus:outline-lime-600 ">

                <?php if (isset($_SESSION['errors']['nama'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['nama'] ?></p>
                <?php endif; ?>
            </div>

            <div class="w-full flex flex-col gap-1">
                <label class="text-sm" for="email">
                    Email
                </label>

                <input type="email" id="email" name="email" value="<?= $user['email'] ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300 focus:outline-lime-600 ">

                <?php if (isset($_SESSION['errors']['email'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="w-full flex flex-col gap-1">
                <label class="text-sm" for="password">
                    Password
                </label>

                <input type="Password" id="password" name="password" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300 focus:outline-lime-600 ">
            </div>

            <div class="flex flex-col gap-2">
                <label for="role">Role</label>
                <select class="w-full py-2 px-3 border border-gray-200 rounded-lg cursor-pointer" name="role" id="role">
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="kasir" <?= $user['role'] === 'kasir' ? 'selected' : '' ?>>Kasir</option>
                </select>
                <div class="text-red-500">
                    <?= $_SESSION['errors']['role'] ?? '' ?>
                </div>
            </div>

            <div class="w-full flex gap-4">
                <button type="submit" class="form-btn w-fit bg-lime-600 text-gray-50 px-6 py-2 rounded-lg cursor-pointer"><span class="form-btntext">Edit</span></button>
            </div>
        </form>
    </div>
</div>


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