@extends('layouts.header')
@section('content')

    <main class="content">
        <div class="container-fluid p-0">
                <a href="{{route('admin.qrcodes.create')}}" class="btn btn-outline-success">Create New QrCode</a>
            <table class="table mt-3">
                <thead>
                <th>id</th>
                <th>Name</th>
                <th>Link</th>
                <th>Qr Code</th>
                <th>views</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($qrs as $qr)
                    <tr>
                        <td>{{$loop->iteration}} )</td>
                        <td>{{$qr->name}}</td>
                        <td>{{$qr->generated_link}}</td>
                        <td>
                            <img src="{{ asset($qr->qr_image) }}" alt="QR Code Image" class="img-thumbnail" style="width: 100px; height: 100px;">
                        </td>
                        <td><b style="color: green">+{{ $qr->views }}</b></td>
                        <td>
                            <a href="{{route('admin.qrcodes.show',[$qr->id])}}" style="margin-right: 8px"
                               class="btn btn-info position-relative float-start">
                                <span><i class="align-middle" data-feather="eye"></i></span>
                            </a>
                            <a href="{{route('admin.qrcodes.edit',[$qr->id])}}" style="margin-right: 8px"
                               class="btn btn-info position-relative float-start">
                                <i class="align-middle" data-feather="edit"></i>
                            </a>
                            <form method="POST" class="position-sticky float-start"
                                  action="{{route('admin.qrcodes.destroy',[$qr->id])}}">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger">
                                    <i class="align-middle" data-feather="trash-2"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </main

@endsection

