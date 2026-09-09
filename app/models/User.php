<?php

class User extends Model {
    protected $table = "users";
    protected $fillable = [
        "nama",
        "email",
        "password",
        "role"
    ];
    protected $softDelete = true;

    public function hasTransactions($userId)
    {
        $query = "SELECT COUNT(*) AS total
                FROM transaksi
                WHERE user_id = :user_id";

        $this->db->query($query);
        $this->db->bind(':user_id', $userId);

        $result = $this->db->single();

        return $result['total'] > 0;
    }
}


?>