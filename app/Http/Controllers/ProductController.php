<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    
    $query = Product::with('category');

    
    
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%")
              ->orWhereHas('category', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
        });
    }
    
    $totalProducts = Product::count();
    $inStockProducts = Product::where('stock', '>', 10)->count();
    $lowStockProducts = Product::whereBetween('stock', [1, 10])->count();
    $outOfStockProducts = Product::where('stock', 0)->count();
    
    $products = $query->paginate(5);
    $categories = Category::all();
    $suppliers = Supplier::all();
    return view('products.index', compact(
        'products',
        'categories',
        'suppliers',
        'totalProducts',
        'inStockProducts',
        'lowStockProducts',
        'outOfStockProducts',
        'search'
    ));
}
    // Menampilkan form tambah produk
    public function create()
    {
        $suppliers = Supplier::all();
        $categories = Category::all();
        $sizes = Size::all();
        return view('products.create', compact('suppliers', 'categories', 'sizes'));
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'cost' => 'nullable|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'nullable|array',
            'sizes.*.name' => 'required|string|max:255',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        // Generate kode produk
        $code = Product::generateProductCode($request->category_id);

        // Upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Simpan produk
        $product = Product::create([
            'code' => $code,
            'name' => $request->name,
            'price' => $request->price,
            'cost' => $request->cost ?? 0,
            'stock' => $request->stock,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'image' => $imagePath,
        ]);

        // Simpan ukuran dan stok
        if ($request->has('sizes')) {
            foreach ($request->sizes as $size) {
                $sizeModel = Size::firstOrCreate(['name' => $size['name']]);
                $product->sizes()->attach($sizeModel->id, ['stock' => $size['stock']]);
            }
        }

        // Generate barcode
        $product->generateBarcode();

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        $categories = Category::all();
        $sizes = Size::all();
        return view('products.edit', compact('product', 'suppliers', 'categories', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'cost' => 'nullable|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'sizes.*.name' => 'required|string|max:255',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        $data = $request->all();

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        // Simpan data produk
        $product->update($data);

        // Perbarui ukuran dan stok
        if ($request->has('sizes')) {
            // Siapkan data untuk sync: [size_id => ['stock' => x], ...]
            $syncData = [];
            $totalStock = 0;
            foreach ($request->sizes as $size) {
                $sizeModel = Size::firstOrCreate(['name' => $size['name']]);
                $stock = isset($size['stock']) ? (int) $size['stock'] : 0;
                $syncData[$sizeModel->id] = ['stock' => $stock];
                $totalStock += $stock;
            }

            // Sinkronkan pivot (akan menambah, menghapus, atau memperbarui stok sesuai data)
            $product->sizes()->sync($syncData);

            // Perbarui stok utama produk agar mencerminkan jumlah stok per ukuran
            $product->update(['stock' => $totalStock]);
        }

        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            return response()->json(['message' => 'Produk berhasil diperbarui.']);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function generateBarcode($code)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        return response($barcode)->header('Content-Type', 'image/png');
    }
    public function findByCode(Request $request)
    {
        $code = $request->query('code'); // Ambil kode dari query parameter
        $product = Product::where('code', $code)->first(); // Cari produk berdasarkan kode

        return response()->json($product); // Kembalikan data produk dalam format JSON
    }

    public function downloadBarcode($id)
    {
        $product = Product::findOrFail($id);

        // Generate barcode
        $barcodeImage = DNS1D::getBarcodePNG($product->barcode, 'C128');

        // Set header untuk response
        $headers = [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $product->barcode . '.png"',
        ];

        // Return response dengan gambar barcode
        return response($barcodeImage, Response::HTTP_OK, $headers);
    }

    public function printBarcodes(Request $request, $id)
    {
        $products = Product::where('id', $id)
            ->get();
        
        $quantity = $request->input('quantity', 1);
        
        return view('products.barcode', compact('products', 'quantity'));
    }

    // Fill missing cost for products (admin action)
    public function fillMissingCost(Request $request)
    {
        // Only allow admin middleware at route level; extra safety: could check here
        $products = Product::with('sizes')->get();
        $updated = 0;
        foreach ($products as $p) {
            $effectiveStock = 0;
            if ($p->relationLoaded('sizes') && $p->sizes->count() > 0) {
                $effectiveStock = $p->sizes->sum('pivot.stock');
            } else {
                $effectiveStock = (int) ($p->stock ?? 0);
            }

            if ((!isset($p->cost) || $p->cost == 0) && $effectiveStock > 0) {
                $p->cost = $p->price * 0.6;
                $p->save();
                $updated++;
            }
        }

        return redirect()->back()->with('success', "Telah mengisi nilai cost untuk {$updated} produk (fallback price * 0.6).");
    }


}