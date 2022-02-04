<?php

namespace App\Http\Controllers;

use App\Models\Product;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderBy('id','desc')->paginate(10);
        $category= Category::get()->all();
        return view('product',compact('product','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    if($request->ajax())
     {
      $product = Product::orderBy('id','desc')->paginate(10);
      return view('product_pagination', compact('product'))->render();
     }
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'category' => 'required',
            'product_name' => 'required',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
          if($validation->passes())
          {
            $image = $request->file('product_image');
            $new_name = time() . '.' . $image->getClientOriginalExtension();
            $location_name='product_images/'. $new_name;
            $path=$image->move(public_path('product_images'), $new_name);
            $product=new Product;
            $product->category_id=$request->input('category');
            $product->product_name=$request->input('product_name');
            
            
            $product->product_image=$location_name;
            $product->save();
            return response()->json([
                'message'   => 'Product Added Successfully',
                'class_name'  => 'alert-success'
            ]);
          }
          else{
            return response()->json([
                'message'   => $validation->errors()->all(),
                'class_name'  => 'alert-danger'
               ]);

          }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product=Product::find($id);
        if($product){
           return response()->json([
               'status'=>200,
               'product'=>$product,
           ]);   
        }
        else{
           return response()->json([
               'status'=>404,
               'message'=>'Product Not Found',
           ]);   
        }   
          
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validation = Validator::make($request->all(), [
            'edit_category_id' => 'required',
            'edit_product_name' => 'required',
            
            
          ]);
          if($validation->fails())
          {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'class_name'  => 'alert-danger'
               ]);

           
          }
          else{
           
            
            $product=Product::find($id);
            if($product){
                $product->product_name=$request->input('edit_product_name');
                $product->category_id=$request->input('edit_category_id');
                
                
               
            
            if($request->hasFile('edit_product_image'))
            {
                $path_info = $product->product_image;
                if(File::exists($path_info)){
                    File::delete($path_info);
                }
                $image = $request->file('edit_product_image');
                $new_name = time() . '.' . $image->getClientOriginalExtension();
                $location_name='product_images/'. $new_name;
                $path=$image->move(public_path('product_images'), $new_name);
                $product->product_image= $location_name;
            }
            $product->save();
            return response()->json([
           'message'   => 'Product Updated Successfully',
           'class_name'  => 'alert-success'
       ]);
    }
            else{
                return response()->json([
                    'message'   => 'Product Not Found',
                    'class_name'  => 'alert-danger',
                ]);

            }
            
            
          }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     $product=Product::find($id);
     $product->delete();
     return response()->json([
        'status'=>200,
        'message'=>'Product Deleted Successfully',
    ]);    
    }
}
