<?php

namespace Docolight\Agno\Traits;

use Yii;
use Docolight\Support\ClassLoader;

trait HasAutoload
{
    public $autoload = [];

    public $psrPath = 'psr';

    public $psrs = [];

    protected function loadPsr()
    {
        foreach ($this->psrs as $psr) {
            ClassLoader::addDirectories(realpath($this->getBasePath().DIRECTORY_SEPARATOR.$this->psrPath.DIRECTORY_SEPARATOR.$psr));
        }
    }

    protected function loadAutoload()
    {
        foreach ($this->autoload as $autoloadFile) {
            require_once Yii::getPathOfAlias($autoloadFile).'.php';
        }
    }
}
