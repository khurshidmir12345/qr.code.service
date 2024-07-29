@extends('layouts.header')

@section('content')

    <main class="content">
        <div class="container-fluid p-0">
            <a href="{{route('admin.permissions.create')}}" class="btn btn-success">Create New Permisssion</a>
            <table class="table">
                <thead>
                <th>id</th>
                <th>Name</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{$loop->iteration}} )</td>
                        <td>{{$permission->name}}</td>
                        <td>
                            <a href="{{route('admin.permissions.edit',$permission->id)}}" style="margin-right: 8px" class="btn btn-info position-relative float-lg-start">
                                <i class="align-middle" data-feather="edit"></i>
                            </a>

                            <form  method="POST" action="{{route('admin.permissions.destroy',[$permission->id])}}">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger"><i class="align-middle" data-feather="trash-2"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </main

    @endsection()
