<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Retun all the caegories with status 200 (ok) in json.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // fetch categories with thei products
        $categories = Category::with('products')->get();

        return response()->json($categories);
    }


    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request values
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|unique:categories|max:255'
        // ]);

        // //if validation fails ( has errors) store the err msg in $response
        // if( $validator->fails() ) {

        //     // set the validator error message to variable then return it
        //     $message =  $validator->messages();
                
        //     return response()->json('message', $message);

        // } else {
            // else if no errrors then store the category in DB
            $category = Category::create([
                'name' => $request->name
            ]);

            // return status, and the data of the category added and message 
            return response()->json([
                'status'     => (bool) $category,
                'category'   => $category,
                'message'    => $category ? 'Category Created!' : 'Error Creating Category'
            ]);
        // }
       
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // update product
        $category->update(
            // only get name from request
            $request->only('name')
        );

        // Return status of HTTP request with message
        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {   
        // Delete the record 
        $deletedCat = $category->delete();

        // Return status of HTTP request with message
        return response()->json([
            'deletedCat' => $deletedCat
        ]);
    }
}
