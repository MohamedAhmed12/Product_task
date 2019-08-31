<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get 8 random products
        $products = Product::paginate(15);

        // itterate through products to convert each images str to array 
        foreach ($products as $product) {
            $product->image = explode(',', $product->image);
        }

        // $products = Ca::with('category ')->get();
         

        return response()->json($products,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product;
        
        $product->name        = $request->name;
        $product->price       = $request->price;
        $product->images      = $request->images;
        $product->category_id = $request->category_id;

        $product->save();


        return response()->json([
            // 'status' => (bool) $product,
            'product'   => $product,
            // 'message' => $product ? 'Product Created!' : 'Error Creating Product'
        ]);
    }

    /**
     * Display the specified Product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Main Prouct with it's category as nested object 

        $product = Product::where('id', $id)->with('category')->firstOrFail();

        return response()->json([
            'product' => $product, 
            'id'      => $id
        ]); 
    }

    /**
     * Upload the image and store it in public/images then retrive the path to be use in updating the product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadFile(Request $request)
    {
        // set images array to store the paths to ech product images in it 
        $images = [];
        // if array of images sent with request
        if($request->hasFile('images')){
            // itterate through it 
            foreach($request->file('images') as $image)
            {  
                // rename the image
                $name = time()."_".$image->getClientOriginalName();
                // store the image
                $image->move(public_path('images'), $name);
                // add the name to images array so we can pass it to product record in database
                array_push( $images, asset('/images/' . $name) );  
            }
        }

        // return stored images paths
        return response()->json($images, 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // $validate = $this->validate($request,[
        //                 'name'      => 'required|min:3',
        //                 'price'     => 'required|max:250|digits_between:0,10',
        //                 'category'  => 'required|unique',
        //                 'image'     => 'image|nullable|max:1999' //nullable means its optional and max storage
        //             ]);
     
        // update the product
        $product->name        = $request->name;
        $product->price       = $request->price;
        $product->images      = $request->images;
        $product->category_id = $request->category_id;
        
        $product->save();
        
        // resend request to Get Main Prouct with it's category as nested object 
        $category = Category::find( $request->category_id);

        return response()->json([
            'product'   => $product,
            'category'  => $category,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $status = $product->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Order Deleted!' : 'Error Deleting Order'
        ]);
        }
}
