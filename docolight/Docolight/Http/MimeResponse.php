<?php

namespace Docolight\Http;

use Exception;
use Docolight\Http\Contracts\Arrayable;

/**
 * Response that has specified mime type.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
abstract class MimeResponse extends Response
{
    /**
     * String representation of the body.
     *
     * @var string
     */
    protected $stringRepresentation;

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return (empty($this->body)) ? $this->getEmpty() : $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function finalize()
    {
        list($status, $headers, $body) = parent::finalize();

        $this->stringRepresentation = $this->convertToStringRepresentation($body);
        $this->length = strlen($this->stringRepresentation);

        $this->headers->set('Content-Type', $this->getContentType());
        $this->headers->set('Content-Length', $this->length);

        return array($status, $headers, $body);
    }

    /**
     * {@inheritdoc}
     */
    public function write($body, $replace = false)
    {
        if ($body) {
            return $this->innerWrite($body, $replace);
        }
    }

    /**
     * Inner write.
     *
     * @param Docolight\Http\Contracts\Arrayable $body
     * @param boolean                            $replace
     *
     * @return \Docolight\Http\Response
     */
    protected function innerWrite(Arrayable $body, $replace = false)
    {
        if ($replace === true) {
            $this->body = $body;
        } elseif ($replace === false) {
            $this->body->fill($body->castToArray());
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function sendBody($body)
    {
        if (trim(strtoupper(def($_SERVER, 'REQUEST_METHOD', ''))) !== 'HEAD') {
            echo $this->stringRepresentation;
        }
    }

    /**
     * Convert to a string representation.
     *
     * @param Docolight\Http\Contracts\Arrayable $body
     *
     * @return string
     */
    abstract protected function convertToStringRepresentation(Arrayable $body);


    /**
     * Get content type specified for this class.
     *
     * @return string
     */
    abstract protected function getContentType();
}
