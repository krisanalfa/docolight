<?php

namespace Docolight\Http;

use Docolight\Support\Fluent;
use Docolight\Http\Contracts\Arrayable;

/**
 * This is a simple abstraction over top an HTTP response. This
 * provides methods to set the HTTP status, the HTTP headers,
 * and the HTTP body. Please use this response only if you want
 * to send a [JSON](http://json.org) response.
 *
 * ```php
 * $response = new Docolight\Http\JsonResponse(new Docolight\Http\Headers());
 *
 * // Or you can resolve via factory
 * // $response = with(new Docolight\Http\ResponseFactory())->produce('json');
 *
 * // Move even shorter way
 * // $response = container('response')->produce('json');
 *
 * // Set your header
 * $response->headers->set('Foo', 'Bar');
 *
 * // Set your response status
 * $response->setStatus(202);
 *
 * // Set your response body
 * $response->setBody(new Docolight\Support\Fluent(array('my_json_index' => 'my_json_value')));
 *
 * // Send your response to client
 * $response->send();
 *
 * // In shorter way
 * // response(
 * //    'json',
 * //    200,
 * //    new Docolight\Support\Fluent(array('my_json_index' => 'my_json_value')),
 * //    array('Foo' => 'Bar')
 * // )->send();
 * ```
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class JsonResponse extends MimeResponse
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->body === null) {
            $this->body = $this->getEmpty();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getContentType()
    {
        return 'application/json';
    }

    /**
     * {@inheritdoc}
     */
    protected function convertToStringRepresentation(Arrayable $body)
    {
        return json_encode($body->castToArray());
    }

    /**
     * {@inheritdoc}
     */
    protected function getEmpty()
    {
        return new Fluent();
    }
}
