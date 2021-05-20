<?php

namespace Militer\mvcCore\User;

interface iUser
{
    public function checkEmail(string $email);
    public function login(array $loginData);
    public function logout();
    public function accessRestoreRequest(string $email);
    public function accessRestore(string $email, string $password);
}
