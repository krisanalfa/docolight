<?php

namespace Docoflow\Traits;

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
        if ($validUntil = $this->getAttribute('expired_at')) {
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

    /**
     * Get current status
     *
     * @return int
     */
    public function status()
    {
        if (is_null($status = (int) $this->getAttribute('status'))) {
            $status = 0;
        }

        switch ($status) {
            case 0:
                return static::UNPROCESSED;
                break;
            case 1:
                return static::APPROVED;
                break;
            case 2:
                return static::PARTIAL;
                break;
            case 3:
                return static::REJECTED;
                break;
            default:
                return static::INVALID;
                break;
        }
    }

    /**
     * Approve
     *
     * @return mixed
     */
    public function approve()
    {
        $this->setAttribute('status', static::APPROVED);

        return $this;
    }

    /**
     * Approve if only it's not expired.
     *
     * @return mixed
     */
    public function approveIf()
    {
        if (! $this->valid()) {
            throw new LogicException('Cannot be approved, because validation process is expired.');
        }

        return $this->approve();
    }

    /**
     * Reject.
     *
     * @return mixed
     */
    public function reject()
    {
        $this->setAttribute('status', static::REJECTED);

        return $this;
    }

    /**
     * Reject if only it's not expired.
     *
     * @return mixed
     */
    public function rejectIf()
    {
        if (! $this->valid()) {
            throw new LogicException('Cannot be rejected, because validation process is expired.');
        }

        return $this->reject();
    }

    /**
     * Reset.
     *
     * @return mixed
     */
    public function reset()
    {
        $this->setAttribute('status', static::UNPROCESSED);

        return $this;
    }

    /**
     * Reset if only it's not expired.
     *
     * @return mixed
     */
    public function resetIf()
    {
        if (! $this->valid()) {
            throw new LogicException('Cannot be reseted, because validation process is expired.');
        }

        return $this->resetIf();
    }
}
