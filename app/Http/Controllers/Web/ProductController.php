<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;

    public static function middleware(): array
    {
        return [
            new Middleware('auth',     except: ['publicIndex', 'show']),
            new Middleware('verified', except: ['publicIndex', 'show']),

            new Middleware('permission:product.read',   only: ['index']),
            new Middleware('permission:product.create', only: ['create','store']),
            new Middleware('permission:product.update', only: ['edit','update']),
            new Middleware('permission:product.delete', only: ['destroy']),
        ];
    }

    public function publicIndex(Request $request)
    {
        $q = $request->string('q')->toString();
        $products = Product::query()
            ->when($q, fn($qq) => $qq->where(function($w) use ($q){
                $w->where('name','like',"%$q%")
                  ->orWhere('sku','like',"%$q%");
            }))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Home/Index', [
            'filters' => ['q' => $q],
            'products' => $products,
        ]);
    }

    /** Admin list */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $q = $request->string('q')->toString();
        $products = Product::query()
            ->when($q, fn($qq) => $qq->where(function($w) use ($q){
                $w->where('name','like',"%$q%")
                  ->orWhere('sku','like',"%$q%");
            }))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'filters' => ['q' => $q],
            'products' => $products,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Product::class);
        return Inertia::render('Products/Create');
    }

    public function store(ProductStoreRequest $request)
    {
        $this->authorize('create', Product::class);
        Product::create($request->validated());
        return redirect()->route('admin.products.index')->with('success','Product created');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $this->authorize('update', $product);
        $product->update($request->validated());
        return redirect()->route('admin.products.index')->with('success','Product updated');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();
        return back()->with('success','Product deleted');
    }
}
