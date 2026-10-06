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
            ->select('menu.*, kategori.nama AS nama_kategori')
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

        $menuIds = array_column($cart, 'menu_id');

        $menus = $this
            ->whereIn('id', $menuIds)
            ->get();

        $result = [];

        foreach ($menus as $menu) {

            foreach ($cart as $item) {

                if ((int) $item['menu_id'] === (int) $menu['id']) {

                    $result[] = [
                        'menu_id' => $menu['id'],
                        'nama_menu' => $menu['nama'],
                        'harga' => $menu['harga'],
                        'foto' => $menu['foto'],
                        'ketersediaan' => $menu['ketersediaan'],
                        'deleted_at' => $menu['deleted_at'],
                        'qty' => $item['qty'],
                        'subtotal' => $menu['harga'] * $item['qty']
                    ];

                    break;
                }
            }
        }

        return $result;
    }
}
