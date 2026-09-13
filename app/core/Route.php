<?php 

class Route {
    public function run() {
        $router = new App();
        
        $router->setDefaultConstroller("HomeController");
        $router->setDefaultMethod("index");

        $router->get("/", ["HomeController", "index"], ['auth']);
        
        $router->get("/kasir", ["KasirBerandaController", "index"], ['auth', "role:kasir"]);
     
        // Admin
        $router->get("/admin", ["AdminBerandaController", "index"], ['auth', "role:admin"]);

            // Kelola User
            $router->get("/admin/user", ["AdminUserController", "index"], ['auth', "role:admin"]);
            $router->post("/admin/user/tambah", ["AdminUserController", "store"], ['auth', "role:admin"]);

            $router->get("/admin/user/edit/{id}", ["AdminUserController", "edit"], ['auth', "role:admin"]);
            $router->post("/admin/user/edit/{id}", ["AdminUserController", "update"], ['auth', "role:admin"]);
            
            $router->post("/admin/user/destroy/{id}", ["AdminUserController", "destroy"], ['auth', "role:admin"]);
            $router->post("/admin/user/nonaktifkan/{id}", ["AdminUserController", "nonaktifkan"], ['auth', "role:admin"]);
            $router->post("/admin/user/aktifkan/{id}", ["AdminUserController", "aktifkan"], ['auth', "role:admin"]);
            
            // Kelola Menu
            $router->get("/admin/menu", ["AdminMenuController", "index"], ['auth', "role:admin"]);
            
            $router->post("/admin/menu/tambah", ["AdminMenuController", "store"], ['auth', "role:admin"]);
            
            $router->get("/admin/menu/edit/{id}", ["AdminMenuController", "edit"], ['auth', "role:admin"]);
            $router->post("/admin/menu/edit/{id}", ["AdminMenuController", "update"], ['auth', "role:admin"]);
            
            $router->post("/admin/menu/destroy/{id}", ["AdminMenuController", "destroy"], ['auth', "role:admin"]);

            $router->post("/admin/menu/nonaktifkan/{id}", ["AdminMenuController", "nonaktifkan"], ['auth', "role:admin"]);
            $router->post("/admin/menu/aktifkan/{id}", ["AdminMenuController", "aktifkan"], ['auth', "role:admin"]);

        // Auth
        $router->get("/login", ['AuthController', 'formLogin'], ['guest']);
        $router->post("/login", ['AuthController', 'login'], ['guest']);
        
        $router->post("/logout", ['AuthController', 'logout'], ['auth']);

        $router->run();
    }
}




?>