<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductFashionController extends Controller
{
    public function viewProductFashion()
    {
        $products = $this->productRandomFashion();
        return view('client.pages.product-fashion', compact('products'));
    }

    // trả 15 sản phẩm fashion ngẫu nhiên từ bảng product
    public function productRandomFashion()
    {
        $products = DB::table('products')
            ->join('categories', 'products.categorie_id', '=', 'categories.id')
            ->where('categories.name', 'thời trang')
            ->distinct()
            ->inRandomOrder()
            ->take(15)
            ->get(['products.id', 'products.name', 'products.old_price', 'products.new_price', 'products.thumbnail']);

        return $products;
    }

    // sắp xếp 15 sản phẩm fashion
    public function getSortedProductFashion($sort)
    {
        if ($sort !== 'asc' && $sort !== "desc") {
            throw new InvalidArgumentException("Invalid sort parameter must be asc or desc");
        }
        $orderBy = ($sort === 'asc') ? 'new_price' : 'new_price desc';
        $sortedProducts = DB::table('products')
            ->join('categories', 'products.categorie_id', '=', 'categories.id')
            ->where('categories.name', 'thời trang')
            ->orderByRaw($orderBy)
            ->take(15)
            ->get(['products.id', 'products.name', 'products.old_price', 'products.new_price', 'products.thumbnail']);

        return $sortedProducts;
    }

    // lấy ra 15 sản phẩm fashion đã sắp xếp
    public function sortProductFashion(Request $request, $sort = 'desc')
    {
        $products = $this->getSortedProductFashion($sort);

        return view('client.pages.product-dress', compact('products', 'sort'));
    }
}
