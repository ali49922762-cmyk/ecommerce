<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Policies\ProductPolicy;

class ProductController extends Controller
{
     public function index(){
        $products = Product::get();
        return response()->json($products);  
    }

    public function show(Product $id){
        return response()->json($id);
    }


    public function store(Request $request)
    {
        // $user=$request->user();

        //$this->authorize('create',Product::class);

        //$policy=new ProductPolicy;

        // if($policy->create($user)){
        //     return response()->json([
        //         'status'=>false,
        //         'message'=>'unauthorized'
        //     ]);
        // }

        // if(!$user){
        //     return $this->unauthorized([
        //         'message'=>'unauthorized',
        //     ]);
        // }

        $request->validate([
            'name'=>['required','string','max:255'],
            'description'=>['required','string'],
            'price'=> ['required','integer','min:0'],
        ]);

        // $validate['user_id']=$request->user()->id;

        $product = Product::create([
            'user_id'     => Auth::id(), 
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
        ]);

        return response()->json([
            'message' => [
                'en'=>'Product created successfully',
                'ar'=>'تم إضافة المنتج بنجاح'
            ],
            'data'    => $product
        ]);
    }


    public function update(Request $request, $id)
    {
        $product=Product::findOrFail($id);

        $request->validate([
            'name'        => ['required','string','max:255'],
            'description' => ['required','string'],
            'price'       => ['required','integer','min:0'],
        ]);

        $product->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
        ]);

        return response()->json([
            'message' => [
                'en' => 'Product updated successfully',
                'ar' => 'تم تعديل المنتج بنجاح'
        ],
            'data' => $product
        ]);
    }

    public function delete($id)
    {

    $product = Product::findOrFail($id);

    $product->delete();

        return response()->json([
            'message' => [
                'en' => 'Product deleted successfully',
                'ar' => 'تم حذف المنتج بنجاح'
            ]
        ]);
    }
    
}