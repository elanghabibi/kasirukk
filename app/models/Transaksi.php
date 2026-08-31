<?php

class Transaksi extends Model {
    protected $table = "transaksi";
    protected $fillable = [
        "user_id",
        "nama_pelanggan",
        "subtotal",
        "pajak",
        "total",
        "tipe_order",
        "no_meja",
        "keterangan",
        "tanggal_transaksi",
        "tunai",
        "kembalian"
    ];

}


?>