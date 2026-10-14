<?php

class Pengaturan extends Model
{
    protected $table = "pengaturan";
    protected $fillable = [
        "nama_restoran",
        "logo",
        "deskripsi",
        "alamat",
        "no_telepon",
        "email",
        "pesan_struk"
    ];
    protected $softDelete = false;

}
