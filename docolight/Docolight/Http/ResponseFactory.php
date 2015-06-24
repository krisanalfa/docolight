<?php

namespace Docolight\Http;

use Docolight\Support\Factory;

/**
 * A factory class to wrap all responses to a single instance resolver.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class ResponseFactory extends Factory
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultProduct()
    {
        return 'base';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethod($product)
    {
        return 'create'.ucfirst($product).'Response';
    }

    /**
     * Create Response instance
     *
     * @return \Docolight\Http\Response
     */
    protected function createBaseResponse()
    {
        return new Response(new Headers(array('Content-Type' => 'text/html')));
    }

    /**
     * Create JsonResponse instance.
     *
     * @return \Docolight\Http\JsonResponse
     */
    protected function createJsonResponse()
    {
        return $this->container->make('Docolight\Http\JsonResponse');
    }
}
