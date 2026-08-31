<div>
    <h1>Halo, <?= $_SESSION['user']['nama'] ?></h1>
    <form action="<?= BASEURL . "/logout" ?>" method="POST">
        <button type="submit">Logout</button>
    </form>

</div>