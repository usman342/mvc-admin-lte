<?php 

route()->setDefaultNamespace('App\Controllers');

route()->get('/', 'WelcomeController@index');


/* route()->get('/not-found', 'WelcomeController@notFound');
route()->get('/forbidden', 'WelcomeController@notFound');

route()->error(function(\Pecee\Http\Request $request, \Exception $exception) {
  switch($exception->getCode()) {
    // Page not found
    case 404:
      response()->redirect('/not-found');
    // Forbidden
    case 403:
      response()->redirect('/forbidden');
  }
}); */

route()->start();