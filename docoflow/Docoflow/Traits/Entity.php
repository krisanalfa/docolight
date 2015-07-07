<?php

namespace Docoflow\Traits;

use Exception;
use CActiveRecord;

/**
 * With this trait, you can create your own entities in so many ways.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
trait Entity
{
    /**
     * Make an entity statically.
     *
     * @param array $stack Array of your entities
     *
     * @return mixed
     */
    public static function make(array $stack)
    {
        $instance = new static();

        return $instance->assign($stack);
    }

    /**
     * Bulk assign a data to the entities stack.
     *
     * @param array $stack Array of your entities.
     *
     * @return mixed
     */
    public function assign(array $stack)
    {
        foreach ($stack as $value) {
            $this->push($value);
        }

        return $this;
    }

    /**
     * Bulk save all model in current entity.
     *
     * @return void
     */
    public function save()
    {
        foreach ($this as $model) {
            if ($model instanceof CActiveRecord) {

                if (container()->bound(get_class(container('docoflow.connection')))) {
                    $model->save();
                } else {
                    $transaction = transaction(container('docoflow.connection'));

                    try {
                        $model->save();

                        $transaction->commit();
                    } catch (Exception $e) {
                        $transaction->rollback();

                        throw $e;
                    }
                }
            }
        }
    }
}
