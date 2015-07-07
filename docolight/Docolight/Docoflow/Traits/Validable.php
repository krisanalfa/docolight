<?php

namespace Docolight\Docoflow\Traits;

use Carbon\Carbon;

/**
 * Any class use this trait is can be validated. Means, you can determine if the verification is not expired.
 * You can also change the status, check the status, etc.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
trait Validable
{
    /**
     * Date validity.
     *
     * @var \Carbon\Carbon
     */
    protected $validUntil;

    /**
     * Get workflow date validity.
     *
     * @return null|\Carbon\Carbon
     */
    public function validUntil()
    {
        if ($validUntil = $this->expired_at) {
            if ($this->validUntil) {
                return $this->validUntil;
            }

            $this->validUntil = new Carbon($validUntil);

            return $this->validUntil;
        }
    }

    /**
     * Determine if workflow stil valid to be validated.
     *
     * @return bool
     */
    public function valid()
    {
        if ($validUntil = $this->validUntil()) {
            return !$validUntil->isPast();
        }

        return true;
    }
}
