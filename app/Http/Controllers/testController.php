<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
public function firstAction(){
       $localName='yass';
      return view('teste',['name'=>$localName, 'books'=>['java','c++']]);
}
}
