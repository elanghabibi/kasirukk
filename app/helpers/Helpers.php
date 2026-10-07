<?php
class Helpers
{
    /**
     * =========================================================
     * STRING HELPERS
     * =========================================================
     */

    /**
     * Mengambil inisial nama.
     *
     * Contoh:
     * "Elang Alamsyah Habibi" -> "EAH"
     * "Elang Habibi"          -> "EH"
     * "Elang"                 -> "E"
     */
    public static function initials(string $name, int $limit = 3): string
    {
        $name = trim($name);

        if ($name === '' || $limit <= 0) {
            return '';
        }

        $words = preg_split('/\s+/', $name);
        $initials = '';

        foreach (array_slice($words, 0, $limit) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }


    /**
     * Mengambil nama depan + inisial nama terakhir.
     *
     * Contoh:
     * "Elang Alamsyah Habibi" -> "Elang A"
     * "Elang Alamsyah"        -> "Elang A"
     * "Elang"                 -> "Elang"
     */
    public static function firstNameInitial(string $name): string
    {
        $name = trim($name);

        if ($name === '') {
            return '';
        }

        $words = preg_split('/\s+/', $name);

        if (count($words) === 1) {
            return $words[0];
        }

        $firstName = $words[0];
        $secondNameInitial = strtoupper(substr($words[1], 0, 1));
        // $lastNameInitial = strtoupper(substr(end($words), 0, 1));

        return $firstName . ' ' . $secondNameInitial;
    }


    /**
     * Mengambil nama depan saja.
     *
     * "Elang Alamsyah Habibi" -> "Elang"
     */
    public static function firstName(string $name): string
    {
        $name = trim($name);

        if ($name === '') {
            return '';
        }

        return preg_split('/\s+/', $name)[0];
    }


    /**
     * Mengambil nama terakhir.
     *
     * "Elang Alamsyah Habibi" -> "Habibi"
     */
    public static function lastName(string $name): string
    {
        $name = trim($name);

        if ($name === '') {
            return '';
        }

        $words = preg_split('/\s+/', $name);

        return end($words);
    }


    /**
     * Membatasi panjang teks.
     *
     * Contoh:
     * truncate("Lorem ipsum dolor sit amet", 15)
     * -> "Lorem ipsum..."
     */
    public static function truncate(string $text, int $length = 50): string
    {
        $text = trim($text);

        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . '...';
    }


    /**
     * =========================================================
     * FORMAT HELPERS
     * =========================================================
     */

    /**
     * Format angka menjadi Rupiah.
     *
     * 15000 -> Rp15.000
     */
    public static function rupiah(int|float $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }


    /**
     * Format angka tanpa simbol mata uang.
     *
     * 15000 -> 15.000
     */
    public static function numberFormat(int|float $number): string
    {
        return number_format($number, 0, ',', '.');
    }


    /**
     * Format tanggal Indonesia.
     *
     * 2026-08-30 14:30:00
     * -> 30 Agustus 2026
     */
    public static function formatDate(string $date): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $timestamp = strtotime($date);

        if ($timestamp === false) {
            return '';
        }

        $day = date('d', $timestamp);
        $month = $months[(int) date('m', $timestamp)];
        $year = date('Y', $timestamp);

        return "$day $month $year";
    }


    /**
     * Format tanggal dan waktu.
     *
     * 2026-08-30 14:30:00
     * -> 30 Agustus 2026, 14:30
     */
    public static function formatDateTime(string $date): string
    {
        $timestamp = strtotime($date);

        if ($timestamp === false) {
            return '';
        }

        return formatDate($date) . ', ' . date('H:i', $timestamp);
    }


    /**
     * =========================================================
     * SECURITY / OUTPUT HELPERS
     * =========================================================
     */

    /**
     * Escape HTML untuk mencegah XSS ketika menampilkan data.
     *
     * Contoh:
     * <?= e($user['name']) ?>
     */
    public static function e(?string $value): string
    {
        return htmlspecialchars(
            $value ?? '',
            ENT_QUOTES,
            'UTF-8'
        );
    }


    /**
     * Membuat URL berdasarkan BASEURL.
     *
     * url('/login')
     * -> BASEURL/login
     */
    public static function url(string $path = ''): string
    {
        return rtrim(BASEURL, '/') . '/' . ltrim($path, '/');
    }


