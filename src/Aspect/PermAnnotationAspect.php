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
        $className = $proceedingJoinPoint->className;
        $methodName = $proceedingJoinPoint->methodName;
        if ($autoPerm && in_array($methodName, $autoPerm->exclude)) {
            return false; // 跳过不需要鉴权的方法
        }
        $prefix = $autoPerm && $autoPerm->prefix ? $autoPerm->prefix : $this->genPrefix($className);
        $name = $perm && $perm->name ? $perm->name : $this->genName($methodName);
        return $this->parsePermName($prefix, $name);
    }

    // 拼接权限名
    protected function parsePermName($prefix, $name)
    {
        // 注意每个应用的app_name的唯一性
        $appName = trim($this->config->get('app_name'), '_');
        $prefix = trim($prefix, '_');
        $name = trim($name, '_');
        return $appName . '_' . $prefix . '_' . $name;
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

    // 生成名称
    protected function genName(string $methodName): string
    {
        $methodName = Str::snake($methodName);
        $methodName = str_replace('__', '_', $methodName);
        return $methodName;
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