<?php

class Auth {
    public static function user() {
        $user = Controller::model("User")->find($_SESSION['user']['id']);

        if (isset($_SESSION['user'])) return $user;
    }
}


?>