    /**
     * Redirect ke URL tertentu.
     */
    public static function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }


    /**
     * =========================================================
     * SESSION HELPERS
     * =========================================================
     */

    /**
     * Mengambil data user yang sedang login.
     */
    public static function authUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }


    /**
     * Mengecek apakah user sedang login.
     */
    public static function isAuth(): bool
    {
        return isset($_SESSION['user']);
    }


    /**
     * Mengambil role user saat ini.
     */
    public static function userRole(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }


    /**
     * Mengecek role user.
     *
     * Contoh:
     * hasRole('admin')
     * hasRole('kasir')
     */
    public static function hasRole(string $role): bool
    {
        return userRole() === $role;
    }


    /**
     * Mengecek apakah user memiliki salah satu role.
     *
     * Contoh:
     * hasAnyRole(['admin', 'kasir'])
     */
    public static function hasAnyRole(array $roles): bool
    {
        return in_array(userRole(), $roles, true);
    }


    /**
     * =========================================================
     * UI HELPERS
     * =========================================================
     */

    /**
     * Menentukan class CSS berdasarkan kondisi.
     *
     * Contoh:
     * className($menu['is_active'], 'active', 'inactive')
     */
    public static function className(bool $condition, string $trueClass, string $falseClass = ''): string
    {
        return $condition ? $trueClass : $falseClass;
    }


    /**
     * Menghasilkan badge status.
     *
     * Bisa kamu sesuaikan dengan class Tailwind milikmu.
     */
    public static function statusBadge(string $status): string
    {
        return match ($status) {
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'pending'   => 'Menunggu',
            default     => ucfirst($status),
        };
    }


    /**
     * =========================================================
     * ARRAY / DATA HELPERS
     * =========================================================
     */

    /**
     * Mengambil value array dengan aman.
     *
     * Contoh:
     * value($user, 'name')
     */
    public static function value(array $data, string $key, mixed $default = null): mixed
    {
        return $data[$key] ?? $default;
    }


    /**
     * Mengubah nilai kosong menjadi fallback.
     *
     * Contoh:
     * orDefault('', 'Pelanggan Umum')
     * -> "Pelanggan Umum"
     */
    public static function orDefault(mixed $value, mixed $default): mixed
    {
        return ($value === null || $value === '') ? $default : $value;
    }


    /**
     * =========================================================
     * PAGINATION HELPERS
     * =========================================================
     */

    /**
     * Mengambil nomor halaman dari query string.
     *
     * ?page=2 -> 2
     */
    public static function currentPage(): int
    {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

        return ($page && $page > 0) ? $page : 1;
    }


    /**
     * =========================================================
     * TRANSACTION / POS HELPERS
     * =========================================================
     */

    /**
     * Menghitung subtotal item.
     *
     * price × quantity
     */
    public static function itemSubtotal(int|float $price, int $quantity): float|int
    {
        return $price * $quantity;
    }


    /**
     * Membuat kode transaksi sederhana berdasarkan ID.
     *
     * Contoh:
     * 125 -> TRX-00125
     */
    public static function transactionCode(int $id): string
    {
        return 'TRX-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    }


    /**
     * Mengubah nama kosong menjadi "Pelanggan Umum".
     */
    public static function customerName(?string $name): string
    {
        return trim($name ?? '') !== ''
            ? trim($name)
            : 'Pelanggan Umum';
    }


    /**
     * =========================================================
     * MISC HELPERS
     * =========================================================
     */

    /**
     * Mengecek apakah request menggunakan method tertentu.
     */
    public static function isMethod(string $method): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === strtoupper($method);
    }


    /**
     * Mengambil URL saat ini.
     */
    public static function currentUrl(): string
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = parse_url(BASEURL, PHP_URL_PATH) ?? '';

        if ($basePath !== '' && str_starts_with($url, $basePath)) {
            $url = substr($url, strlen($basePath));
        }

        return '/' . trim($url, '/');
    }

    public static function currentMonthNameId()
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[(int) date('n')];
    }

    public static function formatLabel($text)
    {
        return ucwords(str_replace('_', ' ', $text));
    }

    public static function formatKodeTransaksi($number)
    {
        $prefix = 'TX';
        $tanggal = date('Ymd');
        $urutan = str_pad($number, 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$tanggal}-{$urutan}";
    }

    public static function canDeleteWithin24Hours($createdAt)
    {
        if (empty($createdAt)) {
            return false;
        }

        $createdTime = strtotime($createdAt);
        $now = time();

        return ($now - $createdTime) < (24 * 60 * 60);
    }

    public static function slugToText(string $slug): string
    {
        return ucwords(str_replace('-', ' ', $slug));
    }

    public static function textToSlug(string $text): string
    {
        $text = strtolower(trim($text));

        // Ganti karakter non-alphanumeric menjadi -
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);

        // Hapus - di awal dan akhir
        return trim($text, '-');
    }
}
