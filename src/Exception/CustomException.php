<?php


namespace Militer\mvcCore\Exception;

class CustomException
{

    private static $errors = [
        -1                  => 'CORE_EXCEPTION',
        0                   => 'FATAL_ERROR',
        1045                => 'SQLSTATE[HY000]',
        1049                => 'FATAL_PDO_ERROR',
        E_ALL               => 'E_ALL',
        E_ERROR             => 'FATAL_ERROR',
        E_WARNING           => 'WARNING',
        E_PARSE             => 'PARSE_ERROR',
        E_NOTICE            => 'NOTICE',
        E_CORE_ERROR        => 'FATAL_CORE_ERROR',
        E_CORE_WARNING      => 'CORE_WARNING',
        E_COMPILE_ERROR     => 'FATAL_COMPILE_ERROR',
        E_COMPILE_WARNING   => 'COMPILE_WARNING',
        E_USER_ERROR        => 'FATAL_USER_ERROR',
        E_USER_WARNING      => 'USER_WARNING',
        E_USER_NOTICE       => 'USER_NOTICE',
        E_STRICT            => 'STRICT_ERROR',
        E_RECOVERABLE_ERROR => 'FATAL_RECOVERABLE_ERROR',
        E_DEPRECATED        => 'DEPRECATED_WARNING',
        E_USER_DEPRECATED   => 'USER_DEPRECATED_WARNING',
    ];


    public function __construct()
    {
        \set_error_handler([self::class, 'errorHandler'], E_ALL);
        \set_exception_handler([self::class, 'exceptionHandler']);
    }


    public static function init()
    {
        \set_error_handler([self::class, 'errorHandler'], E_ALL);
        \set_exception_handler([self::class, 'exceptionHandler']);
    }


    public static function errorHandler($code, $message, $file, $line)
    {
        $severity = E_ALL;
        throw new \ErrorException($message, $code, $severity, $file, $line);
    }

    public static function exceptionHandler(\Throwable $exception)
    {
        if (DEV) {
            echo '<h1>' .
                (self::$errors[$exception->getCode()] ?? $exception->getCode()) .
                " [{$exception->getCode()}]" .
                '</h1>';
            echo '<table><tr><td>Класс</td><td><strong>' . get_class($exception) . '</strong></td></tr>';
            echo '<tr><td>Сообщение</td><td><strong>' . $exception->getMessage() . '</strong></td></tr>';
            echo '<tr><td>Файл</td><td><strong>' . $exception->getFile() . '</strong></td></tr>';
            echo '<tr><td>Строка</td><td><strong>' . $exception->getLine() . '</strong></td></tr>';
            // echo '<tr><td>Предыдущий обработчик</td><td><strong>' . $exception->getPrevious() . '</strong></td></tr>';
            echo '<tr><td valign="top">Трассировка</td><td><pre>' . $exception->getTraceAsString() . '</pre></td></tr></table>';
            // \print_r(['<tr><td valign="top">Трассировка</td><td><pre>', $exception->getTrace(), '</pre></td></tr></table>']);
        } else {
            echo '<h1>Ошибка</h1>';
            $message = str_repeat("=", 80) . "\n" . \date('Y.m.d H:i:s') . ' => ' . $exception->__toString() . "\n\n";
            \error_log($message, 3, \ERROR_LOG_FILE);
        }
    }
}
