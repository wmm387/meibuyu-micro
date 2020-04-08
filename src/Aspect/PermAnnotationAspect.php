<?php
/**
 * Created by PhpStorm.
 * User: Zero
 * Date: 2020/4/8
 * Time: 14:48
 */

namespace Meibuyu\Micro\Aspect;

use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Utils\Str;
use Meibuyu\Micro\Annotation\Perm;
use Meibuyu\Micro\Exceptions\HttpResponseException;
use Meibuyu\Micro\Handler\PermHandler;

/**
 * @Aspect()
 */
class PermAnnotationAspect extends AbstractAspect
{

    /**
     * @Inject()
     * @var PermHandler
     */
    private $permHandler;

    public $annotations = [
        Perm::class,
    ];

    /**
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     * @throws HttpResponseException
     * @throws \Hyperf\Di\Exception\Exception
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $perm = $this->getPermName($proceedingJoinPoint);
        if ($this->permHandler->check($perm)) {
            return $proceedingJoinPoint->process();
        } else {
            throw new HttpResponseException('当前用户没有此操作权限');
        }
    }

    public function getPermName(ProceedingJoinPoint $proceedingJoinPoint)
    {
        /** @var Perm $annotation */
        $annotation = $this->getAnnotation($proceedingJoinPoint);
        if ($annotation->name) {
            return $annotation->name;
        } else {
            $className = $proceedingJoinPoint->className;
            $methodName = $proceedingJoinPoint->methodName;
            return $this->getPrefix($className) . $methodName;
        }
    }

    protected function getPrefix(string $className): string
    {
        $handledNamespace = Str::replaceFirst('Controller', '', Str::after($className, '\\Controller\\'));
        $handledNamespace = str_replace('\\', '_', $handledNamespace);
        $prefix = Str::snake($handledNamespace);
        $prefix = str_replace('__', '_', $prefix);
        if ($prefix[-1] !== '_') {
            $prefix .= '_';
        }
        return $prefix;
    }

    public function getAnnotation(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $metadata = $proceedingJoinPoint->getAnnotationMetadata();
        return $metadata->method[Perm::class] ?? null;
    }

}