<?php

class Upload
{
    public static function image(
        $inputName,
        $folder = 'uploads/images',
        $maxSize = 2097152, // 2MB
        $allowed = ['jpg', 'jpeg', 'png', 'webp']
    ) {
        if (!isset($_FILES[$inputName])) {
            return null;
        }

        $file = $_FILES[$inputName];

        if ($file['error'] !== 0) {
            return null;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            return null;
        }

        if ($file['size'] > $maxSize) {
            return null;
        }

        $fileName = uniqid('img_', true) . '.' . $ext;

        $publicPath = dirname(__DIR__, 2) . '/public/' . $folder;

        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0777, true);
        }

        $destination = $publicPath . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return '/' . $folder . '/' . $fileName;
    }
}
