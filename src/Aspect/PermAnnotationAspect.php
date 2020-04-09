<?php
/**
 * Created by PhpStorm.
 * User: Zero
 * Date: 2020/4/8
 * Time: 14:48
 */

namespace Meibuyu\Micro\Aspect;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Utils\Str;
use Meibuyu\Micro\Annotation\AutoPerm;
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

    /**
     * @Inject
     * @var ConfigInterface
     */
    protected $config;

    public $annotations = [
        Perm::class,
        AutoPerm::class,
    ];

    /**
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     * @throws HttpResponseException
     * @throws \Hyperf\Di\Exception\Exception
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $perm = $this->genPermName($proceedingJoinPoint);
        if ($perm) {
            if ($this->permHandler->check($perm)) {
                return $proceedingJoinPoint->process();
            } else {
                throw new HttpResponseException('当前用户没有此操作权限');
            }
        }
        return $proceedingJoinPoint->process();
    }

    // 生成权限名
    public function genPermName(ProceedingJoinPoint $proceedingJoinPoint)
    {
        /** @var AutoPerm $autoPerm */
        /** @var Perm $perm */
        [$autoPerm, $perm] = $this->getAnnotations($proceedingJoinPoint);
        if ($perm && $perm->name) {
            // 如果有指定权限名,直接返回
            return $perm->name;
        } else {
            $methodName = $proceedingJoinPoint->methodName;
            $className = $proceedingJoinPoint->className;
            if ($autoPerm) {
                if (in_array($methodName, $autoPerm->exclude)) {
                    // 排除不鉴权的方法
                    return false;
                }
                if ($autoPerm->prefix) {
                    // 如果有指定前缀,直接拼接返回
                    return $this->parseName($autoPerm->prefix, $methodName);
                }
            }
            return $this->parseName($this->genPrefix($className), $methodName);
        }
    }

    // 拼接权限名
    protected function parseName($prefix, $methodName)
    {
        // 注意每个应用的app_name的唯一性
        $appName = $this->config->get('app_name');
        if ($prefix[-1] !== '_') {
            $prefix .= '_';
        }
        return $appName . '_' . $prefix . $methodName;
    }

    // 生成前缀
    protected function genPrefix(string $className): string
    {
        $handledNamespace = Str::replaceFirst('Controller', '', Str::after($className, '\\Controller\\'));
        $handledNamespace = str_replace('\\', '_', $handledNamespace);
        $prefix = Str::snake($handledNamespace);
        $prefix = str_replace('__', '_', $prefix);
        return $prefix;
    }

    // 获取注解
    public function getAnnotations(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $metadata = $proceedingJoinPoint->getAnnotationMetadata();
        return [
            $metadata->class[AutoPerm::class] ?? null,
            $metadata->method[Perm::class] ?? null
        ];
    }

}