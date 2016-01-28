# Taurus [![Latest Stable Version](https://poser.pugx.org/saturn/taurus/v/stable)](https://packagist.org/packages/saturn/taurus) [![License](https://poser.pugx.org/saturn/taurus/license)](https://packagist.org/packages/saturn/taurus)
A simple, small, easy, etcetera, API framework.
### Installation
##### With Composer (recommended):
```bash
$ composer require saturn/taurus
```
##### Setup
Add a .htaccess file to your root directory, to redirect all requests to the file that should launch Taurus, for example:
```htaccess
Options -Indexes
RewriteEngine on
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_METHOD} ^(get|post)$ [NC]
RewriteRule ^ index.php [QSA,L]
```
Create a folder named ``application`` at root level. This folder will contain the API(s). The API folder must be named after it's version.

The folder structure should look something like this:
```
- application/
   - 1.0/
     - Resources/
        + Example.php      // The end point
     - Models/
        + ExampleModel.php // The Model file for Example (optional)
     - BaseAPI.php (Each API version must extend the Base API class.)
   - 1.1/
     - Resources/
     - Models/
     - BaseAPI.php
- index.php
- .htaccess
```
### Usage
Run Taurus in your ``index.php`` file.
```php
use taurus\core\Taurus;

include "vendor/autoload.php";

try {
    new Taurus();
} catch (Exception $e) {
    // Error handling
}
```
Each API version must extend the BaseAPI class.
```php
// Add it to the API version's base folder, i.e. /application/1.0/BaseAPI.php
use taurus\core\BaseAPI;

class API extends BaseAPI {

}
```
To actually start using Taurus, you need to create ``Resources`` with end points.

#### Create Resources & End Points
All resources should be put inside the ``Resources`` folder in the appropriate API folder under ``application``. The Resource classes must extend the base class ``Resource`` and have a ``main`` method (this is the default method called).

Below follows an example on how to create an end point named ``Example`` with two end points: Main (``/``) and remove.
```php
// /application/1.0/Resources/Example.php
// This end point is accessible by calling: http://api.example.dev/1.0/Example or:
//                                          http://api.example.dev/1.0/Example/remove/15
use taurus\core\Resource;

class Example extends Resource {

    protected function main() {
        $data = $this->model->getExamples($id);
        
        if (isset($data["error"])) {
            $data = $data["error"];
            $this->statusCode = 500;
        }
        
        $this->response($data, $this->statusCode);
    }
    
    protected function remove() {
        $id = $this->arguments[0];
        
        if (isset($id)) {
            $response = $this->model->deleteExample($id);
            $this->statusCode = 200;
        } else {
            $response = "No id given";
            $this->statusCode = 400;
        }

        $this->response($response, $this->statusCode);
    }

}
```
#### Create Models for your Resources
All business logic should be put inside a model class. Models must be paired with exactly one resource file and have the same name, followed by "Model", and extend the base model class ``Model``.

Taurus includes a database wrapper class, accessible through the class property database defined in ``Model``. See [Database](https://github.com/pkrll/Database) for more information on how to use the database connection.

To connect to the database, you must first define and set the following constants in your config file:
   - ``kHostname``: The hostname on which the database server resides.
   - ``kDatabase``: The name of the database.
   - ``kUsername``: The username.
   - ``kPassword``: The password.

Below is an example on how to use a model, based on the example above.
```php
// /application/1.0/Resources/ExampleModel.php
use taurus\core\Model;

class ExampleModel extends Model {

    public function getExamples() {
        $query = "SELECT name FROM Examples";
        return $this->database->read($query);
    }

    public function deleteExample($id) {
        $query = "DELETE FROM Examples WHERE id = :id";
        $param = array("id"  => $id);
        
        $response = $this->database->write($query, $param);
        
        return $response;
    }

}
```
## Author
Taurus was created by Ardalan Samimi.
