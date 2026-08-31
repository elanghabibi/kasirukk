<?php

class GuestMiddleware extends Middleware {
    public function handle($param = null) {
        if (isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/");
            exit;
        }
    }
}


?>