<?php 

class Route {
    public function run() {
        $router = new App();
        
        $router->setDefaultConstroller("HomeController");
        $router->setDefaultMethod("index");

        $router->get("/", ["HomeController", "index"], ['auth']);
        
        $router->get("/kasir", ["KasirBerandaController", "index"], ['auth', "role:kasir"]);
     
        $router->get("/admin", ["AdminBerandaController", "index"], ['auth', "role:admin"]);

        // Auth
        $router->get("/login", ['AuthController', 'formLogin'], ['guest']);
        $router->post("/login", ['AuthController', 'login'], ['guest']);
        
        $router->post("/logout", ['AuthController', 'logout'], ['auth']);

        $router->run();
    }
}




?>