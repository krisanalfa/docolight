<?php

namespace Docoflow\Entity;

use Exception;
use Docoflow\Traits\Entity;
use Docolight\Support\Collection;

/**
 * Group entities.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Group extends Collection
{
    use Entity;

    /**
     * Rebuild a barely new group to a readable array.
     *
     * @param \Docoflow\Entity\Step &$steps
     *
     * @return \Docoflow\Entity\Group
     */
    public function rebuild(Step &$steps)
    {
        $groups = new static;

        foreach ($this as $group) {
            $group = fluent($group);

            if (! $groupId = $group->{'$id'}) {
                throw new Exception("Group doesn't have an id.");
            }

            if (! $assignedStep = $group->{'$step'}) {
                throw new Exception("Group doesn't have any assigned step id.");
            }

            if ($steps->has($assignedStep)) {
                if ($steps->get($assignedStep)->groups->has($groupId)) {
                    throw new Exception("Group id [$groupId] has been assigned before and it can't be overriden.");
                }

                $group->verificator = new Verificator();

                $groups->offsetSet($groupId, $group);

                $steps->pushGroup($groupId, $group);
                $steps->get($assignedStep)->groups->offsetSet($groupId, $group);
            } else {
                throw new Exception("Assigned step [$assignedStep] doesn't exist.");
            }
        }

        return $groups;
    }

    /**
     * Gather all verificator in current group
     *
     * @return \Docoflow\Entity\Verificator
     */
    public function gatherVerificators()
    {
        $verificators = new Verificator();

        foreach ($this as $group) {
            $verificators->assign($group->getRelated('verificators'));
        }

        return $verificators;
    }

    /**
     * Approve all.
     *
     * @return mixed
     */
    public function approve()
    {
        $this->gatherVerificators()->approve();

        return $this;
    }

    /**
     * Approve all if it's not expired.
     *
     * @return mixed
     */
    public function approveIf()
    {
        $this->gatherVerificators()->approveIf();

        return $this;
    }

    /**
     * Reject all.
     *
     * @return mixed
     */
    public function reject()
    {
        $this->gatherVerificators()->reject();

        return $this;
    }

    /**
     * Reject all if it's not expired.
     *
     * @return mixed
     */
    public function rejectIf()
    {
        $this->gatherVerificators()->rejectIf();

        return $this;
    }

    /**
     * Reset all.
     *
     * @return mixed
     */
    public function reset()
    {
        $this->gatherVerificators()->reset();

        return $this;
    }

    /**
     * Reset all if it's not expired.
     *
     * @return mixed
     */
    public function resetIf()
    {
        $this->gatherVerificators()->resetIf();

        return $this;
    }
}
