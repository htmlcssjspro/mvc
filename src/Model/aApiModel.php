<?php

namespace Militer\mvcCore\Model;

abstract class aApiModel extends aModel
{


    public function __construct()
    {
        parent::__construct();
    }


    protected function checkEmail($email)
    {
        $sql = "SELECT 1 FROM {$this->usersTable} WHERE `email`=?";
        $pdostmt = $this->PDO->prepare($sql);
        return $pdostmt->execute([$email]);
    }

}
