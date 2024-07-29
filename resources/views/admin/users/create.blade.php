@extends('layouts.header')

@section('content')

    <main class="content">
        @if($errors->count() > 0)
            <div class="container-fluid text-danger mb-3" style="background-color: antiquewhite; padding: 15px; border-radius: 15px">
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
                            Create User
                        </h4>
                    </div>
                    <form method="POST" action="{{route('admin.users.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="name">Name</label>
                            <input type="text" value="{{old('name')}}" class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}" name="name">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{$errors->first('name')}}
                                </div>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Email</label>
                            <input type="email" value="{{old('email')}}" class="form-control {{$errors->has('email') ? 'is-invalid' : ''}}" name="email">
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{$errors->first('email')}}
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-4">
                            <label class="required" for="roles">Roles</label>
                            <select  type="roles" value="roles" class="form-control {{$errors->has('roles') ? 'is-invalid' : ''}}" name="roles[]" id="roles" multiple required>
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
                            <input type="password" class="form-control {{$errors->has('password') ? 'is-invalid' : ''}}" name="password">
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{$errors->first('password')}}
                                </div>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="password_confirmation">Password Confirmed</label>
                            <input type="password" class="form-control {{$errors->has('password') ? 'is-invalid' : ''}}" name="password_confirmation" >
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{$errors->first('password')}}
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
