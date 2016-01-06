<?php

namespace Docoflow\Contracts;

interface DocoflowContract
{
    public function getListStateActivity($stateActivityId, $assignee);

    public function getNextStateActivity($stateActivityId, $assignee);

    public function getPrevStateActivity($stateActivityId, $assignee);

    public function isAuthorized($stateActivityId, $assignee);
}
