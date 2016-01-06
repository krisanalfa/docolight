<?php

namespace Docolight\Rest\Http;

use Yii;
use Exception;
use CController;
use Docolight\Http\Response;

/**
 * A restful controller.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
abstract class RestFulController extends CController
{
    /**
     * Data stored in this implementation. This data will be rendered as json response later in `afterAction` method.
     *
     * @var array
     */
    protected $data;

    /**
     * Exception stored in this implementation. When exception raised, the controller stored the exception in it.
     *
     * @var \Exception
     */
    protected $error;

    /**
     * Headers to be sent in HTTP response.
     *
     * @var array
     */
    protected $headers = [
        'Powered-By' => 'PT. Docotel Teknologi',
    ];

    /**
     * Handling 404 error.
     *
     * @param string $action
     */
    public function missingAction($action)
    {
        return response('json', 404, fluent([
            'status' => 404,
            'message' => 'Not Found',
            'value' => "Request to [".Yii::app()->request->getPathInfo()."] has no resource.",
        ]), $this->headers)->send();
    }

    /**
     * Set an exception publicly.
     *
     * @param \Exception $error
     */
    public function setError($error)
    {
        // Remove the data
        $this->data = null;

        // Set new error
        $this->error = $error;
    }

    /**
     * After action.
     *
     * @param \CInlineAction $action Action from controller
     *
     * @return \Docolight\Http\Response
     */
    public function afterAction($action)
    {
        parent::afterAction($action);

        // Basic data template
        $statusCode = 200;
        $data = array(
            'status' => $statusCode,
            'message' => 'Success',
            'value' => $this->data,
        );

        // Let's find an error
        if ($this->error instanceof Exception) {
            // throw $this->error;

            // Basic data template for an exception
            $statusCode = 500;
            $data = [
                'status' => $statusCode,
                'message' => 'Error',
                'value' => [
                    'code' => $this->error->getCode(),
                    'message' => $this->error->getMessage(),
                ],
            ];

            // If exception code is an HTTP resoponse code
            if ($message = Response::getMessageForCode($this->error->getCode())) {
                $statusCode = $this->error->getCode();
                $data['status'] = $statusCode;
                $data['message'] = preg_replace('/^\d+ /', '', $message);
            }
            // If not, this is a system failure
            // Trace the error if YII_DEBUG is defined
            else {
                if (YII_DEBUG) {
                    $data['value']['stack_trace'] = $this->error->getTrace();
                }
            }
        }
        // If the data is a Reponse instance, send the response
        elseif ($this->data instanceof Response) {
            return $this->data->send();
        }

        return response('json', $statusCode, collect($data), $this->headers)->send();
    }
}
