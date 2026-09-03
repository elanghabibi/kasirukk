<?php

class GuestMiddleware extends Middleware {
    public function handle($param = null) {
        if (isset($_SESSION['user'])) {
            Flasher::setFlash("Anda sudah login", "error", "Gagal");
            header("Location: " . BASEURL . "/");
            exit;
        }
    }
}


?>