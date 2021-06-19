<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\Model\aApiModel;

abstract class aMainApiModel extends aApiModel
{
    public function __construct()
    {
        $this->popupsTable = self::MAIN_POPUPS_TABLE;
        $this->views = \MAIN_VIEWS;
        parent::__construct();
    }


    public function popup(string $popup)
    {
        $this->renderPopup($popup);
    }
}
