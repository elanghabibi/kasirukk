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
}
