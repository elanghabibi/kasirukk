<?php

class AuthMiddleware extends Middleware {
    public function handle($param = null)
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ".BASEURL. "/login");
            exit;
        }
    }
}

?>