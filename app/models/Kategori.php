<?php

class Kategori extends Model {
    protected $table = "kategori";
    protected $fillable = [
        "nama",
    ];
    protected $softDelete = true;
}


?>