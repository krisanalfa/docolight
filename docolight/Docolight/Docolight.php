<?php

use Docolight\Support\Facade;
use Docolight\Container\Container;
use Docolight\Support\ClassLoader;

/**
 * Docolight component.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Docolight extends CApplicationComponent
{
    /**
     * Minimum version of PHP supported with this components.
     */
    const PHP_MINIMUM_VERSION = 50400;

    /**
     * Custom PSR-0 based libraries for your application.
     *
     * @var array
     */
    protected $libraries = array();

    /**
     * Root path where 'docolibs' folder resides.
     *
     * @var string
     */
    protected $rootPath = 'libs';

    /**
     * Component initialization.
     */
    public function init()
    {
        // $this->checkEnvironment();

        $this->registerAutoload();

        $this->loadHelpers();

        $this->registerApplicationComponents();

        Facade::set(container());
    }

    /**
     * Check minimum requirement
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function checkEnvironment()
    {
        if (!(PHP_VERSION_ID > static::PHP_MINIMUM_VERSION)) {
            throw new RuntimeException('Your PHP Version must be at least 5.4 to use this library!');
        }
    }

    /**
     * Load helper file.
     */
    protected function loadHelpers()
    {
        require_once Yii::getPathOfAlias('application.libs.docolight.Docolight.Support.helpers').'.php';
    }

    /**
     * Register application component.
     */
    protected function registerApplicationComponents()
    {
        Yii::app()->setComponent('container', new Container());

        container()->bindIf('Docolight\Http\ResponseFactory', 'Docolight\Http\ResponseFactory', true);

        Yii::app()->setComponent('response', container('Docolight\Http\ResponseFactory'));

        container()->alias('Docolight\Http\ResponseFactory', 'response');
    }

    /**
     * Register autoload file.
     */
    protected function registerAutoload()
    {
        require_once Yii::getPathOfAlias("application.{$this->rootPath}.docolight.Docolight.Support.ClassLoader").'.php';

        if ($librariesPath = realpath(Yii::getPathOfAlias("application.{$this->rootPath}"))) {
            foreach (array_merge($this->getDefaultLibraries(), $this->libraries) as $library) {
                ClassLoader::addDirectories($librariesPath.DIRECTORY_SEPARATOR.$library);
            }

            ClassLoader::register();
        }
    }

    /**
     * Get default library.
     *
     * @return array
     */
    final private function getDefaultLibraries()
    {
        return array(
            'symfony',
            'carbon',
            'docolight',
            'docoflow',
        );
    }
}
