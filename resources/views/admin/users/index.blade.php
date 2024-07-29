@extends('layouts.header')
@section('content')

    <main class="content">
        <div class="container-fluid p-0">
            @can('user_create')
                <a href="{{route('admin.users.create')}}" class="btn btn-success">Create New User</a>
            @endcan
            <table class="table">
                <thead>
                <th>id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{$loop->iteration}} )</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary">{{$role->name}}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-4">
                                    @can('user_edit')
                                        <a href="{{route('admin.users.show',$user->id)}}" style="margin-right: 8px"
                                           class="btn btn-info position-relative float-lg-start">
                                            <span>show</span>
                                        </a>
                                    @endcan
                                </div>
                                <div class="col-2">
                                    @can('user_delete')
                                        <form method="POST" action="{{route('admin.users.destroy',[$user->id])}}">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger">
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                                <div class="col-2">
                                    <a href="{{route('admin.users.edit',$user->id)}}" style="margin-right: 8px"
                                       class="btn btn-info position-relative float-lg-start">
                                        <i class="align-middle" data-feather="edit"></i>
                                    </a>
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

