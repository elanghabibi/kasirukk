<?php

class AdminUserController extends Controller {
    public function index()
    {
        $status = $_GET['status'] ?? 'aktif';
        $keyword = trim($_GET['search'] ?? '');

        $data["title"] = "Kelola User";

        $userModel = $this->model("User");

        if ($status === 'nonaktif') {
            $users = $userModel->onlyTrashed();
        } else {
            if ($keyword !== '') {
                $users = $userModel->search($keyword, [
                    "nama",
                    "email",
                    "role"
                ]);
            } else {
                $users = $userModel->latest();
            }
        }

        $data["dataUser"] = $users;
        $data["status"] = $status;
        $data["keyword"] = $keyword;

        $this->view("admin/user/index", $data, "admin");
    }

    public function store()
    {
        $errors = Validator::validate($_POST, [
            "nama" => "required",
            "email" => "required",
            "role" => "required",
            "password" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");
        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if ($userModel->store($_POST) > 0) {
            Flasher::setFlash("Catatan berhasil dibuat!");
            return $this->redirect("/admin/user");
        } else {
            Flasher::setFlash("Gagal membuat catatan", "error", "Gagal!");
            return $this->redirect("/admin/user");
        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");
        $data["user"] = $userModel->find($id);

        if (!$data["user"]) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/user");
        }

        $data["title"] = "Edit User";
        $this->view("admin/user/edit", $data, "admin");
    }

    public function update($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Mengedit!"
            );

            return $this->redirect("/admin/user");
        }

        $errors = Validator::validate($_POST, [
            "nama" => "required",
            "email" => "required",
            "role" => "required"
        ]);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            return $this->redirect("/admin/user/edit/$id");
        }

        $userModel = $this->model("User");

        if (!empty($_POST['password'])) {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            unset($_POST['password']);
        }

        if ($userModel->update($_POST, $id) > 0) {
            Flasher::setFlash("User berhasil diubah!");
            return $this->redirect("/admin/user");
        } else {
            Flasher::setFlash("Gagal mengubah user", "error", "Gagal!");
            return $this->redirect("/admin/user/edit/$id");
        }
    }

    public function destroy($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Menghapus!"
            );

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");

        // Cek apakah user pernah melakukan transaksi
        if ($userModel->hasTransactions($id)) {
            Flasher::setFlash(
                "User tidak dapat dihapus karena sudah memiliki riwayat transaksi",
                "error",
                "Gagal Menghapus!"
            );

            return $this->redirect("/admin/user");
        }

        // Hapus permanen
        $userModel->destroy($id);

        Flasher::setFlash(
            "User berhasil dihapus",
            "success",
            "Berhasil Dihapus!"
        );

        return $this->redirect("/admin/user");
    }

    public function nonaktifkan($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Menonaktifkan!"
            );

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");

        $result = $userModel->softDelete($id);

        if ($result > 0) {
            Flasher::setFlash(
                "User berhasil dinonaktifkan",
                "success",
                "Berhasil!"
            );
        } else {
            Flasher::setFlash(
                "User sudah dinonaktifkan atau tidak ditemukan",
                "error",
                "Gagal!"
            );
        }

        return $this->redirect("/admin/user");
    }

    public function aktifkan($id = null)
    {
        if (!$id) {
            Flasher::setFlash(
                "User tidak ditemukan",
                "error",
                "Gagal Mengaktifkan!"
            );

            return $this->redirect("/admin/user");
        }

        $userModel = $this->model("User");

        $result = $userModel->restore($id);

        if ($result > 0) {
            Flasher::setFlash(
                "User berhasil diaktifkan",
                "success",
                "Berhasil!"
            );
        } else {
            Flasher::setFlash(
                "User sudah diaktifkan atau tidak ditemukan",
                "error",
                "Gagal!"
            );
        }

        return $this->redirect("/admin/user");
    }
}


?>