<?php
namespace taurus\core;
use saturn\database\Database;

abstract class Model {
    /**
     * The database object.
     *
     * @var Database
     * @access protected
     **/
    protected $database;

    public function __construct() {
        if (!empty(kHostname) && !empty(kUsername) && !empty(kPassword) && !empty(kDatabase)) {
            $this->database = new Database(kHostname, kDatabase, kUsername, kPassword);
        }
    }
    /**
     * Create an error message.
     *
     * @param   string  The error message.
     * @return  array
     */
    final protected function errorMessage($message) {
        return array(
            "error" => array("message" => $message)
        );
    }

}
