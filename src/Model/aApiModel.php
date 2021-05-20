<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Request\iRequest;
use Militer\mvcCore\Http\Response\iResponse;

abstract class aApiModel extends aModel
{
    // protected string $sitemapTable;
    // protected string $layoutsTable;
    // protected string $sectionsTable;
    protected string $popupsTable;
    protected string $views;


    public function __construct()
    {
        parent::__construct();
    }


    protected function renderPopup(string $popup)
    {
        $popupFile = $this->getPopup($popup);
        \ob_start();
        require "{$this->views}/popups/{$popupFile}.php";
        $popup = \ob_get_clean();
        $this->Response->sendPopup($popup);
    }
    private function getPopup(string $popup)
    {
        $sql = "SELECT `file` FROM `{$this->popupsTable}` WHERE `name`='{$popup}'";
        return self::$PDO::queryFetchColumn($sql);
    }

}
