<?php

class AdminUserController extends Controller {
    public function index() {        
        $data["title"] = "Kelola User";
        
        // $user = Auth::user();
        $dataUser = $this->model("User")->latest();
        $data["dataUser"] = $dataUser;

        $this->view("admin/user/index", $data, "admin");
    }

    public function destroy($id = null) {
        if ($id) {
            $this->model("User")->destroy($id);
        }
        Flasher::setFlash("User berhasil dihapus", "success", "Berhasil Dihapus!");
        $this->redirect("/admin/user");
    }
}
// LANJUTIN EDIT USER DLL



?>