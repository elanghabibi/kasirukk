<?php

class Transaksi extends Model
{
    protected $table = "transaksi";
    protected $fillable = [
        "user_id",
        "kode_transaksi",
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
            detail_transaksi.subtotal AS detail_subtotal
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
                    'subtotal' => $row['subtotal'],
                    'pajak' => $row['pajak'],
                    'total' => $row['total'],
                    'tipe_order' => $row['tipe_order'],
                    'tanggal' => $row['tanggal_transaksi'],
                    'tunai' => $row['tunai'],
                    'kembalian' => $row['kembalian'],
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
                'detail_subtotal' => $row['detail_subtotal']
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
            'subtotal' => $data[0]['subtotal'],
            'pajak' => $data[0]['pajak'],
            'total' => $data[0]['total'],
            'tipe_order' => $data[0]['tipe_order'],
            'no_meja' => $data[0]['no_meja'],
            'keterangan' => $data[0]['keterangan'],
            'tanggal' => $data[0]['tanggal_transaksi'],
            'tunai' => $data[0]['tunai'],
            'kembalian' => $data[0]['kembalian'],
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
                'detail_subtotal' => $row['detail_subtotal']
            ];
        }

        $transaksi['banyak_item'] = count($transaksi['details']);

        $transaksi['total_qty'] = array_sum(
            array_column($transaksi['details'], 'qty')
        );

        return $transaksi;
    }

    public function paginateWithDetails($perPage = 10, $page = 1)
    {
        // Ambil transaksi utama terlebih dahulu
        $pagination = $this->select('
            transaksi.*,
            users.nama AS kasir')->join(
                'users',
                'users.id = transaksi.user_id'
            )->paginate($perPage, $page);

        if (empty($pagination['data'])) {
            $pagination['data'] = [];
            return $pagination;
        }

        $result = [];

        foreach ($pagination['data'] as $row) {
            $id = $row['id'];

            $result[$id] = [
                'id' => $row['id'],
                'kode_transaksi' => $row['kode_transaksi'],
                'kasir' => $row['kasir'],
                'nama_pelanggan' => $row['nama_pelanggan'],
                'subtotal' => $row['subtotal'],
                'pajak' => $row['pajak'],
                'total' => $row['total'],
                'tipe_order' => $row['tipe_order'],
                'tanggal' => $row['tanggal_transaksi'],
                'tunai' => $row['tunai'],
                'kembalian' => $row['kembalian'],
                'created_at' => $row['created_at'],
                'details' => []
            ];
        }

        // Ambil detail berdasarkan transaksi yang sudah dipaginasi
        foreach ($result as &$transaksi) {

            $details = $this
                ->withDetailTransaksi()
                ->where('id', $transaksi['id'])
                ->get();

            foreach ($details as $detail) {
                $transaksi['details'][] = [
                    'id' => $detail['detail_id'],
                    'menu_id' => $detail['menu_id'],
                    'nama_menu' => $detail['nama_menu'],
                    'harga_menu' => $detail['harga_menu'],
                    'qty' => $detail['qty'],
                    'detail_subtotal' => $detail['detail_subtotal']
                ];
            }

            $transaksi['banyak_item'] =
                count($transaksi['details']);

            $transaksi['total_qty'] =
                array_sum(
                    array_column(
                        $transaksi['details'],
                        'qty'
                    )
                );
        }

        unset($transaksi);

        $pagination['data'] = array_values($result);

        return $pagination;
    }

    public function getUrutanHariIni()
    {
        $tanggal = date('Ymd');

        $this->db->query("
        SELECT MAX(
            CAST(
                SUBSTRING_INDEX(kode_transaksi, '-', -1)
                AS UNSIGNED
            )
        ) AS urutan
        FROM transaksi
        WHERE kode_transaksi LIKE :kode
    ");

        $this->db->bind(
            ':kode',
            "TX-{$tanggal}-%"
        );

        $result = $this->db->single();

        return ((int) ($result['urutan'] ?? 0)) + 1;
    }
}
