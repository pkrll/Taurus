<?php
namespace Taurus;
require_once(dirname(__DIR__)."/Configurations.php");

class Taurus {
    /**
     *  The URI request.
     *  @access private
     **/
    private $URIRequest;
    /**
     *  The version of the API requested.
     *  @access private
     **/
    private $APIVersion;
    /**
     *  The Bootstrap class will load the requested API.
     *  @access public
     **/
    public function __construct() {
        $this->setAPIVersion();
        $this->loadAPI();
    }
    /**
     *  Set the version of the API to use by parsing the request URI. The first part of the path is the version.
     *  @access private
     **/
    private function setAPIVersion() {
        // The first part of the request should be the version of the API to use.
        $URIRequest = substr($_SERVER["REQUEST_URI"], 1);
        $URIRequest = explode("/", rtrim($URIRequest, "/"));
        $APIVersion = array_shift($URIRequest);

        $this->APIVersion = (is_numeric($APIVersion)) ? $APIVersion : kDefaultAPIVersion;
        $this->URIRequest = $URIRequest;
    }
    /**
     *  Load the API platform, based on which version to use.
     *  @access private
     **/
    private function loadAPI() {
        // Set the paths
        $baseFile = $this->APIVersion . "/" . kDefaultFileName;
        $basePath = kApplicationFolder . $baseFile;
        // Will thrown an error if the file does not exists.
        if (file_exists($basePath) === false) {
            throw new \Exception("API not found.");
        }

        if (class_exists(kDefaultClassName) === false) {
            include $basePath;
        }

        $className = kDefaultClassName;
        new $className($this->APIVersion, $this->URIRequest);
    }
}
