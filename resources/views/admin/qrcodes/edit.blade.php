@extends('layouts.header')

@section('content')

    <main class="content">
        @if($errors->count() > 0)
            <div class="container-fluid text-danger mb-3"
                 style="background-color: antiquewhite; padding: 15px; border-radius: 15px">
                <ul class="list-unstyled ">
                    @foreach($errors->all() as $error)
                        <li class="text-lg-start">{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-body">
                    <div class="mb-5">
                        <h4>
                            Edit Qr Code
                        </h4>
                    </div>
                    <form method="POST" action="{{route('admin.qrcodes.update',$qrCode->id)}}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group mb-4">
                            <label for="name">Name</label>
                            <input type="text" value="{{old('name',$qrCode->name)}}" class="form-control"
                                   name="name" {{$errors->has('name') ? 'is-invalid' : ''}}>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{$errors->first('name')}}
                                </div>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="link">Link</label>
                            <input type="url" value="{{old('link',$qrCode->qr_link)}}" class="form-control"
                                   name="link" {{$errors->has('link') ? 'is-invalid' : ''}}>
                            @if($errors->has('link'))
                                <div class="invalid-feedback">
                                    {{$errors->first('link')}}
                                </div>
                            @endif
                        </div>
                        <button type="submit" style="width: 20%" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </main>

@endsection

