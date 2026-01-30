<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Display a listing of the berita.
     */
    public function index()
    {
        $beritas = Berita::orderBy('created_at', 'desc')->paginate(9);
        return view('admin.berita.index', compact('beritas'));
    }

    /**
     * Show the form for creating a new berita.
     */
    public function create()
    {
        return view('admin.berita.form', ['berita' => null]);
    }

    /**
     * Store a newly created berita in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'isi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('gambar'), $filename);
            $validated['foto'] = $filename;
        }

        Berita::create($validated);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Display the specified berita.
     */
    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.show', compact('berita'));
    }

    /**
     * Show the form for editing the specified berita.
     */
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.form', compact('berita'));
    }

    /**
     * Update the specified berita in storage.
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        // Track old content to delete old images
        $oldContent = $berita->isi;

        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'isi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($berita->foto && file_exists(public_path('gambar/' . $berita->foto))) {
                @unlink(public_path('gambar/' . $berita->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('gambar'), $filename);
            $validated['foto'] = $filename;
        }

        // Delete unused images from old content
        $this->cleanupUnusedImages($oldContent, $validated['isi']);

        $berita->update($validated);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Cleanup unused images when content is updated
     */
    private function cleanupUnusedImages($oldContent, $newContent)
    {
        try {
            // Extract images from old content
            $pattern = '/<img[^>]+src="([^">]+)"/i';
            preg_match_all($pattern, $oldContent, $oldMatches);
            preg_match_all($pattern, $newContent, $newMatches);

            $oldImages = !empty($oldMatches[1]) ? $oldMatches[1] : [];
            $newImages = !empty($newMatches[1]) ? $newMatches[1] : [];

            // Find images that are in old content but not in new content
            $unusedImages = array_diff($oldImages, $newImages);

            foreach ($unusedImages as $imageUrl) {
                // Only process local images
                if (strpos($imageUrl, asset('gambar/')) === 0) {
                    $filename = basename($imageUrl);
                    $filePath = public_path('gambar/' . $filename);

                    // Delete if file exists and is a CKEditor image (not thumbnail)
                    if (file_exists($filePath) && strpos($filename, 'ckeditor_') === 0) {
                        @unlink($filePath);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error cleaning up unused images: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified berita from storage.
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        // 1. Delete thumbnail photo if exists
        if ($berita->foto && file_exists(public_path('gambar/' . $berita->foto))) {
            @unlink(public_path('gambar/' . $berita->foto));
        }

        // 2. Delete ALL images from CKEditor content
        $this->deleteImagesFromContent($berita->isi);

        $berita->delete();

        return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus!');
    }

    /**
     * Helper method to delete images from CKEditor content
     */
    private function deleteImagesFromContent($content)
    {
        try {
            // Extract all image URLs from content
            $pattern = '/<img[^>]+src="([^">]+)"/i';
            preg_match_all($pattern, $content, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $imageUrl) {
                    // Only process local images (from our server)
                    if (strpos($imageUrl, asset('gambar/')) === 0) {
                        // Extract filename from URL
                        $filename = basename($imageUrl);

                        // Check if file exists and delete it
                        $filePath = public_path('gambar/' . $filename);
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting images from content: ' . $e->getMessage());
        }
    }

    /**
     * Upload images from CKEditor
     */
    public function uploadImage(Request $request)
    {
        try {
            // Validasi CSRF token
            if (!csrf_token()) {
                return response()->json([
                    'uploaded' => 0,
                    'error' => [
                        'message' => 'CSRF token missing'
                    ]
                ], 419);
            }

            // Validasi file
            $validator = Validator::make($request->all(), [
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'uploaded' => 0,
                    'error' => [
                        'message' => $validator->errors()->first('upload')
                    ]
                ], 400);
            }

            if ($request->hasFile('upload')) {
                $file = $request->file('upload');

                // Generate unique filename
                $filename = 'ckeditor_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Ensure directory exists
                $directory = public_path('gambar');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Move file to public/gambar directory
                $file->move($directory, $filename);

                // Generate full URL
                $url = asset('gambar/' . $filename);

                return response()->json([
                    'uploaded' => true,
                    'url' => $url
                ], 200, [], JSON_UNESCAPED_SLASHES);
            }

            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'No file uploaded'
                ]
            ], 400);
        } catch (\Exception $e) {
            \Log::error('CKEditor upload error: ' . $e->getMessage());

            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'Upload failed: ' . $e->getMessage()
                ]
            ], 500);
        }
    }
}
