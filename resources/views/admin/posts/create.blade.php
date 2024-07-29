@extends('layouts.header')

@section('content')

    <main class="content">
        @if($errors->count() > 0)
            <div class="container-fluid text-danger mb-3" style="background-color: antiquewhite; padding: 15px; border-radius: 15px">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $error)
                        <li class="text-lg-start">{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-body row">
                    <div class="mb-5">
                        <h4>
                            Generate QR Code
                        </h4>
                    </div>
                    <form method="POST" action="#" enctype="multipart/form-data">

                        @csrf
                        <div class="form-group mb-4">
                            <label for="title" class="mb-2">QR Code Title</label>
                            <input type="text" value="{{old('title')}}" class="form-control {{$errors->has('title') ? 'is-invalid' : ''}}" name="title">
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{$errors->first('title')}}
                                </div>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="description" class="mb-2">QR Code Link</label>
                            <input type="text" value="{{old('description')}}" class="form-control {{$errors->has('description') ? 'is-invalid' : ''}}" name="description">
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{$errors->first('description')}}
                                </div>
                            @endif
                        </div>
                        <button class="btn btn-success">save</button>
                    </form>
                </div>
            </div>

        </div>
    </main>

@endsection
