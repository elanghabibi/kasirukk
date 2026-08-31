<?php

class NoteController extends Controller
{
    public function store()
    {
        $errors = Validator::validate($_POST, [
            "title" => "required",
            "description" => "required"
        ]);

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/login");
            exit;
        }

        $user = Auth::user();
        $_POST['user_id'] = $user['id'];

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            $this->redirect("/");
            exit;
        }

        $imagePath = Upload::image('image');

        if ($imagePath) {
            $_POST['image'] = $imagePath;
        }

        // var_dump($_POST);
        // return;
        echo "halo";


        if ($this->model("Note")->store($_POST) > 0) {
            Flasher::setFlash("Catatan berhasil dibuat!");
            header("Location: " . BASEURL . "/");
        }
    }

    public function edit($id)
    {
        $data["note"] = $this->model("Note")->findOrFail($id);

        $this->view("note/edit", $data);
    }

    public function update($id)
    {
        $errors = Validator::validate($_POST, [
            "title" => "required",
            "description" => "required"
        ]);

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/login");
            exit;
        }

        $user = Auth::user();
        $_POST['user_id'] = $user['id'];

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;

            $this->redirect("/");
            exit;
        }

        if ($this->model("Note")->update($_POST, $id) > 0) {
            Flasher::setFlash("Catatan berhasil diedit!");
            header("Location: " . BASEURL . "/");
        }
    }

    public function destroy($id)
    {
        if ($this->model("Note")->destroy($id) > 0) {
            Flasher::setFlash("Catatan berhasil dihapus!");
            header("Location: " . BASEURL . "/");
        }
    }
}









?>