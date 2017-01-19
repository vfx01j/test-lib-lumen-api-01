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

use App\User;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

$app->post('/api/library', ['middleware'=>'auth', function (Request $request) {
  $this->validate($request, [
      'library' => 'required|json' // takes a parameter 'library' JSON representation of a library object
  ]);

  // The request is valid, save Library data...
  $library = $request->input('library');
  $user = $request->user();

  $root_dir = realpath('./../storage/logs');
  $adapter = new Local($root_dir);
  $filesystem = new Filesystem($adapter);
  $content = ($filesystem->has('save.log')) ? $filesystem->read('save.log') : '';
  $filesystem->put('save.log', $content.$library.PHP_EOL); // File is appended, although it's not efficient. It's better use database in production.

  Log::info('Authorized user saved library object: '.$library); // Logging
  return response()->json(['Library json saved'=>$library]); // json response
}]);
