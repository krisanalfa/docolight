<?php

namespace Docolight\Docoflow\Entity;

use Exception;
use Docolight\Support\Collection;
use Docolight\Docoflow\Traits\Entity;

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
     * @param \Docolight\Docoflow\Entity\Step &$steps
     *
     * @return \Docolight\Docoflow\Entity\Group
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
}
