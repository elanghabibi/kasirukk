<?php

class Menu extends Model {
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
}


?>