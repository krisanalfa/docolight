<?php

namespace Docolight\Support;

use Iterator;
use CActiveRecord;

/**
 * Iterate your active record in a page. Usefull to make a paging report.
 *
 * ```php
 *
 * $model = MyModel::model()->findByPk(1);
 *
 * $pager = new IterablePager($model, 20);
 *
 *
 * foreach($pager as $stack)
 * {
 *      // $stack = array with 20 length, contains MyModel implementation
 *      // next loop will be the next 20 and so on until the last of your
 *      // data. it respects your criteria also.
 * }
 * ```
 */
abstract class IterablePager implements Iterator
{
    /**
     * Total data.
     *
     * @var int
     */
    protected $total;

    /**
     * Current data.
     *
     * @var array
     */
    protected $current;

    /**
     * Model.
     *
     * @var CActiveRecord
     */
    protected $model;

    /**
     * Criteria.
     *
     * @var CDbCriteria
     */
    protected $criteria;

    /**
     * Class constructor.
     *
     * @param CActiveRecord $model
     * @param int           $limit
     */
    public function __construct(CActiveRecord $model, $limit = 10)
    {
        // Property assignment
        $this->model = $model;
        $this->criteria = clone $model->getDbCriteria();

        // Count data
        // Make sure it has no limit
        $this->criteria->limit = null;
        $model->setDbCriteria($this->criteria);
        $this->total = $model->count();

        // Start from zero
        $this->criteria->offset = 0;

        // Set limit
        $this->criteria->limit = $limit;

        // Set criteria
        $this->model->setDbCriteria($this->criteria);

        // Fetch first collection
        $this->current = $this->prepare($this->model->findAll($this->criteria));
    }

    /**
     * Rewind cursor.
     */
    public function rewind()
    {
        $this->criteria->offset = 0;
    }

    /**
     * Get current cursor.
     *
     * @return array
     */
    public function current()
    {
        // Fetch new collection
        $this->current = $this->prepare($this->model->findAll($this->criteria));

        return $this->current;
    }

    /**
     * Fetch next page.
     */
    public function next()
    {
        $this->criteria->offset = $this->criteria->offset + $this->criteria->limit;

        // Set new criteria of offset
        $this->model->setDbCriteria($this->criteria);
    }

    /**
     * Determine whether next page exists.
     *
     * @return bool
     */
    public function valid()
    {
        return ($this->criteria->offset) <= ($this->total);
    }

    /**
     * Get current offset.
     *
     * @return int
     */
    public function key()
    {
        return $this->criteria->offset;
    }

    /**
     * Prepare the data before we store them in $current property.
     *
     * @param array $items
     *
     * @return mixed
     */
    abstract protected function prepare(array $items);
}
