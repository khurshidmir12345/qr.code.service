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
        <div class="container-fluid p-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header text-center  text-white">
                            <h3 class="card-title mb-0">Generate QR Code</h3>
                        </div>
                        <div class="card-body">
                            <!-- QR Code Generation Form -->
                            <form action="{{route('admin.qrcodes.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">QR title</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="enter name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="qrLink" class="form-label">Link</label>
                                    <input type="url" class="form-control" id="qrLink" name="link" placeholder="https://hello-world.com" required>
                                </div>
                                <button type="submit" class="mt-3  btn btn-outline-success w-100">Generate QR Code</button>
                            </form>

                            <!-- QR Code Display -->
                            @isset($qrCode)
                                <div class="mt-4 text-center">
                                    <h4>Your Generated QR Code:</h4>
                                    <div class="d-inline-block p-3 border rounded bg-light">
                                        {!! $qrCode !!}
                                    </div>
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
