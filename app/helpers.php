<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;
use Tamtamchik\SimpleFlash\Flash;
use Rakit\Validation\Validator;

/**
 * Get url for a route by using either name/alias, class or method name.
 *
 * The name parameter supports the following values:
 * - Route name
 * - Controller/resource name (with or without method)
 * - Controller class name
 *
 * When searching for controller/resource by name, you can use this syntax "route.name@method".
 * You can also use the same syntax when searching for a specific controller-class "MyController@home".
 * If no arguments is specified, it will return the url for the current loaded route.
 *
 * @param string|null $name
 * @param string|array|null $parameters
 * @param array|null $getParams
 * @return \Pecee\Http\Url
 * @throws \InvalidArgumentException
*/
function url(?string $name = null, $parameters = null, ?array $getParams = null): Url {
  return Router::getUrl($name, $parameters, $getParams);
}

/**
 * @return \Pecee\Http\Response
 */
function response(): Response {
  return Router::response();
}

/**
 * @return \Pecee\Http\Request
*/
function request(): Request {
  return Router::request();
}

/**
 * Get input class
 * @param string|null $index Parameter index name
 * @param string|mixed|null $defaultValue Default return value
 * @param array ...$methods Default methods
 * @return \Pecee\Http\Input\InputHandler|array|string|null
*/
function input($index = null, $defaultValue = null, ...$methods) {
  if ($index !== null) {
    return request()->getInputHandler()->value($index, $defaultValue, ...$methods);
  }
  return request()->getInputHandler();
}

/**
 * @param string $url
 * @param int|null $code
*/
function redirect(string $url, ?int $code = null): void {
  if ($code !== null) {
    response()->httpCode($code);
  }
  response()->redirect($url);
}
/**
 * Get current csrf-token
 * @return string|null
*/
function csrf_token(): ?string {
  $baseVerifier = Router::router()->getCsrfVerifier();
  if ($baseVerifier !== null) {
    return $baseVerifier->getTokenProvider()->getToken();
  }
  return null;
}

function route() {
  return new Router();
}

function flash() {
  return new Flash();
}

function dd($params) {
  echo '<pre>';
    var_dump($params);
  echo '</pre>';
  die();
}

function pr($params) {
  echo '<pre>';
    print_r($params);
  echo '</pre>';
  die();
}

function validateInput($input, $rules) {
  $validator = new Validator();
  $validation = $validator->validate($input, $rules);
  if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    flash()->error($errors->firstOfAll());
    return false;
  }
  return true;
}

function strReplaceArray($replace, $data) {
  if (!empty($replace)) {
    foreach ($replace as $k => $v) {
      $find = '{{ $' . $k . ' }}';
      $data = str_replace($find, $v, $data);
    }
  }
  return $data;
}

function searchinArray($search, $array, $key) {
  if (is_array($array) && !empty($array)) {
    foreach ($array as $val) {
      if (isset($val[$key]) && $val[$key] === $search) {
        return $val;
      }
    }
  }
  return null;
}