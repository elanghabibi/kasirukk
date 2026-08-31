<?php
class ErrorController extends Controller
{
    public function notFound()
    {
        http_response_code(404);

        $this->view('errors/404', [
            'title' => '404 Not Found'
        ], 'guest');
    }

    public function forbidden()
    {
        http_response_code(403);

        $this->view('errors/403', [
            'title' => '403 Forbidden'
        ], 'guest');
    }
}
