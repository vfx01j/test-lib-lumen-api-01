<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/user/{id}', function ($id) use ($app) {
    return 'User '.$id;
});

$app->get('/foo', function () use ($app) {
    return 'Hello World';
});

use Illuminate\Http\Response;

$app->get('/api/library/{id}', function ($id)
{
  if (is_numeric($id))
  {
	   return response()->json(['id'=>$id]);
   }
   else
   {
     return response('Not Found.', 404);
   }
});

use Illuminate\Http\Request;

$app->post('/api/library', ['middleware'=>'auth', function (Request $request) {
  $this->validate($request, [
      'library' => 'required|json' // takes a parameter 'library' JSON representation of a library object
  ]);

  // The request is valid, save Library data...
  $library = $request->input('library');
  return response()->json(['Library json saved'=>$library]);
}]);
