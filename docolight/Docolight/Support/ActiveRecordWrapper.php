<?php

namespace Docolight\Support;

use CActiveRecord;
use Docolight\Http\Contracts\Arrayable;

/**
 * You want to send your CActiveRecord implementation to a json response? Use this class.
 *
 * ```php
 *
 * // Get a model
 * $model = MyModel::model()->findByPk(1);
 *
 * // Wrap your model
 * $wrapper = new ActiveRecordWrapper($model);
 *
 * // Get a JsonResponse instance
 * $response = container('response')->produce('json');
 *
 * // Set response body
 * $response->setBody($wrapper);
 *
 * // Send response to client
 * $response->send();
 *
 * // To send your json request in a short way, you can use `response()` function
 * response('json', 200, $wrapper)->send();
 * ```
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class ActiveRecordWrapper implements Arrayable
{
    /**
     * Our model implementation
     *
     * @var CActiveRecord
     */
    protected $model;

    /**
     * Initialize the class.
     *
     * @param \CActiveRecord $default Default attribute inside this class
     */
    public function __construct(CActiveRecord $model)
    {
        $this->fill($model);
    }

    /**
     * Initialize the class statically.
     *
     * @param \CActiveRecord $default Default attribute inside this class
     */
    public static function make(CActiveRecord $model)
    {
        return new static($model);
    }

    /**
     * {@inheritdoc}
     */
    public function castToArray()
    {
        return Arr::arToArray($this->model);
    }

    /**
     * {@inheritdoc}
     */
    public function fill($model)
    {
        $this->innerFill($model);
    }

    /**
     * Inner fill to maintain type hint.
     *
     * @param \CActiveRecord $model
     *
     * @return void
     */
    protected function innerFill(CActiveRecord $model)
    {
        $this->model = $model;
    }
}
