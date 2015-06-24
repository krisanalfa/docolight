<?php

namespace Docolight\Http;

use ArrayAccess;
use CApplicationComponent;

/**
 * This is a simple abstraction over top an HTTP response. This
 * provides methods to set the HTTP status, the HTTP headers,
 * and the HTTP body.
 *
 * ```php
 * $response = new Docolight\Http\Response(new Docolight\Http\Headers(array('Content-Type' => 'text/html')));
 *
 * // Or you can resolve via factory
 * // $response = with(new Docolight\Http\ResponseFactory())->produce();
 *
 * // Move even shorter way
 * // $response = container('response')->produce();
 *
 * // Set your header
 * $response->headers->set('Foo', 'Bar');
 *
 * // Set your response status
 * $response->setStatus(202);
 *
 * // Set your response body
 * $response->setBody('PUT YOUR HTML HERE');
 *
 * // Send your response to client
 * $response->send();
 *
 * // In shorter way
 * // response(
 * //    'base',
 * //    200,
 * //    'PUT YOUR HTML HERE',
 * //    array('Foo' => 'Bar')
 * // )->send();
 * ```
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Response extends CApplicationComponent implements ArrayAccess
{
    /**
     * @var int HTTP status code
     */
    protected $status = 200;

    /**
     * @var \Docolight\Http\Headers
     */
    public $headers = null;

    /**
     * @var string HTTP response body
     */
    protected $body = '';

    /**
     * @var int Length of HTTP response body
     */
    protected $length = 0;

    /**
     * @var array HTTP response codes and messages
     */
    protected static $messages = array(
        // Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        102 => '102 Processing',
        // Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        207 => '207 Multi-Status',
        208 => '208 Already Reported',
        226 => '226 IM Used',
        // Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        308 => '308 Permanent Redirect',
        // Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        418 => '418 I\'m a teapot',
        419 => '419 Authentication Timeout',
        420 => '420 Method Failure',
        421 => '421 Misdirected Request',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        424 => '424 Failed Dependency',
        426 => '426 Upgrade Required',
        428 => '428 Precondition Required',
        429 => '429 Too Many Requests',
        431 => '431 Request Header Fields Too Large',
        451 => '451 Unavailable For Legal Reasons',
        // Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported',
        506 => '506 Variant Also Negotiates',
        507 => '507 Insufficient Storage',
        508 => '508 Loop Detected',
        509 => '509 Bandwidth Limit Exceeded',
        510 => '510 Not Extended',
        511 => '511 Network Authentication Required',
        520 => '520 Unknown Error',
        598 => '598 Network Read Timeout Error',
        599 => '599 Network Connect Timeout Error',
    );

    /**
     * Constructor
     *
     * @param \Docolight\Http\Headers $headers
     */
    public function __construct(Headers $headers)
    {
        $this->headers = $headers;

        $this->init();
    }

    /**
     * Get current status code
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set current HTTP statuc sode
     *
     * @param int $status Current status code
     *
     * @return \Docolight\Http\Response
     */
    public function setStatus($status)
    {
        $this->status = (int) $status;

        return $this;
    }

    /**
     * Return HTTP response Body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set current HTTP response body
     *
     * @param string $content
     *
     * @return \Docolight\Http\Response
     */
    public function setBody($content)
    {
        $this->write($content, true);

        return $this;
    }

    /**
     * Append HTTP response body.
     *
     * @param string $body    Content to append to the current HTTP response body
     * @param bool   $replace Overwrite existing response body?
     *
     * @return \Docolight\Http\Response
     */
    public function write($body, $replace = false)
    {
        $body = (string) $body;

        if ($replace) {
            $this->body = $body;
        } else {
            $this->body .= $body;
        }

        $this->length = strlen($this->body);

        return $this;
    }

    /**
     * Get current response length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Finalize prepares this response and returns an array
     * of [status, headers, body].
     *
     * @return array[int status, array headers, string body]
     */
    public function finalize()
    {
        // Prepare response
        if (in_array($this->status, array(204, 304))) {
            $this->headers->remove('Content-Type');
            $this->headers->remove('Content-Length');
            $this->setBody($this->getEmpty());
        }

        return array($this->status, $this->headers, $this->getBody());
    }

    /**
     * Send response to client
     *
     * @return void
     */
    public function send()
    {
        list($status, $headers, $body) = $this->finalize();

        $this->prepareHeaders($status, $headers);
        $this->sendBody($body);
    }

    /**
     * Send body
     *
     * @param string $body
     *
     * @return void
     */
    protected function sendBody($body)
    {
        if (trim(strtoupper(def($_SERVER, 'REQUEST_METHOD', ''))) !== 'HEAD') {
            echo $body;
        }
    }

    /**
     * Prepare headers before we send them
     *
     * @param int                     $status
     * @param \Docolight\Http\Headers $headers
     *
     * @return void
     */
    protected function prepareHeaders($status, Headers $headers)
    {
        if (headers_sent() === false) {
            // Send status
            if (strpos(PHP_SAPI, 'cgi') === 0) {
                header(sprintf('Status: %s', static::getMessageForCode($status)));
            } else {
                header(sprintf('HTTP/%s %s', '1.1', static::getMessageForCode($status)));
            }

            // Send headers
            foreach ($headers as $name => $header) {
                foreach (explode("\n", $header) as $value) {
                    header("$name: $value", false);
                }
            }
        }
    }

    /**
     * Redirect.
     *
     * This method prepares this response to return an HTTP Redirect response
     * to the HTTP client.
     *
     * @param string $url    The redirect destination
     * @param int    $status The redirect HTTP status code
     *
     * @return \Docolight\Http\Response Maintain chaining access.
     */
    public function redirect($url, $status = 302)
    {
        $this->setStatus($status);
        $this->headers->set('Location', $url);

        return $this;
    }

    /**
     * Determine if value is available.
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->headers[$offset]);
    }

    /**
     * Get value
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->headers[$offset];
    }

    /**
     * Set value
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->headers[$offset] = $value;
    }

    /**
     * Remove a value
     *
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->headers[$offset]);
    }

    /**
     * Get message for HTTP status code.
     *
     * @param int $status
     *
     * @return string|null
     */
    public static function getMessageForCode($status)
    {
        $status = (int) $status;

        return isset(self::$messages[$status]) ? self::$messages[$status] : null;
    }

    /**
     * Set custom HTTP Status Code message
     *
     * @param int    $status
     * @param string $message
     */
    public static function setMessage($status, $message)
    {
        $status = (int) $status;

        static::$messages[$status] = $messages;
    }

    /**
     * Initialize component.
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Default empty data
     *
     * @return mixed
     */
    protected function getEmpty()
    {
        return '';
    }
}
