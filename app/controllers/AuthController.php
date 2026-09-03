<?php

class AuthController extends Controller
{
    public function formLogin()
    {
        $data['title'] = "Login";
        $this->view("auth/login", $data, "guest");        
    }

    public function formRegister()
    {
        $data['title'] = "Register";
        $this->view("auth/register", $data);
    }

    public function login()
    {
        $errors = Validator::validate($_POST, [
            "email" => "required",
            "password" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: " . BASEURL . "/login");
            exit;
        }
        
        $user = $this->model("User")->where("email", $_POST['email']);
        
        if (!$user) {
            Flasher::setFlash("Email atau password salah", "error", "Gagal");
            header("Location: " . BASEURL . "/login");
            exit;
        }
        
        if (!password_verify($_POST['password'], $user[0]['password'])) {
            Flasher::setFlash("Password salah", "error", "Gagal");
            $_SESSION['old'] = $_POST;
            header("Location: " . BASEURL . "/login");
            exit;
        }

        $_SESSION['user'] = [
            'id' => $user[0]['id'],
            'nama' => $user[0]['nama'],
            'email' => $user[0]['email'],
            'role' => $user[0]['role'],
        ];

        Flasher::setFlash("Selamat datang, " . $user[0]['nama'], "success", "Berhasil Masuk!");
        // if ($user[0]['role'] === 'admin') {
        //     header("Location: " . BASEURL . "/admin");
        //     exit;
        // } else {
        //     header("Location: " . BASEURL . "/kasir");
        //     exit;
        // }
        header("Location: " . BASEURL . "/" . $user[0]['role']);
        exit;
    }

    public function register()
    {
        $errors = Validator::validate($_POST, [
            "name" => "required",
            "email" => "required",
            "password" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: " . BASEURL . "/register");
            exit;
        }

        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $_POST['role'] = "user";

        if ($this->model("User")->store($_POST) > 0) {
            Flasher::setFlash("Register berhasil! Silakan login");
            header("Location: " . BASEURL . "/login");
        }
    }

    public function logout()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            Flasher::setFlash("Anda telah keluar", "success", "Berhasil");
            header("Location: " . BASEURL . "/login");
        }
    }
}



?>