<?php

namespace Militer\mvcCore\Csrf;

class Csrf implements iCsrf
{
    public function __construct()
    {
        $this->init();
    }


    private function init()
    {
        $_SESSION['csrf_secret'] = $_SESSION['user_uuid'] ?? $_SESSION['admin'] ?? 'guest';
        $_SESSION['csrf_token']  = \password_hash($_SESSION['csrf_secret'], PASSWORD_DEFAULT);
    }

    public function verify(string $csrfToken)
    {
        return \password_verify($_SESSION['csrf_secret'], $csrfToken);
    }
}
