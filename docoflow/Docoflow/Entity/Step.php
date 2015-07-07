<?php

namespace Docoflow\Entity;

use Exception;
use Docoflow\Traits\Entity;
use Docolight\Support\Collection;
use Docoflow\Traits\BulkValidator;

/**
 * Step entities.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Step extends Collection
{
    use Entity, BulkValidator;

    /**
     * Group entities. Useful to determine a verificator group.
     *
     * @var \Docoflow\Entity\Group.
     */
    protected $groups;

    /**
     * Rebuild a barely new step to a readable array.
     *
     * @return \Docoflow\Entity\Step
     */
    public function rebuild()
    {
        $steps = new static();

        foreach ($this as $step) {
            $step = fluent($step);

            if (! $stepId = $step->{'$id'}) {
                throw new Exception("Step doesn't have an id.");
            }

            $steps[$stepId] = $step;
            $steps[$stepId]->groups = new Group();
        }

        return $steps;
    }

    /**
     * Push group to existing implemetation, so we can determine verificator group later.
     *
     * @param int   $groupId
     * @param array $group
     *
     * @return void
     */
    public function pushGroup($groupId, $group)
    {
        if (! $this->groups) {
            $this->groups = new Group;
        }

        $this->groups->offsetSet($groupId, $group);
    }

    /**
     * Determine whether group is exist.
     *
     * @param id $groupId
     *
     * @return boolean
     */
    public function hasGroup($groupId)
    {
        return ($this->groups instanceof Group) ? $this->groups->has($groupId) : false;
    }

    /**
     * Get group determine by it's id.
     *
     * @param int $groupId
     *
     * @return null|array
     */
    public function getGroup($groupId)
    {
        return ($this->groups instanceof Group) ? $this->groups->offsetGet($groupId) : null;
    }
}
