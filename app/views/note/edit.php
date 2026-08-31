<div>
    <form action="<?= BASEURL . "/note/edit/" . $note['id'] ?>" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" id="">
        <input type="text" name="title" placeholder="Judul" value="<?= $_SESSION['old']['title'] ?? $note['title'] ?>">
        <?php if (isset($_SESSION['errors']['title'])): ?>
            <p><?= $_SESSION['errors']['title'] ?></p>
        <?php endif; ?>
        <textarea name="description" placeholder="Deskripsi"><?= $_SESSION['old']['description'] ?? $note['description'] ?></textarea>
        <?php if (isset($_SESSION['errors']['description'])): ?>
            <p><?= $_SESSION['errors']['description'] ?></p>
        <?php endif; ?>

        <button type="submit">Kirim</button>
    </form>
</div>