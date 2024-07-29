<?php

namespace App\Http\Controllers\QrCode;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateQrCodeRequest;
use Illuminate\Support\Facades\Gate;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use App\Models\QrCode;
use Illuminate\Http\Request;


class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('qr_index'), 403);
        $qrs = QrCode::with('user')->where('user_id', auth()->id())->get();
        return view('admin.qrcodes.index',compact('qrs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('qr_create'), 403);
        return view('admin.qrcodes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('qr_create'), 403);
        $name = $request->input('name');
        $link = $request->input('link');

        // Create new QrCode record first to get the ID
        $qr = new QrCode();
        $qr->name = $name;
        $qr->qr_link = $link;
        $qr->qr_image = 'svg';
        $qr->user_id = auth()->id();
        $qr->save();

        // Generate the QR code with the scan route
        $data = route('qrcodes.scan', ['id' => $qr->id]);
        $qrCodeImage = QrCodeFacade::format('svg')->size(200)->generate($data);

        // Ensure the directory exists
        $qrImagesDir = public_path('qr_images');
        if (!file_exists($qrImagesDir)) {
            mkdir($qrImagesDir, 0755, true);
        }

        // Save the QR code image to a file
        $filename = time() . '.svg';  // Use a unique filename
        $filePath = $qrImagesDir . '/' . $filename;
        file_put_contents($filePath, $qrCodeImage);

        // Update the QR code record with the image path
        $qr->qr_image = 'qr_images/' . $filename;
        $qr->save();

        return redirect()->route('admin.qrcodes.index')->with('success', 'QR Code generated successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $qrCode = QrCode::query()->with('user')->findOrFail($id);
        return view('admin.qrcodes.show',compact('qrCode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort_if(Gate::denies('qr_update'), 403);
        $qrCode = QrCode::query()->with('user')->findOrFail($id);

        return view('admin.qrcodes.edit',compact('qrCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('qr_update'), 403);
        $qrCode = QrCode::query()->findOrFail($id);

        $this->authorize('update', $qrCode);

        $name = $request->input('name');
        $link = $request->input('link');

        $data = route('qrcodes.scan', ['id' => $qrCode->id]);
        $qrCodeImage = QrCodeFacade::format('svg')->size(200)->generate($data);

        $qrImagesDir = public_path('qr_images');
        if (!file_exists($qrImagesDir)) {
            mkdir($qrImagesDir, 0755, true);
        }

        $filename = time() . '.svg';
        $filePath = $qrImagesDir . '/' . $filename;
        file_put_contents($filePath, $qrCodeImage);

        $qrCode->name = $name;
        $qrCode->qr_link = $link;
        $qrCode->qr_image = 'qr_images/' . $filename;
        $qrCode->save();

        return redirect()->route('admin.qrcodes.index', $qrCode->id)->with('success', 'QR Code updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('qr_delete'), 403);
        $qrCode = QrCode::query()->findOrFail($id);

        $qrImagePath = public_path($qrCode->qr_image);
        if (file_exists($qrImagePath)) {
            unlink($qrImagePath);
        }

        $qrCode->delete();

        return redirect()->route('admin.qrcodes.index')->with('success', 'QR Code deleted successfully.');
    }

    public function scan($id)
    {
        $qrCode = QrCode::query()->findOrFail($id);

        // Increment the views count
        $qrCode->increment('views');

        // Redirect to the original link
        return redirect($qrCode->qr_link);
    }
}