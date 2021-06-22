<?php

namespace Militer\mvcCore\Controller;

use Militer\mvcCore\Controller\aApiController;
use Militer\mvcCore\Model\iAdminApiModel;

abstract class aAdminApiController extends aApiController
{
    public iAdminApiModel $Model;


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

    public function updateMainSitemap()
    {
        $this->csrfVerify(function ($sitemapData) {
            $this->Model->updateMainSitemap($sitemapData);
        });
    }
    public function updateAdminSitemap()
    {
        $this->csrfVerify(function ($sitemapData) {
            $this->Model->updateAdminSitemap($sitemapData);
        });
    }

    public function preferences()
    {
        $this->csrfVerify(function ($preferencesData) {
            $this->Model->preferences($preferencesData);
        });
    }




    // public function adminActivation()
    // {
    //     $this->csrfVerify(function ($adminActivationData) {
    //         $this->User->activateAdmin($adminActivationData);
    //     });
    // }
}
