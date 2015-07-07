<?php

namespace Docolight\Docoflow\Traits;

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
}
