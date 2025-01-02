<?php 

namespace App\Models;

use MysqliDb;

class Model {

  public $db;

  public function __construct() {
    $db = new MysqliDb($_ENV['dbhost'], $_ENV['dbusername'], $_ENV['dbpassword'], $_ENV['dbname']);
    $this->db = $db;
  }
}