<?php
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

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

$app->get('/api/library/{id}', function ($id) {
    $v = Validator::make(['id'=>$id], [
      'id' => 'required|integer|min:0'
    ], [
      'id.min' => 'Positive id is required.'
    ]);
    if ($v->passes()) {
        $root_dir = realpath('./../storage/logs');
        $adapter = new Local($root_dir);
        $filesystem = new Filesystem($adapter);
        $content = $filesystem->read('save.log');
        $array_content = json_decode($content, true);
        if (is_null($array_content)) {
            $result = response($v->messages(), 404); // Return Not Found response
        } else {
            foreach ($array_content as $library) { // Search for the first array with matching id
            if ($library['id'] && $library['id']==$id) {
                $result = response()->json($library); // Assign library json to response
                break;
            }
            }
        }
    } else {
        $result = response($v->messages(), 404); // Return Not Found response
    }
    return $result;
});

$app->post('/api/library', ['middleware'=>'auth', function (Request $request) {
    $this->validate($request, [
    'library' => 'required|json'
  ]);
    $data = json_decode($request->input('library'), true)[0];
    $v = Validator::make($data, [
    'id' => 'required|integer|min:0',
    'code' => 'required|regex:/^(\w{3})(\d{3})$/',
    'name' => 'required|string',
    'abbr' => 'required|string',
    'url' => 'required|url'
  ], [
    'id.min' => 'Positive id is required.',
    'code.regex' => 'Code must contain a 3 character, 3 number combination like ABC123.'
  ]);
    if ($v->passes()) {
        // The request is valid, it's time to save Library data...
        $library = $request->input('library');
        $user = $request->user();
        // Store json to local file
        $root_dir = realpath('./../storage/logs');
        $adapter = new Local($root_dir);
        $filesystem = new Filesystem($adapter);
        $content = ($filesystem->has('save.log')) ? $filesystem->read('save.log') : '';
        $filesystem->put('save.log', $content.$library.PHP_EOL); // Store json string to local file. Use database in production for better performance.
        Log::info('Authorized user saved library object: '.$library); // Logging
        $result = response()->json(['Library json saved'=>$library]); // return json response
    } else {
        $result = response($v->messages(), 400); // return Bad Request response
    }
    return $result;
}]);

/* Support for /api/findSmallestLeaf */

/* Debugging */
$test_json = '{
  "root": 1,
  "left": {
    "root": 7,
    "left": {
      "root": 2
    },
    "right": {
      "root": 6
    }
  },
  "right": {
    "root": 5,
    "left": {
      "root": 9
    }
  }
}';

$test_obj = json_decode($test_json);
/* End of Debugging */

function findSmallestLeaf($obj)
{
    $sLeaf = null;
    $candidates = findSmallerLeaves($obj);
    $sLeaf = min($candidates);
    return $sLeaf;
}

$list = array();

function findSmallerLeaves($obj)
{
    global $list;
    if (!isset($obj->left) && !isset($obj->right)) { //Cofirm as leaf if no left/right node existed
        $list[] = $obj->root;
    }
    if (isset($obj->left)) { //Keep searching if left node existed
        findSmallerLeaves($obj->left);
    }
    if (isset($obj->right)) { //Keep searching if right node existed
        findSmallerLeaves($obj->right);
    }
    return $list;
}
/* End of Support for /api/findSmallestLeaf */

//Debugging api
$app->get('/api/test/findSmallestLeaf', function () use ($test_obj) {
    return findSmallestLeaf($test_obj);
});

//Official api
$app->get('/api/findSmallestLeaf', function (Request $request) {
    $this->validate($request, ['tree' => 'required|json']);

    // The request is valid
    $tree = $request->input('tree');
    $tree_obj = json_decode($tree)[0];
    $result = findSmallestLeaf($tree_obj);
    Log::info('JSON Tree fed in for processing: '.$tree); // Logging
    return $result;
});
