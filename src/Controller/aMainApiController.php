<?php

namespace Militer\mvcCore\Controller;

use Militer\mvcCore\Controller\aApiController;
use Militer\mvcCore\Model\interfaces\iMainApiModel;

abstract class aMainApiController extends aApiController
{
    public iMainApiModel $Model;


    public function __construct()
    {
        parent::__construct();
    }


    public function login()
    {
        $this->csrfVerify(function ($loginData) {
            $this->User->login($loginData);
        });
    }

    public function logout()
    {
        $this->User->logout();
    }

    public function register()
    {
        $this->csrfVerify(function ($registerData) {
            $this->User->register($registerData);
        });
    }

    public function accessRestoreRequest()
    {
        $this->csrfVerify(function ($accessRestoreData) {
            $this->User->accessRestoreRequest($accessRestoreData);
        });
    }

    public function accessRestore()
    {
        $this->csrfVerify(function ($accessRestoreData) {
            $this->User->accessRestore($accessRestoreData);
        });
    }






    public function documentation()
    {
        $message = 'api_documentation';
        $this->Response->sendMessage($message);
    }
}
