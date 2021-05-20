<?php

namespace Militer\mvcCore\Csrf;

interface iCsrf
{
    public function verify(string $csrfToken);
}
