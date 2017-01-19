<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class LibraryController extends BaseController
{
  /**
   * Show the profile for the given user.
   *
   * @param  Request $request
   * @return Response
   */
   public function store(Request $request)
   {
     $this->validate($request, [
         'library' => 'required|json' // takes a parameter 'library' JSON representation of a library object
     ]);

     // The request is valid, save Library data...
     $library = $request->input('library');
     return response()->json(['Library json saved'=>$library]);
    }
}
