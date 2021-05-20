<?php

namespace Militer\mvcCore\Http\Response;

use Militer\mvcCore\DI\Container;

class Response implements iResponse
{
    public string $header = '';
    public array  $headers = [];

    public $body = null;
    public $code = 200;

    private $httpStatusCodes = [
        100 => "Continue",
        101 => "Switching Protocols",
        102 => "Processing",
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi-Status",
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        306 => "(Unused)",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Timeout",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Request Entity Too Large",
        414 => "Request-URI Too Long",
        415 => "Unsupported Media Type",
        416 => "Requested Range Not Satisfiable",
        417 => "Expectation Failed",
        418 => "I'm a teapot",
        419 => "Authentication Timeout",
        420 => "Enhance Your Calm",
        422 => "Unprocessable Entity",
        423 => "Locked",
        424 => "Failed Dependency",
        424 => "Method Failure",
        425 => "Unordered Collection",
        426 => "Upgrade Required",
        428 => "Precondition Required",
        429 => "Too Many Requests",
        431 => "Request Header Fields Too Large",
        444 => "No Response",
        449 => "Retry With",
        450 => "Blocked by Windows Parental Controls",
        451 => "Unavailable For Legal Reasons",
        494 => "Request Header Too Large",
        495 => "Cert Error",
        496 => "No Cert",
        497 => "HTTP to HTTPS",
        499 => "Client Closed Request",
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Timeout",
        505 => "HTTP Version Not Supported",
        506 => "Variant Also Negotiates",
        507 => "Insufficient Storage",
        508 => "Loop Detected",
        509 => "Bandwidth Limit Exceeded",
        510 => "Not Extended",
        511 => "Network Authentication Required",
        598 => "Network read timeout error",
        599 => "Network connect timeout error",
    ];


    public function homePage()
    {
        $this->header = "Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}/";
        $this->send();
    }

    public function notFound()
    {
        $this->code = 404;
        $this->body = \file_get_contents(\PAGE_404);
        $this->send();
    }

    public function notFoundPage()
    {
        $this->code = 404;
        $this->header = "Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}/404";
        $this->send();
    }

    public function badRequest()
    {
        $this->code = 400;
        $this->body = \file_get_contents(\PAGE_400);
        $this->send();
    }

    public function badRequestPage()
    {
        $this->code = 400;
        $this->header = "Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}/400";
        $this->send();
    }

    public function badRequestMessage()
    {
        $this->code = 400;
        $this->sendMessage('Bad Request');
    }


    public function sendMessage(string $message)
    {
        $response['message'] = $message;
        $this->sendJson($response);
    }

    public function sendResponse(string $response, bool|string $index)
    {
        $response = Container::get('response', $response);
        \is_bool($index)   && $response = $index ? $response['success'] : $response['error'];
        \is_string($index) && $response = $response[$index];
        $this->sendJson($response);
    }

    public function sendPage(string $page)
    {
        $this->sendHtml($page);
    }

    public function sendMain(array $main)
    {
        $this->sendJson($main);
    }

    public function sendPopup(string $popup)
    {
        $response['popup'] = $popup;
        $this->sendJson($response);
    }


    public function sendOK()
    {
        $this->send();
    }


    private function sendHtml(string $html)
    {
        $this->header = 'Content-type: text/html; charset=UTF-8';
        $this->body = $html;
        $this->send();
    }

    private function sendText(string $text)
    {
        $this->header = 'Content-type: text/plain; charset=UTF-8';
        $this->body = $text;
        $this->send();
    }

    private function sendJson($response)
    {
        $encodeOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE;
        $json = \json_encode($response, $encodeOptions);
        $this->header = 'Content-type: application/json';
        $this->body = $json;
        $this->send();
    }

    private function send()
    {
        $protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/2';
        $text = $this->httpStatusCodes[$this->code];
        \header("{$protocol} {$this->code} {$text}");

        if (!empty($this->header)) {
            \header($this->header);
        }

        if (!empty($this->headers)) {
            foreach ($this->headers as $header) {
                \header($header);
            }
        }

        if ($this->body !== null) {
            \file_put_contents('php://output', $this->body);
        }

        exit;
    }
}
