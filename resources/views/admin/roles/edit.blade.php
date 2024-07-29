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
                            Edit Role
                        </h4>
                    </div>
                    <form method="POST" action="{{route('admin.roles.update',[$role->id])}}" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group mb-4">
                            <label for="name" class="mb-2">Role Name</label>
                            <input type="text" value="{{old('name',$role->name)}}" class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}" name="name">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{$errors->first('name')}}
                                </div>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label class="required" for="permissions">Roles</label>
                            <select type="permissions" value="{{old('permissions')}}" class="form-control {{$errors->has('permissions') ? 'is-invalid' : ''}}" name="permissions[]" id="permissions" multiple required>
                                @foreach($permissions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                                @if($errors->has('permissions'))
                                    <div class="invalid-feedback">
                                        {{$errors->first('permissions')}}
                                    </div>
                                    @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </main>

@endsection
