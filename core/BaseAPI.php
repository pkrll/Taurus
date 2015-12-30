<?php
namespace taurus\core;

abstract class BaseAPI {

    private $httpMethod;

    private $resource;

    private $method = kDefaultMethodName;

    private $arguments;

    public function __construct($version, $request = array()) {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->getHttpMethod($_SERVER["REQUEST_METHOD"]);
        $this->parseRequest($request);
        $this->loadResource($version);
    }

    private function getHttpMethod($httpMethod) {
        switch ($httpMethod) {
            case "POST":
                // Check for DELETE or PUT operations
                if ($this->isCustomHttpHeader($_SERVER)) {
                    $this->getCustomHttpMethod($_SERVER);
                } else {
                    $this->httpMethod = $httpMethod;
                }
                break;
            default:
                $this->httpMethod = "GET";
        }
    }

    private function isCustomHttpHeader($header) {
        if (array_key_exists("HTTP_X_HTTP_METHOD", $header)) {
            return TRUE;
        }

        return FALSE;
    }

    private function getCustomHttpMethod($header) {
        $header = $header["HTTP_X_HTTP_METHOD"];
        if ($header == "DELETE" || $header == "PUT") {
            return $header;
        }

        return NULL;
    }
    /**
     *  Parse the URI request and set the end point, API method and arguments based on the different path components.
     *  @param  $paths  The URI Request.
     **/
    private function parseRequest($request = NULL) {
        if (is_null($request)) {
            return false;
        }

        foreach ($request as $key => $path) {
            if (is_null($path)) {
                continue;
            }

            switch ($key) {
                case 0:
                    // The first part of the path (version component has been sorted out by Bootstrap class) determines the end point of the API.
                    $this->resource = ucfirst($path);
                    break;
                case 1:
                    // The second part of the path will either, if numeric, refer to a specific element or a command or method.
                    if (is_numeric($path)) {
                        $this->arguments[] = $path;
                    } else {
                        $this->method = $path;
                    }
                    break;
                default:
                    $this->arguments[] = $path;
                    break;
            }
        }
    }
    /**
     *  Loads the requested end point / resource.
     *  @access private
     **/
    private function loadResource($version) {
        $path = str_replace(kPlaceholder, $version, kResourcesFolder);
        $path = $path .  "{$this->resource}.php";

        if (file_exists($path) === false) {
            throw new \Exception("This endpoint does not exist.");
        }

        if (class_exists($this->resource) === false) {
            include $path;
        }

        new $this->resource($version, $this->httpMethod, $this->method, $this->arguments);
    }

}
