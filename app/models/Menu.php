<?php

class Menu extends Model
{
    protected $table = "menu";
    protected $fillable = [
        "kategori_id",
        "nama",
        "harga",
        "foto",
        "deskripsi",
        "ketersediaan",
        "deleted_at"
    ];

    protected $softDelete = true;

    public function withKategori()
    {
        return $this
            ->select('menu.*, kategori.nama AS nama_kategori, kategori.slug AS slug_kategori')
            ->join(
                'kategori',
                'kategori.id = menu.kategori_id'
            );
    }

    public function hasTransaksi($id)
    {
        $this->db->query("
        SELECT id
        FROM detail_transaksi
        WHERE menu_id = :kategori_id
        LIMIT 1
    ");

        $this->db->bind(':kategori_id', $id);

        return $this->db->single();
    }

    public function getCartItems($cart)
    {
        if (empty($cart)) {
            return [];
        }

        // 1. Ambil semua menu_id dari keranjang
        $menuIds = array_column($cart, 'menu_id');

        // 2. Query data menu dari database
        $rawMenus = $this
            ->whereIn('id', $menuIds)
            ->get();

        $menus = array_column($rawMenus, null, 'id');

        $result = [];

        // 4. Loop berdasarkan $cart agar URUTAN TERBARU tetep terjaga
        foreach ($cart as $item) {
            $menuId = $item['menu_id'];

            // Cek jika menu ada di hasil query DB
            if (isset($menus[$menuId])) {
                $menu = $menus[$menuId];

                // Sesuaikan sintaks ($menu['nama'] jika array, atau $menu->nama jika object)
                $harga = is_object($menu) ? $menu->harga : $menu['harga'];

                $result[$menuId] = [
                    'menu_id'      => is_object($menu) ? $menu->id : $menu['id'],
                    'nama_menu'    => is_object($menu) ? $menu->nama : $menu['nama'],
                    'harga'        => $harga,
                    'foto'         => is_object($menu) ? $menu->foto : $menu['foto'],
                    'ketersediaan' => is_object($menu) ? $menu->ketersediaan : $menu['ketersediaan'],
                    'deleted_at'   => is_object($menu) ? $menu->deleted_at : $menu['deleted_at'],
                    'qty'          => $item['qty'],
                    'subtotal'     => $harga * $item['qty']
                ];
            }
        }

        return $result;
    }
}
