<?php

class Transaksi extends Model
{
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

    public function withDetailTransaksi()
    {
        return $this
            ->select('
            transaksi.*,
            users.nama AS kasir,
            detail_transaksi.id AS detail_id,
            detail_transaksi.menu_id,
            detail_transaksi.nama_menu,
            detail_transaksi.harga_menu,
            detail_transaksi.qty,
            detail_transaksi.subtotal
        ')
            ->join(
                'detail_transaksi',
                'detail_transaksi.transaksi_id = transaksi.id'
            )->join(
                'users',
                'users.id = transaksi.user_id'
            );
    }

    public function getWithDetails()
    {
        $data = $this
            ->withDetailTransaksi()
            ->latest();

        $result = [];

        foreach ($data as $row) {

            $id = $row['id'];

            if (!isset($result[$id])) {
                $result[$id] = [
                    'id' => $row['id'],
                    'kode_transaksi' => $row['kode_transaksi'],
                    'kasir' => $row['kasir'],
                    'nama_pelanggan' => $row['nama_pelanggan'],
                    'total' => $row['total'],
                    'tipe_order' => $row['tipe_order'],
                    'tanggal' => $row['tanggal_transaksi'],
                    'created_at' => $row['created_at'],
                    'details' => []
                ];
            }

            $result[$id]['details'][] = [
                'id' => $row['detail_id'],
                'menu_id' => $row['menu_id'],
                'nama_menu' => $row['nama_menu'],
                'harga_menu' => $row['harga_menu'],
                'qty' => $row['qty'],
                'subtotal' => $row['subtotal']
            ];
        }

        // Hitung setelah semua detail masuk
        foreach ($result as &$transaksi) {
            $transaksi['banyak_item'] = count($transaksi['details']);

            $transaksi['total_qty'] = array_sum(
                array_column($transaksi['details'], 'qty')
            );
        }

        unset($transaksi);

        return array_values($result);
    }

    public function getDetailById($id)
    {
        $data = $this
            ->withDetailTransaksi()
            ->where('id', $id)
            ->get();

        if (empty($data)) {
            return null;
        }

        $transaksi = [
            'id' => $data[0]['id'],
            'kode_transaksi' => $data[0]['kode_transaksi'],
            'kasir' => $data[0]['kasir'],
            'nama_pelanggan' => $data[0]['nama_pelanggan'],
            'total' => $data[0]['total'],
            'tipe_order' => $data[0]['tipe_order'],
            'tanggal' => $data[0]['tanggal_transaksi'],
            'created_at' => $data[0]['created_at'],
            'details' => []
        ];

        foreach ($data as $row) {
            $transaksi['details'][] = [
                'id' => $row['detail_id'],
                'menu_id' => $row['menu_id'],
                'nama_menu' => $row['nama_menu'],
                'harga_menu' => $row['harga_menu'],
                'qty' => $row['qty'],
                'subtotal' => $row['subtotal']
            ];
        }

        $transaksi['banyak_item'] = count($transaksi['details']);

        $transaksi['total_qty'] = array_sum(
            array_column($transaksi['details'], 'qty')
        );

        return $transaksi;
    }
}
