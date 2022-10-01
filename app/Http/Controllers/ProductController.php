<?php

namespace App\Http\Controllers;
use App\Models\Image;
use App\Models\Product;
use Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->get();
        return response()->json([
            'success' => true,
            'errors' => null,
            'data' => [
                'shareableLink' => "https://www.temporary-url.com/D85696",
                'products' => $products
]
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'productName'       => 'required',
            'price'             => 'required',
            'cost'              => 'required',
            'image.*'           => 'url'
        ];
        $messages = [
            'productName.required' => 'Enter Product Name',
            'price.required' => 'Enter Price',
            'cost.required' => 'Enter Cost',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->errors()->first()){
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
                'data'    => null,
            ],422);
        }
        try {
            $product = Product::create([
                'productName'      => $request->productName,
                'price'             => $request->price,
                'cost'              => $request->cost,
                'description'       => $request->description,
                'unit'              => $request->unit,
                'weightPerUnit'   => $request->weightPerUnit
            ]);

            $productImages = [];
            foreach($request->image as $image) {
                $productImages[] = [
                    'image'         => $image,
                    'productId'    => $product->id,
                ];
            }
            Image::insert($productImages);



            return response()->json([
                'success'   => true,
                'errors'    => null,
                'data'      => $product->load('images')
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success'   => true,
                'errors'    => [
                    $exception->getMessage(),
                ],
                'data'      => null
            ]);
        }
    }
}
