<?php

namespace Militer\mvcCore\User;

use Militer\mvcCore\Model\aModel;

abstract class aUser extends aModel implements iUser
{


    public function __construct()
    {
        parent::__construct();
    }


    public function generatePassword($length = 8)
    {
        $length = $length > 8 ? $length : 8;
        $charsArr = [
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'abcdefghijklmnopqrstuvwxyz',
            '0123456789',
        ];
        $symbols = '!@$%^&?*()';
        $password = '';
        function random($string){
            return $string[\random_int(0, \mb_strlen($string) - 1)];
        }
        foreach ($charsArr as $chars) {
            $password .= random($chars);
        }
        while (\mb_strlen($password) < $length - 1) {
            $chars = $charsArr[\random_int(0, \count($charsArr) - 1)];
            $password .= random($chars);
        }
        $password .= random($symbols);
        return \str_shuffle($password);
    }
}
