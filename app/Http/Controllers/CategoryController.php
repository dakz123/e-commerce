<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::orderBy('id','desc')->paginate(10);
        return view('category',compact('category'));
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
      $category = Category::orderBy('id','desc')->paginate(10);
      return view('category_pagination', compact('category'))->render();
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
            'category_name' => 'required',
            'category_description' => 'required',
            'category_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
          if($validation->passes())
          {
            $image = $request->file('category_image');
            $new_name = time() . '.' . $image->getClientOriginalExtension();
            $location_name='category_images/'. $new_name;
            $path=$image->move(public_path('category_images'), $new_name);
            $category=new Category;
            $category->category_name=$request->input('category_name');
            $category->category_description=$request->input('category_description');
            
            $category->category_image=$location_name;
            $category->save();
            return response()->json([
                'message'   => 'Category Added Successfully',
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category=Category::find($id);
        if($category){
           return response()->json([
               'status'=>200,
               'category'=>$category,
           ]);   
        }
        else{
           return response()->json([
               'status'=>404,
               'message'=>'Category Not Found',
           ]);   
        }   
          
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validation = Validator::make($request->all(), [
            'edit_category_name' => 'required',
            'edit_category_description' => 'required',
            
            
          ]);
          if($validation->fails())
          {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'class_name'  => 'alert-danger'
               ]);

           
          }
          else{
           
            
            $category=Category::find($id);
            if($category){
                $category->category_name=$request->input('edit_category_name');
                $category->category_description=$request->input('edit_category_description');
                
                
               
            
            if($request->hasFile('edit_category_image'))
            {
                $path_info = $category->category_image;
                if(File::exists($path_info)){
                    File::delete($path_info);
                }
                $image = $request->file('edit_category_image');
                $new_name = time() . '.' . $image->getClientOriginalExtension();
                $location_name='category_images/'. $new_name;
                $path=$image->move(public_path('category_images'), $new_name);
                $category->category_image= $location_name;
            }
            $category->save();
            return response()->json([
           'message'   => 'Category Updated Successfully',
           'class_name'  => 'alert-success'
       ]);
    }
            else{
                return response()->json([
                    'message'   => 'Category Not Found',
                    'class_name'  => 'alert-danger',
                ]);

            }
            
            
          }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category=Category::find($id);
     $category->delete();
     return response()->json([
        'status'=>200,
        'message'=>'Category Deleted Successfully',
    ]);    
    }
}
