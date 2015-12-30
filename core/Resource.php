<?php
namespace Taurus;

abstract class Resource {

    protected $name;

    protected $version;

    protected $model;

    protected $httpMethod;

    protected $arguments;

    protected $statusCode = 200;

    protected $statusCodes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded',
        999 => 'Unknown Internal Server Error'
    );

    public function __construct($version, $httpMethod = "GET", $method = kDefaultMethodName, $arguments = NULL) {
        if (!method_exists($this, $method)) {
            throw new \Exception("Call to undefined method.");
        }

        $this->name         = get_class($this);
        $this->version      = $version;
        $this->model        = $this->loadModel();
        $this->httpMethod   = $httpMethod;
        $this->arguments    = $arguments;

        $this->$method();
    }

    protected function response($data, $statusCode = 200) {
        header("HTTP/1.1 {$statusCode} " . $this->getStatusCodeDefinition($statusCode));
        echo json_encode($data);
    }

    protected function getStatusCodeDefinition($statusCode) {
        if (isset($this->statusCodes[$statusCode]) === false) {
            $statusCode = 999;
        }

        return $this->statusCodes[$statusCode];
    }

    private function loadModel() {
        // The model must have the same
        // name as requested resource.
        $name = $this->name.'Model';
        $path = str_replace(kPlaceholder, $this->version, kModelsFolder);
        $path = $path . $name . ".php";
        // If it does not exist, go with
        // the default Model class.
        if (!file_exists($path)) {
            return new Model();
        } else {
            if (!class_exists($name)) {
                include $path;
            }

            return new $name();
        }
    }
}
