<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * List all images in the products directory
     */
    public function index()
    {
        $path = public_path('images/products');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $files = File::files($path);

        $images = [];
        foreach ($files as $file) {
            $images[] = [
                'name' => $file->getFilename(),
                'url' => '/images/products/' . $file->getFilename(),
                'size' => $file->getSize(),
                'last_modified' => date('Y-m-d H:i:s', $file->getMTime()),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $images
        ]);
    }

    /**
     * Upload a new image
     */
    public function store(Request $request)
    {
        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                \Illuminate\Support\Facades\Log::info('Upload Attempt:', [
                    'originalName' => $file->getClientOriginalName(),
                    'mimeType' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'error' => $file->getError(),
                    'errorMessage' => $file->getErrorMessage()
                ]);

                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'File upload failed: ' . $file->getErrorMessage()
                    ], 400);
                }
            } else {
                \Illuminate\Support\Facades\Log::info('No file present in request.');
            }

            $request->validate([
                'image' => 'required|file|max:5120', // Increased limit to 5MB, relaxed 'image' rule to 'file' for testing
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

                // Move to public/images/products
                $file->move(public_path('images/products'), $filename);

                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'data' => [
                        'name' => $filename,
                        'url' => '/images/products/' . $filename
                    ]
                ]);
            }

            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Upload Exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
