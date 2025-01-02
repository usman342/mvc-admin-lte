<?php 

namespace App\Controllers;

use App\Utils\Template;
use App\Models\Users;

class AffController extends Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index() {
    $users = new Users();
    print_r($users->getUsers()); die;
  }
}