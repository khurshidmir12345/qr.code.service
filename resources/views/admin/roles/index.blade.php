@extends('layouts.header')

@section('content')

    <main class="content">
        <div class="container-fluid p-0">
            <a href="{{route('admin.roles.create')}}" class="btn btn-success">Create New Role</a>
            <table class="table">
                <thead>
                <th>id</th>
                <th>Name</th>
                <th>Permissions</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{$loop->iteration}} )</td>
                        <td>{{$role->name}}</td>
                        <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-primary">{{$permission->name}}</span>
                                @endforeach
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-4">
                                    <a href="{{route('admin.roles.edit',$role->id)}}" style="margin-right: 8px" class="btn btn-info position-relative float-lg-start">
                                        <i class="align-middle" data-feather="edit"></i>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <form  method="POST" action="{{route('admin.roles.destroy',[$role->id])}}">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-danger"><i class="align-middle" data-feather="trash-2"></i></button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </main

@endsection
