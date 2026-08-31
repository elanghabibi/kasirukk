<?php

class User extends Model {
    protected $table = "users";
    protected $fillable = [
        "nama",
        "email",
        "password",
        "role"
    ];

}


?>