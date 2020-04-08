<?php
/**
 * Created by PhpStorm.
 * User: Zero
 * Date: 2020/4/8
 * Time: 13:59
 */

namespace Meibuyu\Micro\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Perm extends AbstractAnnotation
{

    /**
     * @var string
     */
    public $name = '';

    public function __construct($value = null)
    {
        $this->bindMainProperty('name', $value);
    }

}