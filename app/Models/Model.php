<?php 

namespace App\Models;

use MysqliDb;

class Model {

  public $db;

  public function __construct() {
    $db = new MysqliDb($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
    $this->db = $db;
  }
}