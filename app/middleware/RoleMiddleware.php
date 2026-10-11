<?php

class RoleMiddleware extends Middleware
{
    public function handle($param = null)
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/login");
            exit;
        }

        if ($param === null) {
            return;
        }

        $roles = explode(",", $param);

        if (!isset($_SESSION['user']) && !in_array(Auth::user()['role'], $roles)) {
            http_response_code(403);
            require_once "../app/controllers/ErrorController.php";
            $error = new ErrorController();
            $error->forbidden();
            exit;
        }
    }
}
