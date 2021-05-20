<?php

namespace Militer\mvcCore\Http\Response;

interface iResponse
{
    public function homePage();
    public function notFound();
    public function notFoundPage();
    public function badRequest();
    public function badRequestPage();
    public function badRequestMessage();

    public function sendMessage(string $message);
    public function sendResponse(string $messages, bool|string $index);

    public function sendPage(string $page);
    public function sendMain(array $main);

    public function sendPopup(string $popup);
}
