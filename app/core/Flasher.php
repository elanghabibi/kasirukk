<?php

class Flasher {
    public static function setFlash($message, $type = "success", $title = "Berhasil") {
        $_SESSION['flash'] = [
            "message" => $message,
            "type"=> $type,
            "title" => $title
        ];
    }

    public static function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}












?>