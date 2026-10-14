<div class="relative bg-white bg-[url('../img/food-doodle.png')] w-100 max-md:w-9/10 p-8 rounded-lg shadow-md">
    <div class="w-full flex flex-col gap-4 items-center">
        <div class="w-fit h-fit p-4 rounded-full bg-white shadow-md flex items-center justify-center">
        <i class="bx bx-arrow-in-left-square-half text-3xl"></i>
        </div>

        <h1 class="font-semibold text-2xl">Selamat datang!</h1>

        <form action="<?= BASEURL . "/login" ?>" method="POST" class="form w-full flex flex-col gap-4">
        <div class="w-full flex flex-col gap-2">
            <label for="email" class="text-sm w-fit">Email</label>
            <input type="email" name="email" id="email" autocomplete="email" value="<?= $_SESSION['old']['email'] ?? "" ?>" placeholder="user@rmmajujaya.com" class="bg-white focus:ring-0 focus:outline-0 border-2 border-gray-200 rounded-md py-2 pl-3 text-sm placeholder:text-sm focus:bg-white">
            <?php if (isset($_SESSION['errors']['email'])): ?>
                <p class="text-sm text-red-500"><?= $_SESSION['errors']['email'] ?></p>
            <?php endif; ?>
        </div>

        <div class="w-full flex flex-col gap-2">
            <label for="password" class="text-sm w-fit">Password</label>
            <div class="show-password relative">
            <input type="password" name="password" id="password" placeholder="password123" class="bg-white w-full password-input focus:ring-0 focus:outline-0 border-2 border-gray-200 rounded-md py-2 pl-3 text-sm placeholder:text-sm">
            <button type="button" class="cursor-pointer w-fit h-fit flex absolute top-1/2 -translate-y-1/2 right-3 text-lg text-gray-600"><i class="bx bx-eye password-btn-icon"></i></button>
            </div>
            <?php if (isset($_SESSION['errors']['password'])): ?>
                <p class="text-sm text-red-500"><?= $_SESSION['errors']['password'] ?></p>
            <?php endif; ?>
        </div>

        <button class="form-btn mt-4 w-full bg-lime-600 cursor-pointer text-white py-2 rounded-lg" type="submit">
            <span class="form-btntext">Masuk</span>
        </button>
        </form>

    </div>

</div>