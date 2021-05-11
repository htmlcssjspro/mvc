<?php

namespace Militer\mvcCore\Model;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Response\iResponse;
use Militer\mvcCore\User\iUser;

abstract class aPageModel extends aModel
{
    // public int $code;
    // public string $header;
    // public array $headers;

    protected iUser $User;


    protected function __construct()
    {
        parent::__construct();
        $this->User = Container::get(iUser::class);
    }


    protected function escapeOutput(array &$data)
    {
        \array_walk_recursive($data, function (&$item) {
            $item = \htmlspecialchars($item, \ENT_QUOTES | \ENT_HTML5 | \ENT_SUBSTITUTE, 'UTF-8');
        });
        return $data;
    }
}
