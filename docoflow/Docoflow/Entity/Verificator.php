<?php

namespace Docoflow\Entity;

use Exception;
use Docoflow\Traits\Entity;
use Docolight\Support\Collection;
use Docoflow\Traits\BulkValidator;

/**
 * Verificator entities.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Verificator extends Collection
{
    use Entity, BulkValidator;

    /**
     * Rebuild a barely new group to a readable array.
     *
     * @param \Docoflow\Entity\Step &$steps
     */
    public function rebuild(Step &$steps)
    {
        foreach ($this as $verificator) {
            $verificator = fluent($verificator);

            if (! $assignedGroup = $verificator->{'$group'}) {
                throw new Exception("Verificator hasn't assigned to any group.");
            }

            if ($steps->hasGroup($assignedGroup)) {
                $verificatorStep = $steps->getGroup($assignedGroup)->{'$step'};

                $steps
                    ->get($verificatorStep)->groups
                    ->get($assignedGroup)->verificator
                    ->push($verificator);
            } else {
                throw new Exception("Assigned group [$assignedGroup] doent't exist.");
            }
        }
    }
}
