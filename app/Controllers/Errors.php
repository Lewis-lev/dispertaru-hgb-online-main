<?php

namespace App\Controllers;

class Errors extends BaseController
{
    public function notFound()
    {
        return $this->response
            ->setStatusCode(404)
            ->setBody(view('errors/html/error_404'));
    }
}
