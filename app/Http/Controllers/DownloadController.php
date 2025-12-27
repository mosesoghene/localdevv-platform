<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Product;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Generate a secure download token for a product
     */
    public function generateToken(Product $product)
    {
        $user = Auth::user();

        // Check if user has purchased this product
        if (!$user->hasCompletedOrderForProduct($product->id)) {
            abort(403, 'You must purchase this product before downloading it.');
        }

        // Generate unique download token
        $token = Str::random(64);

        // Create download record
        Download::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $user->orders()->where('product_id', $product->id)->where('status', 'completed')->first()?->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'download_token' => $token,
            'downloaded_at' => now(),
        ]);

        // Redirect to download with token
        return redirect()->route('download.file', ['token' => $token]);
    }

    /**
     * Download file using secure token
     */
    public function download(Request $request, string $token)
    {
        // Find download record by token
        $download = Download::where('download_token', $token)->firstOrFail();

        // Verify the download belongs to the authenticated user
        if ($download->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to download.');
        }

        // Check if token is still valid (24 hours)
        if ($download->downloaded_at->lt(now()->subHours(24))) {
            abort(410, 'Download link has expired. Please request a new one.');
        }

        $product = $download->product;

        // Check if file exists
        if (!$this->fileService->fileExists($product->file_path)) {
            abort(404, 'Product file not found.');
        }

        $filePath = $this->fileService->getFilePath($product->file_path);
        $fileName = Str::slug($product->name) . '-v' . ($product->version ?? '1.0') . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

        // Stream the file download
        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Show user's purchased products with download links
     */
    public function myProducts()
    {
        $user = Auth::user();

        // Get all products the user has purchased
        $purchasedProducts = Product::whereHas('orders', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'completed');
        })->with(['orders' => function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'completed')
                  ->latest();
        }])->get();

        return view('user.products', compact('purchasedProducts'));
    }
}
