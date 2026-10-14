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
            ->select('menu.*, kategori.nama AS nama_kategori, kategori.slug AS slug_kategori, kategori.deleted_at AS deleted_kategori')
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

        $rawMenus = $this
            ->select('
            menu.*,
            kategori.deleted_at AS deleted_kategori
        ')
            ->join(
                'kategori',
                'kategori.id = menu.kategori_id'
            )
            ->whereIn('id', $menuIds)
            ->get();

        $menus = array_column($rawMenus, null, 'id');

        $result = [];
        $cartBaru = [];

        foreach ($cart as $item) {
            $menuId = $item['menu_id'];

            // kalo menu nya gaada
            if (!isset($menus[$menuId])) {
                continue;
            }

            $menu = $menus[$menuId];

            if ($menu['deleted_kategori'] !== null || $menu['ketersediaan'] === "habis") {
                continue;
            }

            $harga = $menu['harga'];

            $result[$menuId] = [
                'menu_id'      => $menu['id'],
                'nama_menu'    => $menu['nama'],
                'harga'        => $harga,
                'foto'         => $menu['foto'],
                'ketersediaan' => $menu['ketersediaan'],
                'deleted_at'   => $menu['deleted_at'],
                'qty'          => $item['qty'],
                'subtotal'     => $harga * $item['qty']
            ];

            $cartBaru[] = $item;
        }

        // Perbarui session biar menu lama digati baru
        $_SESSION['cart'] = $cartBaru;

        return $result;
    }
}
