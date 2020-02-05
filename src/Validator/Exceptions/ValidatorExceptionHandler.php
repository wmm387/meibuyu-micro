<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: zero
 * Date: 2020/2/5
 * Time: 16:16
 */

namespace Meibuyu\Micro\Validator\Exceptions;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ValidatorExceptionHandler extends ExceptionHandler
{

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation(); // 阻止异常冒泡
        /** @var ValidatorException $throwable */
        $data = json_encode([
            'code' => $throwable->getCode() ?: 401,
            'msg' => $throwable->first(),
        ], JSON_UNESCAPED_UNICODE);

        return $response
            ->withAddedHeader('content-type', 'application/json')
            ->withBody(new SwooleStream($data));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidatorException;
    }
}