<form action="<?= Helpers::url("/admin/user/edit/$user[id]") ?>" method="POST" class="form space-y-4" autocomplete="off" spellcheck="false">
    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4">
        <div class="flex flex-col gap-2">
            <label for="nama">Nama</label>
            <input class="w-full py-2 px-3 border border-gray-200 rounded-lg" type="text" name="nama" id="nama" value="<?= $_SESSION['old']['nama'] ?? $user['nama'] ?>">
            <div class="text-red-500">
                <?= $_SESSION['errors']['nama'] ?? '' ?>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <label for="email">Email</label>
            <input class="w-full py-2 px-3 border border-gray-200 rounded-lg" type="email" name="email" id="email" value="<?= $_SESSION['old']['email'] ?? $user['email'] ?>">
            <div class="text-red-500">
                <?= $_SESSION['errors']['email'] ?? '' ?>
            </div>
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

        <div class="flex flex-col gap-2">
            <label for="password">Password</label>
            <input class="w-full py-2 px-3 border border-gray-200 rounded-lg" type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengubah password">
        </div>

        <div class="w-full flex gap-4">
            <button type="submit" class="form-btn w-fit bg-lime-600 text-gray-50 px-6 py-2 rounded-lg cursor-pointer"><span class="form-btntext">Edit</span></button>
            <a href="<?= Helpers::url("/admin/user") ?>" class="w-fit bg-gray-100 text-gray-600 px-6 py-2 rounded-lg cursor-pointer">Batal</a>
        </div>
    </div>
</form>