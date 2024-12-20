@extends('layouts.header')
@section('content')

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3>QR Code Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="{{ asset($qrCode->qr_image) }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">

                            </div>
                            <div class="col-md-8">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $qrCode->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>QR-Link:</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ $qrCode->qr_link }}" target="_blank" id="qr-link">{{ $qrCode->qr_link }}</a>
                                                <button class="btn btn-outline-secondary align-right btn-sm ms-2 copy-btn" data-clipboard-target="#qr-link">
                                                    <i class="align-middle" data-feather="copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Generated-link:</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span id="generated-link">{{ $qrCode->generated_link ? $qrCode->generated_link : route('qrcodes.scan',['id' => $qrCode->id]) }}</span>
                                                <button class="btn btn-outline-secondary btn-sm ms-2 copy-btn" data-clipboard-target="#generated-link">
                                                    <i class="align-middle" data-feather="copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Views:</th>
                                        <td>{{ $qrCode->views }}</td>
                                    </tr>
                                    <tr>
                                        <th>Generated By:</th>
                                        <td>{{ $qrCode->user->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                            <a href="{{ route('admin.qrcodes.index') }}" class="btn btn-danger">
                                Back to List </a>
                            <a href="{{ asset($qrCode->qr_image) }}" download="{{ $qrCode->name }}.png" class="btn btn-outline-success">
                                Save QR Image  <i class="align-middle" data-feather="save"></i></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($qrCode->qr_link) }}" class="btn btn-outline-primary" target="_blank">Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($qrCode->qr_link) }}" class="btn btn-outline-info " target="_blank">Twitter</a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($qrCode->qr_link) }}" class="btn btn-outline-primary" target="_blank">LinkedIn</a>
                            <a href="mailto:?subject=Check out this QR Code&body={{ urlencode($qrCode->qr_link) }}" class="btn btn-outline-secondary" >Email</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
