<?php

namespace Core\Http;

use Core\Support\Collection;

class Response
{
    protected $headers;
    protected $content;
    protected $version;
    protected $statusCode;
    protected $statusText;
    protected $charset;

    protected $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        422 => 'Unprocessable Entity'
    ];

    public function __construct($content = '', $status = 200, $headers = [], $protocol = '1.1')
    {
        $this->headers = new Collection($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion($protocol);
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }
    
    public function setProtocolVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    public function getProtocolVersion()
    {
        return $this->version;
    }

    public function setStatusCode($statusCode, $statusText = null)
    {
        $this->statusCode = $statusCode;

        if($statusText === null) {
            $this->statusText = $this->statusTexts[$statusCode];
        } else {
            $this->statusText = $statusText;
        }

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function setHeader($header, $value)
    {
        $this->headers->put($header, $value);

        return $this;
    }

    public function sendHeaders()
    {
        foreach ($this->headers as $header => $value) {
            header($header.': '.$value);
        }

        header('HTTP/' . $this->version . ' ' . $this->statusCode . ' ' . $this->statusText);

        return $this;
    }

    public function sendContent()
    {
        echo $this->content;

        return $this;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();

        return $this;
    }
}