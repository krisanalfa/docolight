<?php

namespace Docolight\Docoflow;

use CDbCriteria;
use Carbon\Carbon;
use Docolight\Support\Fluent;
use Docolight\Docoflow\Entity\Step;
use Docolight\Docoflow\Entity\Group;
use Docolight\Docoflow\Entity\Verificator;
use Docolight\Docoflow\Models\Workflow;
use Docolight\Docoflow\Models\WorkflowStep;
use Docolight\Docoflow\Models\WorkflowGroups;
use Docolight\Docoflow\Models\WorkflowVerificator;

/**
 * Workflow fetcher. It helps you to manage your workflow, such as get the verificators, groups, step, etc.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Flo extends Fluent
{
    /**
     * Workflow date validity.
     *
     * @var \Carbon\Carbon
     */
    protected $validUntil;
    /**
     * Workflow Model
     *
     * @var \Docolight\Docoflow\Models\Workflow
     */
    protected $workflow;

    /**
     * Step entities
     *
     * @var \Docolight\Docoflow\Entity\Step
     */
    protected $steps;

    /**
     * Array that frendly with json representation.
     *
     * @var array
     */
    protected $jsonAble;

    /**
     * Group entities
     *
     * @var \Docolight\Docoflow\Entity\Group
     */
    protected $groups;

    /**
     * Verificator entities
     *
     * @var \Docolight\Docoflow\Entity\Verificator
     */
    protected $verificators;

    /**
     * A 'cache' to store grouped verificators based on their step
     *
     * @var array
     */
    protected $groupedVerificators;

    /**
     * Flo constructor
     *
     * @param int $id Workflow id in database.
     */
    public function __construct($id)
    {
        $this->workflow = Workflow::model()->findByPk($id);

        if (! $this->workflow) {
            return;
        }

        $this->fill($this->workflow->attributes);

        $this->steps = new Step($this->workflow->getRelated('steps'));

        $this->groups = new Group();

        foreach ($this->steps as $step) {
            foreach ($step->getRelated('groups') as $group) {
                $this->groups->push($group);
            }
        }

        $this->verificators = new Verificator();

        foreach ($this->groups as $group) {
            foreach ($group->getRelated('verificators') as $verificator) {
                $this->verificators->push($verificator);
            }
        }
    }

    /**
     * Statically create new Flo implementation
     *
     * @param int $id Workflow id in database
     *
     * @return \Docolight\Docoflow\Flo
     */
    public static function fetch($id)
    {
        return new static($id);
    }

    /**
     * Get all steps in current workflow. Return an empty array if there's no step in it.
     *
     * @return array|\Docolight\Docoflow\Entity\Step
     */
    public function steps()
    {
        return $this->steps ?: [];
    }

    /**
     * Get a single step. Let's say you have 4 steps in this workflow. You can call the 4th step entity by call:
     *
     * ```php
     * Flo::fetch($id)->step(4);
     * ```
     *
     * @param int $step Step you want to return
     *
     * @return null|\Docolight\Docoflow\Models\WorkflowStep
     */
    public function step($step)
    {
        return ($this->steps) ? (($this->steps->offsetExists((int) $step - 1)) ? $this->steps->offsetGet((int) $step - 1)
                                                                               : null)
                              : null;
    }

    /**
     * Get current group entities. Return an empty array if there's no group in it.
     *
     * @return array|\Docolight\Docoflow\Entity\Group
     */
    public function groups()
    {
        return $this->groups ?: [];
    }

    /**
     * Get group based on your step. If you have 4 steps in current workflow, and you want to get a group only in the second step, you may call:
     *
     * ```php
     * Flo::fetch(1)->groupsInStep(2);
     * ```
     *
     * @param int $step Step you want to return.
     *
     * @return array|\Docolight\Docoflow\Entity\Group
     */
    public function groupsInStep($step)
    {
        return ($this->step($step)) ? new Group($this->step($step)->groups) : [];
    }

    /**
     * Get all verificators in your workflow. Will return empty array if there's no verificator in your current workflow.
     *
     * @return array|\Docolight\Docoflow\Entity\Verificator
     */
    public function verificators()
    {
        return $this->verificators ?: [];
    }

    /**
     * Get verificators in certain step. If you have 4 steps in your workflow, and you want to get a list of verificators only in 3rd step:
     *
     * ```php
     * Flo::fetch(1)->verificatorsInStep(3);
     * ```
     *
     * @param int $step Step you want to return
     *
     * @return array|\Docolight\Docoflow\Entity\Verificator
     */
    public function verificatorsInStep($step)
    {
        if (isset($this->groupedVerificators[$step])) {
            return $this->groupedVerificators[$step];
        }

        if (!$this->step($step)) {
            return [];
        }

        $groupId = [];

        foreach ($this->step($step)->getRelated('groups') as $group) {
            $groupId[] = $group->{$group->tableSchema->primaryKey};
        }

        $criteria = new CDbCriteria();

        $criteria->addInCondition('workflow_groups_id', $groupId);

        $verificators = new Verificator(WorkflowVerificator::model()->findAll($criteria));

        return $this->groupedVerificators[$step] = $verificators;
    }

    /**
     * Convert this implementation to a standard PHP array.
     *
     * @param boolean $returnModel Set to true if you want to return an object, instead of array.
     *
     * @return array|\Docolight\Support\Collection
     */
    public function toArray($returnModel = false)
    {
        if ($this->jsonAble) {
            return $this->jsonAble;
        }

        if (! $this->workflow) {
            $this->jsonAble = fluent();

            return;
        }

        $this->jsonAble = fluent($this->workflow->attributes);

        $this->jsonAble->steps = collect();

        foreach ($this->steps as $step) {
            $stepFluent = fluent(array_except($step->attributes, ['workflow_id']));

            $stepFluent->groups = collect();

            foreach ($step->getRelated('groups') as $group) {
                $groupFluent = fluent(array_except($group->attributes, ['workflow_step_id']));

                $groupFluent->verificators = collect();

                foreach ($group->getRelated('verificators') as $verificator) {
                    $fluentVerificator = fluent(array_except($verificator->attributes, ['workflow_groups_id']));

                    if ($verificator->hasMutator('user')) {
                        container()->instance('workflow.verificator', $verificator);
                        unset($fluentVerificator['user_id']);
                        $fluentVerificator->user = $verificator->callMutator('user', [container('workflow.verificator')]);
                    }

                    $groupFluent->verificators->push($fluentVerificator);
                }

                $stepFluent->groups->push($groupFluent);
            }

            $this->jsonAble->steps->push($stepFluent);
        }

        return ($returnModel) ? $this->jsonAble : $this->jsonAble->toArray();
    }

    /**
     * Get workflow date validity.
     *
     * @return null|\Carbon\Carbon
     */
    public function validUntil()
    {
        if ($this->workflow) {
            return $this->workflow->validUntil();
        }
    }

    /**
     * Determine if workflow stil valid to be validated.
     *
     * @return bool
     */
    public function valid()
    {
        if ($this->workflow) {
            return $this->workflow->valid();
        }

        return false;
    }
}
