<?php
/**
 * Created by PhpStorm.
 * User: Zero
 * Date: 2020/4/9
 * Time: 14:59
 */

namespace Meibuyu\Micro\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class AutoPerm extends AbstractAnnotation
{

    /**
     * @var string
     */
    public $prefix = '';

    /**
     * @var array
     */
    public $exclude = [];

}