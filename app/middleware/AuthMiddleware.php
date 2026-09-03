<?php

class AuthMiddleware extends Middleware {
    public function handle($param = null)
    {
        if (!isset($_SESSION['user'])) {
            Flasher::setFlash("Anda harus login terlebih dahulu", "error", "Gagal");
            header("Location: ".BASEURL. "/login");
            exit;
        }
    }
}

?>