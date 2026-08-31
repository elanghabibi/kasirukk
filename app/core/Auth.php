<?php

class Auth {
    public static function user() {
        if (isset($_SESSION['user'])) return $_SESSION['user'];
    }
}


?>