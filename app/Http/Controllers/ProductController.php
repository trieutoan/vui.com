<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
 use Request;
use App\Http\requests\ProductRequest;

use App\Category;
use App\Product;
use Auth;
use App\Product_image;
use File;
use DB;

class ProductController extends Controller
{
    public function index()
    {   
        $products = Product::all();
        return view('admin.product.list', compact('products'));
    }

    public function create()
    {   
        $cate = Category::select('id', 'name')->get();
        return view('admin.product.add', compact('cate'));
    }

    public function store(ProductRequest $request)
    {
        $filename = $request->file('fImages')->getClientOriginalName();
        $product = new Product();
        $product->name = $request->txtName;
        $product->alias = changeTitle($request->txtName);
        $product->intro = $request->txtIntro;
        $product->image = $filename;
        $product->view = 0;
        $product->comment = 0;
        $product->hot = $request->rdoStatus;
        $product->source = $request->source;
        $product->user_id = Auth::user()->id;
        $product->cate_id = $request->category;
        $product->save();
        $request->file('fImages')->move('upload/images/', $filename);
        $product_id = $product->id;

        if ($request->hasFile('Image_details')) {
            foreach ($request->file('Image_details') as $file) {
                $product_img = new Product_image();
                if (isset($file)) {
                    $product_img->image = $file->getClientOriginalName();
                    $product_img->product_id = $product_id;
                    $file->move('upload/images/details/', $file->getClientOriginalName());
                    $product_img->save();
                }
            }
        }
        return redirect()->route('admin.product.index')
            ->with(['flash_level' => 'success', 'flash_message' => 'Success !! Complate Add Product']);
    }

    public function edit($id)
    {  
        $cate = Category::all();
        $product = Product::find($id);
        $product_image = Product::find($id)->product_image;
        return view('admin.product.edit', compact('product', 'cate', 'product_image'));

    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);
        $product->name = request::input('txtName');
        $product->alias = changeTitle(request::input('txtName'));
        $product->intro = request::input('txtIntro');
        $product->view = 0;
        $product->comment = 0;
        $product->hot = request::input('rdoStatus');
        $product->source = request::input('source');
        $product->user_id = Auth::user()->id;
        $product->cate_id = request::input('category');

        $image_current = 'upload/images/'.Request::input('img_current');
        
        if (!empty(Request::file('fImages'))) {
            $file_name = Request::file('fImages')->getClientOriginalName();
            $product->image = $file_name;
            Request::file('fImages')->move('upload/images/', $file_name);

            if (File::exists($image_current)) {
                File::delete($image_current);
            }
        } else {
            echo "Not Exists File !";
        }

        $product->save();
        if (!empty(Request::file('fImgdetail'))) {
            foreach (Request::file('fImgdetail') as $fileimg) {
                $image_product = new Product_image();
                if (isset($fileimg)) {
                    $image_product->image = $fileimg->getClientOriginalName();
                    $image_product->product_id = $id;
                    $fileimg->move('upload/images/details/',$fileimg->getClientOriginalName());
                    $image_product->save();
                }
            }
        }
        else {
            echo "not file";
        }
        
        return redirect()->route('admin.product.index')
            ->with(['flash_level' => 'success', 'flash_message' => 'Success !! Complate Update Product']);
    }


    public function destroy($id)
    {
        $product_detail = Product::find($id)->product_image->toArray();
        foreach ($product_detail as $value) {
            File::delete('upload/images/details/'.$value['image']);
        }

        $product = Product::find($id);
        File::delete('upload/images/'.$product->image);
        $product->delete();

        return redirect()->route('admin.product.index')
            ->with(['flash_level' => 'success', 'flash_message' => 'Success !! Complate Delete Product']);

    }

    public function delimg($id)
    {
        if (Request::ajax()) {
            $idhinh = (int)Request::get('idHinh');
            $image_detail = Product_image::findOrFail($idhinh);
            if (!empty($image_detail)) {
                $img = 'upload/images/details/'.$image_detail->image;
                if (File::exists($img)) {
                    File::delete($img);
                }
                $image_detail->delete();
            }
            return "success";
        }
    }

}
