<?php

class Settings {
    public static function set() {
        $pengaturan = Controller::model("Pengaturan")->get();

        return $pengaturan;
    }
}


?>