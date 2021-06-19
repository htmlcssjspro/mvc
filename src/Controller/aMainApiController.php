<?php

namespace Militer\mvcCore\Controller;

use Militer\mvcCore\Controller\aApiController;

abstract class aMainApiController extends aApiController
{


    public function __construct()
    {
        parent::__construct();
    }


    public function index(array $routerData)
    {
        \extract($routerData);
        $this->methodVerify($method);
        \method_exists($this, $action)
            ? $this->$action($query)
            : $this->Response->badRequestMessage();
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


    public function popup(array $query)
    {
        $popup = $query[0];
        $this->Model->popup($popup);
    }



    public function documentation()
    {
        $message = 'api_documentation';
        $this->Response->sendMessage($message);
    }
}
