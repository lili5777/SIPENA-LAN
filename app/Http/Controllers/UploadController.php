<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    //
    public function index()
    {
        return view('upload.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120|mimes:pdf,doc,docx,jpg,png|max:10120'
        ]);

        $file = $request->file('file');
        $nama = $request->input('nama', 'dokumen') . '_' . time() . '.' . $file->extension();
        $path = 'dokumen/' . $nama;
        Storage::disk('google')->put($path, File::get($file));

        return redirect()
            ->route('upload.index')
            ->with('success', 'Upload sukses!');
    }
}
