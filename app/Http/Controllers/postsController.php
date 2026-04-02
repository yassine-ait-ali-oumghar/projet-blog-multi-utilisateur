<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
class postsController extends Controller
{
   public function indexe(){
      $postsFronDB= Post::all();
      
 
    return view('posts.indexe',['posts'=>$postsFronDB]);
   }
   public function show(Post $post){

   //  $singlePostFromDB =  Post::findOrfail($post);
//   if(is_null($singlePostFromDB)){
//    return to_route('posts.indexe');
//   }

      return view('posts.show',['posts'=>$post]);
   }
   public function create(){
      $users= User::all(); 
       return view('posts.create',['users'=> $users]);
   }
     public function store(){
      $data= request()->all();
       $title = request()-> title;
       $description = request()->description;
        $postCreator = request()-> postCreator;                
      //   $post=new Post;
      //   $post->title=$title; 
      //   $post->description=$description; 
      //   $post->save();
      
      Post::create([
         'title'=>$title,
          'description'=>$description,
      ]);
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
