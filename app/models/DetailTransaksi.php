<?php

class DetailTransaksi extends Model
{
    protected $table = "detail_transaksi";
    protected $fillable = [
        "transaksi_id",
        "menu_id",
        "nama_menu",
        "harga_menu",
        "qty",
        "subtotal"
    ];

}
