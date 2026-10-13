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

            return $this->redirect("/login");
        }

        $user = $this->model("User")->firstWhere("email", $_POST['email']);

        // var_dump($user);
        // exit;
       
        // User tidak ditemukan
        if (!$user) {
            Flasher::setFlash(
                "Email atau password salah",
                "error",
                "Gagal"
            );

            return $this->redirect("/login");
        }

        // Password salah
        if (!password_verify($_POST['password'], $user['password'])) {
            Flasher::setFlash(
                "Email atau password salah",
                "error",
                "Gagal"
            );

            $_SESSION['old'] = $_POST;

            return $this->redirect("/login");
        }

        // Login berhasil
        $_SESSION['user'] = [
            'id' => $user['id']
            // 'nama' => $user['nama'],
            // 'email' => $user['email'],
            // 'role' => $user['role'],
        ];

        Flasher::setFlash(
            "Selamat datang, " . $user['nama'],
            "success",
            "Berhasil Masuk!"
        );

        $this->redirect('/' . $user['role']);
    }

    // public function register()
    // {
    //     $errors = Validator::validate($_POST, [
    //         "name" => "required",
    //         "email" => "required",
    //         "password" => "required"
    //     ]);

    //     if (!empty($errors)) {
    //         $_SESSION['errors'] = $errors;
    //         $_SESSION['old'] = $_POST;
    //         header("Location: " . BASEURL . "/register");
    //         exit;
    //     }

    //     $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    //     $_POST['role'] = "user";

    //     if ($this->model("User")->store($_POST) > 0) {
    //         Flasher::setFlash("Register berhasil! Silakan login");
    //         $this->redirect('/login');
    //     }
    // }

    public function logout()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            Flasher::setFlash("Anda telah keluar", "success", "Berhasil");
            return $this->redirect('/login');
        }
    }
}



?>