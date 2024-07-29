@extends('layouts.header')
@section('content')

    <div class="main">
        <main class="content">
            <div class="container-fluid p-0">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">User details</h5>
                    </div>
                    <div class="card-body h-100">

                        <div class="d-flex align-items-start">
                            <div class="row flex-grow-1 ms-3">
                                <div class="col-3">
                                    <img src="{{asset('img/avatars/avatar.jpg')}}" width="200" height="200" class="me-2"
                                         alt="Vanessa Tucker">
                                </div>
                                <div class="col">
                                    <h3><b>Name:</b><strong class="text-muted ms-5 ">{{$user->name}}</strong></h3>
                                    <hr>
                                    <b>Email:</b><strong class="text-muted ms-5 ">{{$user->email}}</strong><br/>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            @foreach($user->posts as $post)
                                <div class="col-6 col-md-3 mb-3 shadow">
                                    <div class="card" style="background-color: honeydew">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">{{$post->title}}</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{$post->description}}</p>
                                        </div>
                                        <span><a
                                                href="{{route('admin.posts.show',[$post->id])}}">show comments</a></span>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

@endsection
