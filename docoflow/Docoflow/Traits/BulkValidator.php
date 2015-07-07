<?php

namespace Docoflow\Traits;

/**
 * Validate all model in current entities. You can reject all steps and validators using this trait.
 *
 * @author Krisan Alfa Timur <krisanalfa@gmail.com>
 */
trait BulkValidator
{
    /**
     * Approve all.
     *
     * @return mixed
     */
    public function approve()
    {
        foreach ($this as $validable) {
            $validable->approve();
        }

        return $this;
    }

    /**
     * Approve all if it's not expired.
     *
     * @return mixed
     */
    public function approveIf()
    {
        foreach ($this as $validable) {
            $validable->approveIf();
        }

        return $this;
    }

    /**
     * Reject all.
     *
     * @return mixed
     */
    public function reject()
    {
        foreach ($this as $validable) {
            $validable->reject();
        }

        return $this;
    }

    /**
     * Reject all if it's not expired.
     *
     * @return mixed
     */
    public function rejectIf()
    {
        foreach ($this as $validable) {
            $validable->rejectIf();
        }

        return $this;
    }

    /**
     * Reset all.
     *
     * @return mixed
     */
    public function reset()
    {
        foreach ($this as $validable) {
            $validable->reset();
        }

        return $this;
    }

    /**
     * Reset all if it's not expired.
     *
     * @return mixed
     */
    public function resetIf()
    {
        foreach ($this as $validable) {
            $validable->resetIf();
        }

        return $this;
    }
}
