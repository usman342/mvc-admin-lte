<?php 

namespace App\Models;

class Users extends Model {

  public function __construct() {
    parent::__construct();
  }

  public function getUsers() {
    return $this->db->get('accounts');
  }
}