<?php

namespace Militer\mvcCore\Controller;

use Militer\mvcCore\Controller\aApiController;

abstract class aAdminApiController extends aApiController
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
        $this->csrfVerify(function ($adminLoginData) {
            $this->User->adminLogin($adminLoginData);
        });
    }

    public function logout()
    {
        $this->User->adminLogout();
    }


    public function preferences()
    {
        $this->csrfVerify(function ($preferencesData) {
            $this->Model->preferences($preferencesData);
        });
    }

    public function adminPasswordChange()
    {
        $this->csrfVerify(function ($adminPasswordChangeData) {
            $this->User->adminPasswordChange($adminPasswordChangeData);
        });
    }

    public function addNewAdmin()
    {
        $this->csrfVerify(function ($newAdminData) {
            $this->User->addAdmin($newAdminData);
        });
    }

    public function adminActivation()
    {
        $this->csrfVerify(function ($adminActivationData) {
            $this->User->activateAdmin($adminActivationData);
        });
    }
}
