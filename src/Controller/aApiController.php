<?php

namespace Militer\mvcCore\Controller;

use Core\Http\Request\iRequest;
use Core\Http\Response\iResponse;
use Militer\mvcCore\DI\Container;

abstract class aApiController extends aController
{
    protected $Request;
    protected $Response;


    public function __construct() {
        parent::__construct();
        $this->Request  = Container::get(iRequest::class);
        $this->Response = Container::get(iResponse::class);
        $this->methodVerify();
    }


    protected function sendMessage(string $messages, bool $result)
    {
        $messages = Container::get('messages')[$messages];
        $message = $result ? $messages['success'] : $messages['error'];
        $this->Response->sendJson($message);
    }

    protected function csrfVerify(callable $callback)
    {
        $postData = $this->Request->getPOST();
        $csrfToken = $postData['csrf'] ?? '';
        unset($postData['csrf']);
        if ($this->Csrf->verify($csrfToken)) {
            $this->filterInput($postData);
            $callback($postData);
        } else {
            $this->Response->badRequest();
        }
    }

    protected function filterInput(array &$data)
    {
        \array_walk_recursive($data, function (&$item) {
            $item = \trim($item);
        });
    }


    protected function fileUpload(string $name, $dest, callable $callback)
    {
        $array = \is_array($dest);
        $files = $this->Request->getFILES();
        $file = $files[$name];
        if (\is_array($file['name']) && $array) {
            $files = $this->reArrayFILES($file);
        } else {
            $files = [$file];
            $dest  = [$dest];
        }

        foreach ($files as $key => $file) {
            $fileName = $file['name'];
            if (!empty($fileName)) {
                $ext = \pathinfo($fileName, PATHINFO_EXTENSION);
                $rel = "$dest[$key].$ext";
                $relPath[$key] = $rel;
                $destination = \_ROOT_ . $rel;
                if ($file['error'] === \UPLOAD_ERR_OK) {
                    $callback($file);
                    $result = \move_uploaded_file($file['tmp_name'], $destination);
                    !$result && $this->Response->badRequest();
                } else {
                    $this->fileUploadErrors($file);
                }
            } else {
                $relPath[$key] = '';
            }
        }
        return $array ? $relPath : $relPath[0];
    }

    private function fileUploadErrors($file)
    {
        switch ($file['error']) {
            case \UPLOAD_ERR_INI_SIZE:
            case \UPLOAD_ERR_FORM_SIZE:
                $message = "Размер принятого файла <strong>{$file['name']}</strong> превысил максимально допустимый размер";
                break;
            case \UPLOAD_ERR_PARTIAL:
                $message = "Загружаемый файл <strong>{$file['name']}</strong> был получен только частично";
                break;
            case \UPLOAD_ERR_NO_FILE:
                $message = "Файл <strong>{$file['name']}</strong> не был загружен";
                break;
            case \UPLOAD_ERR_NO_TMP_DIR:
                $message = "Отсутствует временная папка";
                break;
            case \UPLOAD_ERR_CANT_WRITE:
                $message = "Не удалось записать файл <strong>{$file['name']}</strong> на диск";
                break;
            case \UPLOAD_ERR_EXTENSION:
                $message = "Неожиданная ошибка загрузки файла <strong>{$file['name']}</strong>";
                break;
        }
        $this->Response->sendJson($message);
    }

    private function reArrayFILES($files)
    {
        foreach ($files as $fileKey => $fileValue) {
            foreach ($fileValue as $position => $value) {
                $reFiles[$position][$fileKey] = $value;
            }
        }
        return $reFiles;
    }

    private function methodVerify()
    {
        $method = $this->Request->getMethod();
        $method !== 'post' && $this->Response->notFound();
    }
}
