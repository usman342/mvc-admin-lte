<?php

namespace App\Controllers;

use App\Utils\Template;

class WelcomeController extends Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index() {
    echo 'Hello World';
    // Template::view('dashboard.html');
  }

  public function notFound() {
    Template::view('error.html');
  }
}