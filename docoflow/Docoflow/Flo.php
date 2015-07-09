<?php

namespace Docoflow;

use Exception;
use CDbCriteria;
use Carbon\Carbon;
use Docoflow\Entity\Step;
use Docoflow\Entity\Group;
use Docolight\Support\Fluent;
use Docoflow\Models\Workflow;
use Docoflow\Entity\Verificator;
use Docoflow\Models\WorkflowStep;
use Docoflow\Models\WorkflowGroups;
use Docoflow\Models\WorkflowVerificator;
use Docoflow\Contracts\ValidationStatus;

/**
 * Workflow fetcher. It helps you to manage your workflow, such as get the verificators, groups, step, etc.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Flo extends Fluent implements ValidationStatus
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
     * @var \Docoflow\Models\Workflow
     */
    protected $workflow;

    /**
     * Step entities
     *
     * @var \Docoflow\Entity\Step
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
     * @var \Docoflow\Entity\Group
     */
    protected $groups;

    /**
     * Verificator entities
     *
     * @var \Docoflow\Entity\Verificator
     */
    protected $verificators;

    /**
     * A 'cache' to store grouped verificators based on their step
     *
     * @var array
     */
    protected $groupedVerificators;

    /**
     * Boostrapped entities
     *
     * @var array
     */
    protected $bootstrapped;

    /**
     * Flo constructor
     *
     * @param int $id Workflow id in database.
     */
    public function __construct($id)
    {
        $this->workflow = Workflow::model()->findByPk($id);
        $this->bootstrapped = fluent();

        if (! $this->workflow) {
            return;
        }

        $this->bootstrapped->set('workflow', true);

        $this->fill($this->workflow->attributes);
    }

    protected function makeInternalSteps()
    {
        $this->bootstrapped->set('steps', true);

        if ($this->workflow) {
            $this->steps = new Step($this->workflow->getRelated('steps'));
        }
    }

    protected function makeInternalGroups()
    {
        $this->bootstrapped->set('groups', true);

        if (! $this->steps) {
            if (! $this->bootstrapped->steps) {
                $this->makeInternalSteps();
            } else {
                return;
            }
        }

        $this->groups = new Group();

        foreach ($this->steps as $step) {
            foreach ($step->getRelated('groups') as $group) {
                $this->groups->push($group);
            }
        }
    }

    protected function makeInternalVerificators()
    {
        $this->bootstrapped->set('verificators', true);

        if (! $this->groups) {
            if (! $this->bootstrapped->groups) {
                $this->makeInternalGroups();
            } else {
                return;
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
     * @return \Docoflow\Flo
     */
    public static function fetch($id)
    {
        return new static($id);
    }

    /**
     * Get all steps in current workflow. Return an empty array if there's no step in it.
     *
     * @return array|\Docoflow\Entity\Step
     */
    public function steps()
    {
        if (! $this->bootstrapped->steps) {
            $this->makeInternalSteps();
        }

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
     * @return null|\Docoflow\Models\WorkflowStep
     */
    public function step($step)
    {
        if (! $this->bootstrapped->steps) {
            $this->makeInternalSteps();
        }

        return ($this->steps) ? (($this->steps->offsetExists((int) $step - 1)) ? $this->steps->offsetGet((int) $step - 1)
                                                                               : null)
                              : null;
    }

    /**
     * Get current group entities. Return an empty array if there's no group in it.
     *
     * @return array|\Docoflow\Entity\Group
     */
    public function groups()
    {
        if (! $this->bootstrapped->groups) {
            $this->makeInternalGroups();
        }

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
     * @return array|\Docoflow\Entity\Group
     */
    public function groupsInStep($step)
    {
        if (! $this->bootstrapped->groups) {
            $this->makeInternalGroups();
        }

        return ($this->step($step)) ? new Group($this->step($step)->groups) : [];
    }

    /**
     * Get all verificators in your workflow. Will return empty array if there's no verificator in your current workflow.
     *
     * @return array|\Docoflow\Entity\Verificator
     */
    public function verificators()
    {
        if (! $this->bootstrapped->verificators) {
            $this->makeInternalVerificators();
        }

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
     * @return array|\Docoflow\Entity\Verificator
     */
    public function verificatorsInStep($step)
    {
        if (isset($this->groupedVerificators[$step])) {
            return $this->groupedVerificators[$step];
        }

        if (! $this->bootstrapped->steps) {
            $this->makeInternalSteps();
        }
        $steps = $this->step($step);
        if (empty($steps)) {
            return [];
        }

        return $this->groupedVerificators[$step] = Group::make($steps->getRelated('groups'))->gatherVerificators();
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

    /**
     * Reset workflow, also all of it's steps and verificators status.
     *
     * @return \Docoflow\Flo
     */
    public function reset()
    {
        if ($this->workflow) {
            $this->workflow->reset();

            if (($verificators = $this->verificators()) instanceof Verificator) {
                $verificators->reset();
            }

            if (($steps = $this->steps()) instanceof Step) {
                $steps->reset();
            }
        }

        return $this;
    }

    /**
     * Reset workflow, also all of it's steps and verificators status if only it's not expired.
     *
     * @return \Docoflow\Flo
     */
    public function resetIf()
    {
        if ($this->workflow) {
            $this->workflow->resetIf();

            if (($verificators = $this->verificators()) instanceof Verificator) {
                $verificators->resetIf();
            }

            if (($steps = $this->steps()) instanceof Step) {
                $steps->resetIf();
            }
        }

        return $this;
    }

    /**
     * Reject workflow, also all of it's steps and verificators status.
     *
     * @return \Docoflow\Flo
     */
    public function reject()
    {
        if ($this->workflow) {
            $this->workflow->reject();

            if (($verificators = $this->verificators()) instanceof Verificator) {
                $verificators->reject();
            }

            if (($steps = $this->steps()) instanceof Step) {
                $steps->reject();
            }
        }

        return $this;
    }

    /**
     * Reject workflow, also all of it's steps and verificators status if only it's not expired.
     *
     * @return \Docoflow\Flo
     */
    public function rejectIf()
    {
        if ($this->workflow) {
            $this->workflow->rejectIf();

            if (($verificators = $this->verificators()) instanceof Verificator) {
                $verificators->rejectIf();
            }

            if (($steps = $this->steps()) instanceof Step) {
                $steps->rejectIf();
            }
        }

        return $this;
    }

    /**
     * Approve workflow, also all of it's steps and verificators status.
     *
     * @return \Docoflow\Flo
     */
    public function approve()
    {
        if ($this->workflow) {
            $this->workflow->approve();

            if (($verificators = $this->verificators()) instanceof Verificator) {
                $verificators->approve();
            }

            if (($steps = $this->steps()) instanceof Step) {
                $steps->approve();
            }
        }

        return $this;
    }

    /**
     * Approve workflow, also all of it's steps and verificators status if only it's not expired.
     *
     * @return \Docoflow\Flo
     */
    public function approveIf()
    {
        if ($this->workflow) {
            $this->workflow->approveIf();

            if (($verificators = $this->verificators()) instanceof Verificator) {
                $verificators->approveIf();
            }

            if (($steps = $this->steps()) instanceof Step) {
                $steps->approveIf();
            }
        }

        return $this;
    }

    /**
     * Save all data in workflow, include all of it's steps, groups, and verificators.
     *
     * @return void
     */
    public function save()
    {
        if ($this->workflow) {
            $transaction = transaction(container('docoflow.connection'));

            try {
                $this->workflow->save();

                if (($steps = $this->steps()) instanceof Step) {
                    $steps->save();
                }

                if (($groups = $this->groups()) instanceof Group) {
                    $groups->save();
                }

                if (($verificators = $this->verificators()) instanceof Verificator) {
                    $verificators->save();
                }

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();

                throw $e;
            }
        }
    }
}
