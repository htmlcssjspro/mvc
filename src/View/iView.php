<?php

namespace Militer\mvcCore\View;

use Militer\mvcCore\Model\iModel;

interface iView
{
    public function render(iModel $Model);
}
