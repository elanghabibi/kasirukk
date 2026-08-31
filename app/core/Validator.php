<?php

class Validator {
    public static function validate($data, $rules) {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = trim($data[$field] ?? "");

            if ($rule === "required" && $value === "") {
                $errors[$field] = ucfirst($field) . " wajib diisi";
            }
        }

        return $errors;
    }
}




?>