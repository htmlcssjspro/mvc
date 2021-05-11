<?php

namespace Militer\mvcCore\View;

use Militer\mvcCore\Model\iModel;

interface iView
{
    public static function renderPage($page);
    public static function renderMain($main);
    public static function renderNotFound();
}
