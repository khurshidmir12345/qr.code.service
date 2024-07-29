@extends('layouts.header')
@section('content')

    <main class="content">
        <div class="container-fluid p-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header text-center  text-white">
                            <h3 class="card-title mb-0">Generate QR Code</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{route('admin.qrcodes.create')}}">generator</a>
                            <!-- QR Code Generation Form -->
                            <form action="#" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="qrName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="qrName" name="name" placeholder="Enter name here" required>
                                </div>
                                <div class="mb-3">
                                    <label for="qrLink" class="form-label">Link</label>
                                    <input type="url" class="form-control" id="qrLink" name="link" placeholder="Enter link here" required>
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

