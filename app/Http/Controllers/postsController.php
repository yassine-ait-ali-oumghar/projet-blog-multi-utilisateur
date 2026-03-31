<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
class postsController extends Controller
{
   public function indexe(){
      $postsFronDB= Post::all();
      
 
    return view('posts.indexe',['posts'=>$postsFronDB]);
   }
   public function show($postId){
    $singlePostFromDB =  Post::find($postID);
           $singlePost = [
        'title'       => 'PHP',
        'description' => 'PHP is cool language',
        'Name'        => 'Yassine',
        'Email'       => 'Yassine@gmail.com',
        'created_at'  => 'thursday 25th of december 1975 02:15:16 PM'
    ];
   
      return view('posts.show',['posts'=>$singlePost]);
   }
   public function create(){
       return view('posts.create');
   }
     public function store(){
      $data= request()->all();
                                 
      dd($data);
      return to_route('posts.indexe');
     }
      public function edit(){
       return view('posts.edit');
   }
      public function update($postId){
      $title = request()-> title;
       $description = request()->description;
        $postCreator = request()-> postCreator;
                                 
      dd($title,$description,$postCreator);
      return to_route('posts.show',$postId);
      }
         public function destroy(){
      return to_route('posts.indexe');
      }
}
