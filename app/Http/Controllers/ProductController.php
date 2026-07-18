<?php
/*
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
*/

//===
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Product;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {

        $product = Product::all();
        // dd($product);
        return view('products.index')->with('product' , $product);

    }


    public function create()
    {
        return view('products.create');
    }


    public function store(Request $request)
    {

    }


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
