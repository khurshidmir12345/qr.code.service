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
                            Create Permissions
                        </h4>
                    </div>
                    <form method="POST" action="{{route('admin.permissions.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="name" class="mb-2">Permission Name</label>
                            <input type="text" value="{{old('name')}}" class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}" name="name">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{$errors->first('name')}}
                                </div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-bitbucket">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </main>

@endsection
