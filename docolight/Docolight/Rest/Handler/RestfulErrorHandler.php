<?php

namespace Docolight\Rest\Handler;

use Yii;
use Exception;
use CErrorHandler;
use CInlineAction;
use CHttpException;
use CExceptionEvent;

/**
 * Handling error on Restful Based Controller.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class RestfulErrorHandler extends CErrorHandler
{
    /**
     * Handle error / exception.
     *
     * @param \CEvent $event
     */
    public function handle($event)
    {
        $controller = Yii::app()->controller;

        $error = ($event instanceof CExceptionEvent) ? $event->exception : new Exception($event->message, $event->code);
        if (!$controller) {
            return $this->internalHandle($error);
        }

        $controller->setError($error);

        return $controller->afterAction(new CInlineAction($controller, 'exception'));
    }

    /**
     * Hack, handling internal error on user input whenever they want to access any controller in parent module.
     *
     * @param Exception $error
     */
    public function internalHandle(Exception $error)
    {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
            'value' => [
                'error_code' => $error->getCode(),
                'error_message' => strip_tags($error->getMessage()),
            ],
        ];

        $statusCode = 500;

        if ($error instanceof CHttpException) {
            $data['status'] = 400;
            $data['message'] = 'Bad Request';

            $statusCode = 400;
        }

        if (YII_DEBUG) {
            $data['value']['stack_trace'] = $error->getTrace();
        }

        return response('json', $statusCode, fluent($data), [], true);
    }
}
