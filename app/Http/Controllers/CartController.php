<?php

namespace App\Http\Controllers;

use App\Models\blog_category;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = blog_category::all();

        return view('cart.index', compact('cart'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function addToCart(Request $request)
    {
        // Get the existing cart from cookies (or create an empty array)
        $cart = json_decode($request->cookie('cart_items', '[]'), true);

        // Create new cart item
        $item = [
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => 1, // Default quantity
        ];

        // Add the item to the cart array
        $cart[] = $item;

        // Save the updated cart in a cookie
        $cookie = Cookie::make('cart_items', json_encode($cart), 60 * 24); // Valid for 24 hours

        // Return a success message
        return response()->json(['message' => 'Item added to cart'])->withCookie($cookie);
    }

    // Show cart items
    public function Cartlist(Request $request)
    {

        // Get the cart items from the cookie
       
        $cart = json_decode($request->cookie('cart_items', '[]'), true);

        return view('cart.show', compact('cart'));
    }
}
