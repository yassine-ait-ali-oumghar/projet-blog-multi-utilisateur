<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class postsController extends Controller
{
   public function indexe(){
      $allPosts =[
         ['id' => 1, 'title' => 'PHP',        'posted_by' => 'Ahmed',   'created_at' => '2022-10-10 09:00:00'],
        ['id' => 2, 'title' => 'Javascript', 'posted_by' => 'Mohamed', 'created_at' => '2023-08-20 07:00:00'],
        ['id' => 3, 'title' => 'HTML',       'posted_by' => 'Mahmoud', 'created_at' => '2023-10-06 06:00:00'],
        ['id' => 4, 'title' => 'CSS',        'posted_by' => 'Ali',     'created_at' => '2023-08-07 05:00:00'],
    ];
      
    return view('posts.indexe',['posts'=>$allPosts]);
   }
   public function show($postId){
   
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
      public function update(){
      $title = request()-> title;
       $description = request()->description;
        $postCreator = request()-> postCreator;
                                 
      dd($title,$description,$postCreator);
      return to_route('posts.show',$post['id']);
      }
         public function destroy(){
      return 'we are in ';
      }
}
