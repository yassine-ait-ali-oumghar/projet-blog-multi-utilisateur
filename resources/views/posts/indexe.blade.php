@extends('layaout.app')
@section('title') Index @endsection

@section('content')
<div class="d-flex justify-content-center">
<div class="text-center" >
<a href="{{route('posts.create')}}" class="btn btn-success mb-3">Create Poste</a>
</div>
</div>
<div class="d-flex justify-content-center"> 
  <table class="table w-75">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Title</th>
      <th scope="col">Posted By</th>
      <th scope="col">Created At</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($posts as $post)
   
    <tr>
      <td>{{$post['id']}}</td>
      <td>{{$post['title']}}</td>
      <td>{{$post['posted_by']}}</td>
      <td>{{$post['created_at']}}</td>
      <td>
        <div class="btn-group" role="group">
          <a href="{{route('posts.show',$post['id'])}}" class="btn btn-info">Show</a>
          <a href="{{route('posts.edit',$post['id'])}}" class="btn btn-primary">Edit</a>
          <a href="a" class="btn btn-danger">Delete</a>
        </div>
      </td>
    </tr>
     @endforeach
  </tbody>
</table>
@endsection