<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\Model\aApiModel;

abstract class aAdminApiModel extends aApiModel
{


    public function __construct()
    {
        parent::__construct();
    }


    public function preferences(array $preferencesData)
    {
        $this->adminCheck();
        \extract($preferencesData);

        $this->Response->sendResponse('adminPreferences', true);
        $this->Response->sendResponse('adminPreferences', false);
    }


    protected function adminCheck()
    {
        !isset($_SESSION['admin_uuid'])
            && $this->Response->sendResponse('logout', true);
    }
}
