@extends('layaout.app')
@section('title') Show @endsection
@section('content')
<div class="card">
  <div class="card-header">
    post Info
  </div>
  <div class="card-body">
    <h5 class="card-title">Title:{{$posts['title']}}</h5>
    <p href="#">Description: {{$posts['description']}}</p>
  </div>
</div><div class="card">
  <div class="card-header">
    post Info
  </div>
  <div class="card-body">
    <h5 class="card-title">Name: {{$posts['Name']}}</h5>
    <p class="card-text">Email: {{$posts['Email']}}</p>
    <p href="#">Created at: {{$posts['created_at']}}/p>
  </div>
</div>
@endsection