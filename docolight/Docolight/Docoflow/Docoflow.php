<?php

namespace Docolight\Docoflow;

use Yii;
use Datetime;
use Exception;
use Docolight\Docoflow\Entity\Step;
use Docolight\Docoflow\Entity\Group;
use Docolight\Docoflow\Entity\Verificator;
use Docolight\Docoflow\Models\Workflow;
use Docolight\Docoflow\Models\WorkflowStep;
use Docolight\Docoflow\Models\WorkflowGroups;
use Docolight\Docoflow\Models\WorkflowVerificator;

/**
 * Workflow model. Use this class to save your workflow.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Docoflow
{
    /**
     * Prepare a data to be stored in database.
     *
     * @var \Docolight\Docoflow\Entity\Step
     */
    protected $prepared;

    /**
     * Workflow active record.
     *
     * @var \Docolight\Docoflow\Models\Workflow
     */
    protected $workflow;

    /**
     * Workflow step entities.
     *
     * @var \Docolight\Docoflow\Entity\Step
     */
    protected $step;

    /**
     * Workflow group entities.
     *
     * @var \Docolight\Docoflow\Entity\Group
     */
    protected $group;

    /**
     * Verificator entities.
     *
     * @var \Docolight\Docoflow\Entity\Verificator
     */
    protected $verificator;

    /**
     * Expiracy date of this workflow.
     *
     * @var \Datetime
     */
    protected $expiredDate;

    /**
     * Docoflow class constructor.
     *
     * @param string                                      $name        Your workflow name
     * @param \Docolight\Docoflow\Entity\Step|null        $step        Your workflow step entities
     * @param \Docolight\Docoflow\Entity\Group|null       $group       Your workflow group entities
     * @param \Docolight\Docoflow\Entity\Verificator|null $verificator Your workflow verificator entities
     */
    public function __construct($name, Step $step = null, Group $group = null, Verificator $verificator = null)
    {
        $this->name = $name;
        $this->step = $step;
        $this->group = $group;
        $this->verificator = $verificator;
    }

    /**
     * Statically create Docoflow instance.
     *
     * @param string                                      $name        Your workflow name
     * @param \Docolight\Docoflow\Entity\Step|null        $step        Your workflow step entities
     * @param \Docolight\Docoflow\Entity\Group|null       $group       Your workflow group entities
     * @param \Docolight\Docoflow\Entity\Verificator|null $verificator Your workflow verificator entities
     */
    public static function make($name, Step $step = null, Group $group = null, Verificator $verificator = null)
    {
        return new static($name, $step, $group, $verificator);
    }

    /**
     * Inject step entities to a record.
     *
     * @param \Docolight\Docoflow\Entity\Step $step
     *
     * @return \Docolight\Docoflow\Docoflow
     */
    public function withStep(Step $step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Inject step group entities to a record.
     *
     * @param \Docolight\Docoflow\Entity\Group $group
     *
     * @return \Docolight\Docoflow\Docoflow
     */
    public function withGroup(Group $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Inject verificator entities to a record.
     *
     * @param \Docolight\Docoflow\Entity\Verificator $verificator
     *
     * @return \Docolight\Docoflow\Docoflow
     */
    public function withVerificator(Verificator $verificator)
    {
        $this->verificator = $verificator;

        return $this;
    }

    /**
     * Make a workflow valid until certain time.
     *
     * @param \Datetime $expiredDate
     *
     * @return \Docolight\Docoflow\Docoflow
     */
    public function validUntil(Datetime $expiredDate)
    {
        $this->expiredDate = $expiredDate;

        return $this;
    }

    /**
     * Save a workflow.
     *
     * @return \Docolight\Docoflow\Models\Workflow
     */
    public function save()
    {
        if (!$this->prepared) {
            $this->prepare();
        }

        $transaction = transaction(Yii::app()->dbKeuangan);

        try {
            $newWorkFlowId = $this->createNewWorkFlow();

            $this->createWorkflowDefinition($newWorkFlowId);

            $transaction->commit();

            return $this->workflow;
        } catch (Exception $e) {
            $transaction->rollback();

            throw $e;
        }
    }

    /**
     * Prepare data before saving
     *
     * @return \Docolight\Docoflow\Docoflow
     */
    public function prepare()
    {
        if (!$this->step) {
            throw new Exception('Cannot create workflow, steps are empty.');
        }

        $steps = $this->step->rebuild();

        if (!$this->group) {
            throw new Exception('Cannot create workflow, groups are empty.');
        }

        $this->group->rebuild($steps);

        if (!$this->verificator) {
            throw new Exception('Cannot create workflow, verificators are empty.');
        }

        $this->verificator->rebuild($steps);

        $this->prepared = $steps;

        return $this;
    }

    /**
     * Get prepared data.
     *
     * @return \Docolight\Docoflow\Entity\Step
     */
    public function getPreparedData()
    {
        if (!$this->prepared) {
            $this->prepare();
        }

        return $this->prepared;
    }

    /**
     * Make a new workflow.
     */
    protected function createNewWorkFlow()
    {
        $workflowModel = new Workflow();

        $workflowModel->name = $this->name;

        if ($this->expiredDate instanceof Datetime) {
            $workflowModel->expired_at = $this->expiredDate->format('Y-m-d H:i:s');
        }

        if (!$workflowModel->save()) {
            throw new Exception("Cannot save Workflow: ".json_encode($workflowModel->getErrors()));
        }

        $this->workflow = $workflowModel;

        return $workflowModel->{$workflowModel->tableSchema->primaryKey};
    }

    /**
     * Create steps, groups, and verificators definition.
     *
     * @param id $newWorkFlowId
     *
     * @return id Newly saved workflow id.
     */
    protected function createWorkflowDefinition($newWorkFlowId)
    {
        foreach ($this->prepared as $step) {
            $stepModel = new WorkflowStep();

            $stepModel->workflow_id = $newWorkFlowId;
            $stepModel->name = $step->name;

            if ($step->expired_at instanceof Datetime) {
                $stepModel->expired_at = $step->expired_at->format('Y-m-d H:i:s');
            }

            if ($stepModel->save()) {
                $this->createWorkflowGroup($step->groups, $stepModel->{$stepModel->tableSchema->primaryKey});
            } else {
                throw new Exception("Cannot save Step: ".json_encode($stepModel->getErrors()));
            }
        }

        return $newWorkFlowId;
    }

    /**
     * Create workflow group.
     *
     * @param \Docolight\Docoflow\Entity\Group $groups
     * @param id                               $newStepId
     */
    protected function createWorkflowGroup(Group $groups, $newStepId)
    {
        foreach ($groups as $group) {
            $groupModel = new WorkflowGroups();

            $groupModel->workflow_step_id = $newStepId;
            $groupModel->name = $group->name;

            if ($groupModel->save()) {
                $this->createWorkflowVerificator($group->verificator, $groupModel->{$groupModel->tableSchema->primaryKey});
            } else {
                throw new Exception("Cannot save Group: ".json_encode($groupModel->getErrors()));
            }
        }
    }

    /**
     * Create workflow verificators.
     *
     * @param \Docolight\Docoflow\Entity\Verificator $verificators
     * @param id                                     $newGroupId
     */
    protected function createWorkflowVerificator(Verificator $verificators, $newGroupId)
    {
        foreach ($verificators as $verificator) {
            $verificatorModel = new WorkflowVerificator();

            $verificatorModel->workflow_groups_id = $newGroupId;
            $verificatorModel->user_id = $verificator->user_id;

            if (!$verificatorModel->save()) {
                throw new Exception("Cannot save Verificator: ".json_encode($verificatorModel->getErrors()));
            }
        }
    }
}
