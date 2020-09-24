<?php

namespace Militer\mvcCore;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Interfaces\iUser;

abstract class User implements iUser
{
    public $pdo;
    protected $userTable = \USERS_TABLE;


    public function __construct()
    {
        $this->pdo = Container::get('pdo');
    }
}
