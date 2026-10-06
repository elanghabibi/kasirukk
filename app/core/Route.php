<?php 

class Route {
    public function run() {
        $router = new App();
        
        $router->setDefaultController("HomeController");
        $router->setDefaultMethod("index");

        $router->get("/", ["HomeController", "index"], ['auth']);
     
        // Admin
        $router->get("/admin", ["Admin/AdminBerandaController", "index"], ['auth', "role:admin"]);

            // Kelola User
            $router->get("/admin/user", ["Admin/AdminUserController", "index"], ['auth', "role:admin"]);
            $router->post("/admin/user/tambah", ["Admin/AdminUserController", "store"], ['auth', "role:admin"]);

            $router->get("/admin/user/edit/{id}", ["Admin/AdminUserController", "edit"], ['auth', "role:admin"]);
            $router->post("/admin/user/edit/{id}", ["Admin/AdminUserController", "update"], ['auth', "role:admin"]);
            
            $router->post("/admin/user/destroy/{id}", ["Admin/AdminUserController", "destroy"], ['auth', "role:admin"]);
            $router->post("/admin/user/nonaktifkan/{id}", ["Admin/AdminUserController", "nonaktifkan"], ['auth', "role:admin"]);
            $router->post("/admin/user/aktifkan/{id}", ["Admin/AdminUserController", "aktifkan"], ['auth', "role:admin"]);
            
            // Kelola Menu
            $router->get("/admin/menu", ["Admin/AdminMenuController", "index"], ['auth', "role:admin"]);
            
            $router->post("/admin/menu/tambah", ["Admin/AdminMenuController", "store"], ['auth', "role:admin"]);
            
            $router->get("/admin/menu/edit/{id}", ["Admin/AdminMenuController", "edit"], ['auth', "role:admin"]);
            $router->post("/admin/menu/edit/{id}", ["Admin/AdminMenuController", "update"], ['auth', "role:admin"]);
            
            $router->post("/admin/menu/destroy/{id}", ["Admin/AdminMenuController", "destroy"], ['auth', "role:admin"]);
            
            $router->post("/admin/menu/nonaktifkan/{id}", ["Admin/AdminMenuController", "nonaktifkan"], ['auth', "role:admin"]);
            $router->post("/admin/menu/aktifkan/{id}", ["Admin/AdminMenuController", "aktifkan"], ['auth', "role:admin"]);
            
            // Kelola Kategori
            $router->get("/admin/kategori", ["Admin/AdminKategoriController", "index"], ['auth', "role:admin"]);
            
            $router->post("/admin/kategori/tambah", ["Admin/AdminKategoriController", "store"], ['auth', "role:admin"]);
            
            $router->get("/admin/kategori/edit/{id}", ["Admin/AdminKategoriController", "edit"], ['auth', "role:admin"]);
            $router->post("/admin/kategori/edit/{id}", ["Admin/AdminKategoriController", "update"], ['auth', "role:admin"]);
            
            $router->post("/admin/kategori/destroy/{id}", ["Admin/AdminKategoriController", "destroy"], ['auth', "role:admin"]);

            $router->post("/admin/kategori/nonaktifkan/{id}", ["Admin/AdminKategoriController", "nonaktifkan"], ['auth', "role:admin"]);
            $router->post("/admin/kategori/aktifkan/{id}", ["Admin/AdminKategoriController", "aktifkan"], ['auth', "role:admin"]);
            
            // Kelola Transaksi
            $router->get("/admin/riwayat-transaksi", ["Admin/AdminTransaksiController", "index"], ['auth', "role:admin"]);
            
            $router->get("/riwayat-transaksi/struk/{id}", ["Admin/AdminTransaksiController", "struk"], ['auth']);
            
            // $router->post("/admin/riwayat-transaksi/tambah", ["Admin/AdminTransaksiController", "store"], ['auth', "role:admin"]);
            
            // $router->get("/admin/riwayat-transaksi/edit/{id}", ["Admin/AdminTransaksiController", "edit"], ['auth', "role:admin"]);
            // $router->post("/admin/riwayat-transaksi/edit/{id}", ["Admin/AdminTransaksiController", "update"], ['auth', "role:admin"]);
            
            // $router->post("/admin/riwayat-transaksi/destroy/{id}", ["Admin/AdminTransaksiController", "destroy"], ['auth', "role:admin"]);

            // $router->post("/admin/riwayat-transaksi/nonaktifkan/{id}", ["Admin/AdminTransaksiController", "nonaktifkan"], ['auth', "role:admin"]);
            // $router->post("/admin/riwayat-transaksi/aktifkan/{id}", ["Admin/AdminTransaksiController", "aktifkan"], ['auth', "role:admin"]);

        // End Admin

        // Kasir

        $router->get("/kasir", ["Kasir/KasirBerandaController", "index"], ['auth', "role:kasir"]);

            $router->get("/kasir/transaksi", ["Kasir/KasirTransaksiController", "index"], ['auth', "role:kasir"]);
            $router->post("/kasir/transaksi/store", ["Kasir/KasirTransaksiController", "store"], ['auth', "role:kasir"]);

            $router->post("/kasir/transaksi/cart/add", ["Kasir/KasirTransaksiController", "addToCart"], ['auth', "role:kasir"]);
            $router->post("/kasir/transaksi/cart/update", ["Kasir/KasirTransaksiController", "updateCart"], ['auth', "role:kasir"]);
            $router->post("/kasir/transaksi/cart/remove", ["Kasir/KasirTransaksiController", "removeFromCart"], ['auth', "role:kasir"]);
            $router->post("/kasir/transaksi/cart/clear", ["Kasir/KasirTransaksiController", "clearCart"], ['auth', "role:kasir"]);

            $router->get("/kasir/transaksi/pembayaran", ["Kasir/KasirTransaksiController", "pembayaran"], ['auth', "role:kasir"]);

        // End Kasir

        // Auth
        $router->get("/login", ['AuthController', 'formLogin'], ['guest']);
        $router->post("/login", ['AuthController', 'login'], ['guest']);
        
        $router->post("/logout", ['AuthController', 'logout'], ['auth']);

        $router->run();
    }
}




?>