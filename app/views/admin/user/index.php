<div class="w-full flex justify-between items-center h-fit gap-4">
    <div class="w-8/10">
        <form action="<?= Helpers::url('/admin/user') ?>" method="GET" class="relative w-full">
            <button
                type="submit"
                class="flex items-center justify-center cursor-pointer absolute top-1/2 -translate-y-1/2 left-5 text-xl text-gray-600">
                <i class="bx bx-search"></i>
            </button>
            <input
                type="search"
                name="search"
                class="w-full py-2 border-2 border-gray-200 rounded-lg pl-12 focus:outline-lime-600"
                placeholder="Cari user..."
                value="<?= htmlspecialchars($keyword ?? '') ?>"
                autocomplete="off"
                spellcheck="false" />
            <input
                type="hidden"
                name="status"
                value="<?= htmlspecialchars($status ?? 'aktif') ?>">
        </form>
    </div>

    <div class="flex-1 h-full">
        <a
            href="<?= Helpers::url('/admin/user') ?>"
            class="flex h-full w-full bg-gray-50 border border-gray-600 rounded-lg text-gray-600 items-center justify-center font-medium gap-2 text-sm">Hapus Filter</a>
    </div>
</div>

<!-- Filter -->
<div class="flex gap-4">
    <a
        href="<?= Helpers::url('/admin/user?status=aktif&search=' . urlencode($keyword ?? '')) ?>"
        class="text-sm w-fit h-fit px-6 py-1 rounded-full border <?= $status === 'aktif' ? 'bg-lime-50 text-lime-600 border-lime-600' : 'text-gray-600 border-gray-600' ?>">Aktif</a>
    <a
        href="<?= Helpers::url('/admin/user?status=nonaktif&search=' . urlencode($keyword ?? '')) ?>"
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
                        <td class="w-40 py-3 px-6">Nama</td>
                        <td class="w-52 py-3 px-6">Email</td>
                        <td class="w-30 py-3 px-6">Role</td>
                        <td class="w-40 py-3 px-6">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dataUser)) : ?>
                        <?php foreach ($dataUser as $user) : ?>
                            <tr>
                                <td class="w-14 py-3 px-6"><?= $user['id'] ?></td>
                                <td class="w-40 py-3 px-6 truncate"><?= $user['nama'] ?></td>
                                <td class="w-52 py-3 px-6 truncate">
                                    <?= $user['email'] ?>
                                </td>
                                <td class="w-30 py-3 px-6">
                                    <?php if ($user['role'] === 'admin') : ?>
                                        <span
                                            class="w-fit h-fit px-3 py-1 text-xs rounded-sm bg-orange-100 text-orange-600">Admin</span>
                                    <?php else : ?>
                                        <span class="w-fit h-fit px-3 py-1 text-xs rounded-sm bg-sky-100 text-sky-600">Kasir</span>
                                    <?php endif; ?>
                                </td>
                                <td class="w-40 py-3 px-6">
                                    <?php if (!isset($user['deleted_at'])): ?>
                                        <div class="flex gap-2">
                                            <?php if ($user['id'] !== 1 || Auth::user()['id'] == $user['id']): ?>
                                                <div>
                                                    <a href="<?= Helpers::url('/admin/user/edit/' . $user['id']) ?>"
                                                        class="cursor-pointer w-fit aspect-square p-2 bg-blue-100 text-blue-600 rounded flex items-center gap-1">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($user['id'] !== 1 && $user['id'] !== $_SESSION['user']['id']): ?>
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
                                                                Nonaktifkan User
                                                            </h2>
                                                            <p class="text-sm text-gray-600">
                                                                Apakah anda yakin ingin menonaktifkan user ini?
                                                            </p>

                                                            <div class="w-full grid grid-cols-2 gap-4">
                                                                <button
                                                                    class="modal-close w-full bg-gray-200 text-gray-950 font-bold py-2 text-sm rounded-lg cursor-pointer">
                                                                    Batal
                                                                </button>
                                                                <form action="<?= Helpers::url('/admin/user/nonaktifkan/' . $user['id']) ?>" method="POST" class="form">
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
                                                <?php if (Helpers::canDeleteWithin24Hours($user['created_at'])): ?>
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
                                                                    <form action="<?= Helpers::url('/admin/user/destroy/' . $user['id']) ?>" method="POST" class="form">
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
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
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
                                                        <form action="<?= Helpers::url('/admin/user/aktifkan/' . $user['id']) ?>" method="POST" class="form">
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
                            <td colspan="5" class="py-3 px-6 text-center">Tidak ada data</td>
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

    <!-- Tambah User -->
    <div class="flex-1 w-full border border-gray-200 rounded-lg p-4 flex flex-col gap-4 shadow-sm">
        <h2 class="text-lg font-semibold">Tambah User</h2>
        <form action="<?= Helpers::url('/admin/user/tambah') ?>" method="POST" class="form space-y-4 text-sm" autocomplete="off" spellcheck="false">
            <div class="flex flex-col gap-2">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" placeholder="Joseph" value="<?= $_SESSION['old']['nama'] ?? '' ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300">
                <?php if (isset($_SESSION['errors']['nama'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['nama'] ?></p>
                <?php endif; ?>
            </div>
            <div class="flex flex-col gap-2">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="contoh@rmmajujaya.com" value="<?= $_SESSION['old']['email'] ?? '' ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300">
                <?php if (isset($_SESSION['errors']['email'])): ?>
                    <p class="text-xs text-red-500"><?= $_SESSION['errors']['email'] ?></p>
                <?php endif; ?>
            </div>
            <div class="gap-4 grid grid-cols-2">
                <div class="w-full flex flex-col gap-2">
                    <label for="role">Role</label>
                    <select name="role" id="role" class="w-full border border-gray-200 rounded-md py-2 pl-2">
                        <option value="" <?= (isset($_SESSION['old']['role']) && $_SESSION['old']['role'] === '') ? 'selected' : '' ?>>-- Pilih Role --</option>
                        <option value="admin" <?= (isset($_SESSION['old']['role']) && $_SESSION['old']['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="kasir" <?= (isset($_SESSION['old']['role']) && $_SESSION['old']['role'] === 'kasir') ? 'selected' : '' ?>>Kasir</option>
                    </select>
                    <?php if (isset($_SESSION['errors']['role'])): ?>
                        <p class="text-xs text-red-500"><?= $_SESSION['errors']['role'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="w-full flex flex-col gap-2">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="password123" value="<?= $_SESSION['old']['password'] ?? '' ?>" class="w-full border border-gray-200 rounded-md py-2 pl-2 placeholder:text-gray-300">
                    <?php if (isset($_SESSION['errors']['password'])): ?>
                        <p class="text-xs text-red-500"><?= $_SESSION['errors']['password'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <button type="submit" class="form-btn w-full py-2 cursor-pointer bg-lime-600 text-gray-50 rounded-lg font-semibold"><span class="form-btntext">Tambah</span></button>
        </form>
    </div>
    <!-- End Tambah User -->
</div>