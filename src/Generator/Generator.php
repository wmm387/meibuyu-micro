<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: zero
 * Date: 2020/2/11
 * Time: 15:16
 */

namespace Meibuyu\Micro\Generator;

use Hyperf\Utils\CodeGen\Project;
use Hyperf\Utils\Filesystem\Filesystem;
use Hyperf\Utils\Str;
use Meibuyu\Micro\Generator\Stubs\Stub;

abstract class Generator
{
    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The array of options.
     *
     * @var array
     */
    protected $options;

    /**
     * The shortname of stub.
     *
     * @var string
     */
    protected $stub;

    /**
     * The class-specific output paths.
     *
     * @var string
     */
    protected $classPath = '';

    /**
     * The file extension.
     * @var string
     */
    protected $extension = '';

    protected $_name;

    protected $_namespace;

    /**
     * Create new instance of this class.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->filesystem = new Filesystem;
        $this->options = $options;
        $this->makeName();
        $this->makeNamespace();
    }

    /**
     * Make name.
     */
    public function makeName()
    {
        $name = $this->name;
        if (Str::contains($this->name, '\\')) {
            $name = str_replace('\\', '/', $this->name);
        }
        if (Str::contains($this->name, '/')) {
            $name = str_replace('/', '/', $this->name);
        }

        $this->_name = Str::studly(str_replace(' ', '/', ucwords(str_replace('/', ' ', $name))));
    }

    /**
     * Make namespace
     */
    public function makeNamespace()
    {
        $classNamespace = str_replace('/', '\\', $this->classPath);
        $this->_namespace = 'App\\' . $classNamespace . '\\';
    }

    /**
     * Get extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Run the generator.
     *
     * @return int
     * @throws FileAlreadyExistsException
     */
    public function run()
    {
        $path = $this->getPath();
        if ($this->filesystem->exists($path)) {
            throw new FileAlreadyExistsException($path);
        }
        if (!$this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, 0777, true, true);
        }

        return $this->filesystem->put($path, $this->getStub());
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        $project = new Project();
        return BASE_PATH . '/' . $project->path($this->_namespace . $this->_name . $this->extension);
    }

    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub()
    {
        return (new Stub(__DIR__ . '/Stubs/' . $this->stub . '.stub', $this->getReplacements()))->render();
    }

    /**
     * Get template replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return [
            'date' => $this->getDate(),
            'time' => $this->getTime(),
            'class' => $this->getClass(),
            'namespace' => $this->getNamespace(),
        ];
    }

    public function getDate()
    {
        return date('Y/m/d', time());
    }

    public function getTime()
    {
        return date('h:i', time());
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass()
    {
        return Str::studly(class_basename($this->_name));
    }

    /**
     * Get class namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        $segments = explode('/', $this->_name);
        array_pop($segments);
        return 'namespace ' . rtrim($this->_namespace . implode($segments, '\\'), '\\') . ';';
    }

    /**
     * Get value from options by given key.
     *
     * @param string $key
     * @param string|null $default
     *
     * @return string
     */
    public function getOption($key, $default = null)
    {
        if (!array_key_exists($key, $this->options)) {
            return $default;
        }

        return $this->options[$key] ?: $default;
    }

    /**
     * Helper method for "getOption".
     *
     * @param string $key
     * @param string|null $default
     *
     * @return string
     */
    public function option($key, $default = null)
    {
        return $this->getOption($key, $default);
    }

    /**
     * Handle call to __get method.
     *
     * @param string $key
     *
     * @return string|mixed
     */
    public function __get($key)
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }

        return $this->option($key);
    }
}