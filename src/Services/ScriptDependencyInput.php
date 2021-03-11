<?php


namespace Narcisonunez\LaravelScripts\Services;

use Exception;
use Illuminate\Support\Str;

class ScriptDependencyInput
{
    public string $inputDependency;
    public string $name = '';
    public string $description = '';
    public bool $isOptional = false;

    /**
     * ScriptDependencyInput constructor.
     * @param $inputDependency
     * @throws Exception
     */
    public function __construct($inputDependency)
    {
        $this->inputDependency = $inputDependency;
        $this->resolve($inputDependency);
    }

    /**
     * @param $inputDependency
     * @return static
     */
    public static function for($inputDependency) : self
    {
        return new static($inputDependency);
    }

    /**
     * @param $inputDependency
     * @throws Exception
     */
    public function resolve($inputDependency) : void
    {
        $data = explode(':', $inputDependency);

        if (count($data) === 1) {
            $this->setValues(trim($data[0]));
            return;
        }

        if (count($data) === 2) {
            $this->setValues(trim($data[0]), trim($data[1]));
            return;
        }

        throw new Exception('Bad Script Dependency Input format.');
    }

    /**
     * @param string $name
     * @param string $description
     */
    private function setValues($name = '', $description = '')
    {
        $this->name = $name;
        $this->description = $description;
        $this->isOptional = Str::endsWith($name, '?');
    }

    /**
     * Alias for the dependency name
     * @return string
     */
    public function key() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function label() : string
    {
        $label = Str::title(implode(' ',preg_split('/(?=[A-Z])/', $this->name)));
        return Str::replaceLast('?', '', $label);
    }
}
