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
                            Edit User
                        </h4>
                    </div>
                    <form method="POST" action="{{route('admin.users.update',$user->id)}}"
                          enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group mb-4">
                            <label for="name">Name</label>
                            <input type="text" value="{{old('name',$user->name)}}" class="form-control"
                                   name="name" {{$errors->has('name') ? 'is-invalid' : ''}}>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{$errors->first('name')}}
                                </div>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Email</label>
                            <input type="email" value="{{old('email',$user->email)}}" class="form-control"
                                   name="email" {{$errors->has('email') ? 'is-invalid' : ''}}>
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{$errors->first('email')}}
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-4">
                            <label class="required" for="roles">Roles</label>
                            <select class="select2 form-select" type="roles" value="roles" name="roles[]" id="roles"
                                    multiple required>
                                @foreach($roles as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                                @if($errors->has('roles'))
                                    <div class="invalid-feedback">
                                        {{$errors->first('roles')}}
                                    </div>
                                @endif
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password">Password</label>
                            <input type="password" class="form-control"
                                   name="password" {{$errors->has('password') ? 'is-invalid' : ''}}>
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{$errors->first('password')}}
                                </div>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="password_confirmation">Password Confirmed</label>
                            <input type="password" class="form-control"
                                   name="password_confirmation" {{$errors->has('password_confirmation') ? 'is-invalid' : ''}}>
                            @if($errors->has('password_confirmation'))
                                <div class="invalid-feedback">
                                    {{$errors->first('password_confirmation')}}
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
