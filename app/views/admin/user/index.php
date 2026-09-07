<div class="w-full flex justify-between items-center h-fit gap-4">
    <div class="w-8/10">
    <form action="" class="relative w-full">
        <button
        type="submit"
        class="flex items-center justify-center cursor-pointer absolute top-1/2 -translate-y-1/2 left-5 text-xl text-gray-600"
        >
        <i class="bx bx-search"></i>
        </button>
        <input
        type="search"
        class="w-full py-2 border-2 border-gray-200 rounded-lg pl-12 focus:outline-lime-600"
        placeholder="Cari user..."
        />
    </form>
    </div>

    <div class="flex-1 h-full">
    <a
        href=""
        class="flex h-full w-full bg-lime-50 border border-lime-600 rounded-lg text-lime-600 items-center justify-center font-medium gap-2 text-sm"
        ><i class="bx bx-arrow-to-bottom text-xl"></i> Export User</a
    >
    </div>
</div>

<!-- Filter -->
<div class="flex gap-4">
    <a
    href=""
    class="text-sm w-fit h-fit px-6 py-1 rounded-full border bg-lime-50 text-lime-600 border-lime-600"
    >Aktif</a
    >
    <a
    href=""
    class="text-sm w-fit h-fit px-6 py-1 rounded-full border text-gray-600 border-gray-600"
    >Nonaktif</a
    >
</div>
<!-- End Filter -->

<div class="flex gap-4">
    <!-- Table -->
    <div class="w-7/10 border border-gray-200 overflow-hidden rounded-lg">
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
            <?php foreach ($dataUser as $user) : ?>
            <tr>
                <td class="w-14 py-3 px-6"><?= $user['id'] ?></td>
                <td class="w-40 py-3 px-6 truncate"><?= $user['nama'] ?></td>
                <td class="w-52 py-3 px-6 truncate">
                    <?= $user['email'] ?>
                </td>
                <td class="w-30 py-3 px-6">
                    <?php if($user['role'] === 'admin') : ?>
                    <span
                    class="w-fit h-fit px-3 py-1 text-xs rounded-sm bg-orange-100 text-orange-600"
                    >Admin</span
                    >
                    <?php else : ?>
                    <span class="w-fit h-fit px-3 py-1 text-xs rounded-sm bg-sky-100 text-sky-600">Kasir</span>
                    <?php endif; ?>
                </td>
                <td class="w-40 py-3 px-6">
                    <div class="flex gap-2">
                        <div class="modal">
                            <a href=""
                            class="modal-btn cursor-pointer w-fit aspect-square p-2 bg-blue-100 text-blue-600 rounded flex items-center gap-1"
                            >
                            <i class="bx bx-edit"></i>
                            </a>
                        </div>

                        <?php if ($user['id'] !== 1): ?>
                        <!-- Button Nonaktif -->
                        <div class="modal">
                            <button
                            class="modal-btn cursor-pointer w-fit aspect-square p-2 bg-gray-100 text-gray-600 rounded flex items-center gap-1"
                            >
                            <i class="bx bx-block"></i>
                            </button>

                            <div
                            class="modal-panel opacity-0 pointer-events-none absolute top-0 left-0 w-full h-screen flex justify-center items-center bg-gray-950/10"
                            style="z-index: 200"
                            >
                            <div
                                class="bg-gray-50 p-6 text-center rounded-xl shadow-lg shadow-gray-600/5 w-80 max-md:w-7/10 flex flex-col gap-6"
                            >
                                <i class="bx bx-block text-red-500 text-5xl"></i>
                                <h2 class="text-2xl font-bold leading-6">
                                Nonaktifkan User
                                </h2>
                                <p class="text-sm text-gray-600">
                                Apakah anda yakin ingin menonaktifkan user ini?
                                </p>

                                <div class="w-full grid grid-cols-2 gap-4">
                                <button
                                    class="modal-close w-full bg-gray-200 text-gray-950 font-bold py-2 text-sm rounded-lg cursor-pointer"
                                >
                                    Batal
                                </button>
                                <form action="" class="form">
                                    <button
                                    type="submit"
                                    class="form-btn w-full bg-red-500 text-gray-50 font-bold py-2 text-sm rounded-lg cursor-pointer"
                                    >
                                    <span class="form-btntext"
                                        >Ya, nonaktifkan</span
                                    >
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
                            class="modal-btn cursor-pointer w-fit aspect-square p-2 bg-red-100 text-red-600 rounded flex items-center gap-1"
                            >
                            <i class="bx bx-trash"></i>
                            </button>

                            <div
                            class="modal-panel opacity-0 pointer-events-none absolute top-0 left-0 w-full h-screen flex justify-center items-center bg-gray-950/10"
                            style="z-index: 200"
                            >
                            <div
                                class="bg-gray-50 p-6 text-center rounded-xl shadow-lg shadow-gray-600/5 w-80 max-md:w-7/10 flex flex-col gap-6"
                            >
                                <i
                                class="bx bx-alert-triangle text-red-500 text-5xl"
                                ></i>
                                <h2 class="text-2xl font-bold leading-6">
                                Hapus Data
                                </h2>
                                <p class="text-sm text-gray-600">
                                Apakah anda yakin ingin menghapus?
                                </p>

                                <div class="w-full grid grid-cols-2 gap-4">
                                <button
                                    class="modal-close w-full bg-gray-200 text-gray-950 font-bold py-2 text-sm rounded-lg cursor-pointer"
                                >
                                    Batal
                                </button>
                                <form action="<?= Helpers::url('/admin/user/destroy/' . $user['id']) ?>" method="POST" class="form">
                                    <button
                                    type="submit"
                                    class="form-btn w-full bg-red-500 text-gray-50 font-bold py-2 text-sm rounded-lg cursor-pointer"
                                    >
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
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    </div>
    <!-- End Table -->

    <!-- Tambah User -->
    <div class=""></div>
    <!-- End Tambah User -->
</div>