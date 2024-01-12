<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\product_stocks;
use App\Models\finished_products;
use App\Models\product_sales;
use App\Models\product_damages;
use App\Models\product_supplies;
use App\Models\spareparts;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = products::paginate(50);
        return view('products', compact('products'));
    }

    public function spareparts()
    {
        $spareparts = spareparts::get();
        return view('spareparts', compact('spareparts'));
    }

    public function damages()
    {
        $product_damages = product_damages::paginate(50);
        return view('product-damages', compact('product_damages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function adddProduct(Request $request){
        product_damages::updateOrCreate(['id'=>$request->id],[
            'product_id'=>$request->product_id,
            'invoiceno'=>$request->invoiceno,
            'batchno'=>$request->batchno,
            'reason'=>$request->reason,
            'quantity'=>$request->quantity,
            'dated'=>$request->dated,
            'damaged_by'=>$request->damaged_by,
            'setting_id'=>$request->setting_id
        ]);

        return redirect()->route('product-damages');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'picture'=>'image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        if(!empty($request->file('picture'))){
            // $picture = $request->file('picture')->getClientOriginalName();
            $picture = time().'.'.$request->picture->extension();
            // $path = $request->file('picture')->store('public/images');
            $request->picture->move(\public_path('images\products'),$picture);
        }else{
            if($request->oldpicture==""){
                $picture = "";
            }else{
                $picture = $request->oldpicture;
            }
        }


        $product_id = products::updateOrCreate(['id'=>$request->id],[
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'measurement_unit' => $request->measurement_unit,
            'size' => $request->size,
            'price'=>$request->price,
            'picture'=>$picture,
            'setting_id'=>$request->setting_id

        ])->id;

        product_stocks::updateOrCreate(['product_id'=>$product_id],[
            'product_id'=>$product_id,
            'quantity' => 0,
            'added_by' => Auth()->user()->id,
            'facility_location'=>$request->setting_id,
            'setting_id'=>$request->setting_id
        ]);

        $products = products::paginate(50);

        return view('products', compact('products'));
    }


    public function pjob($batchno)
    {
        $pjob = production_jobs::where('batchno',$batchno)->first();
        $pusedmaterials = material_checkouts::where('production_batch',$batchno)->get();
        $pfinished = finished_products::where('batchno',$batchno)->first();

        $pdf_doc = PDF::loadView('pjob',compact('pjob','pusedmaterials','pfinished'))->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf_doc->stream('pjob'.$batchno.'.pdf');

    }

    public function product($product_id)
    {
        $product = products::where('id',$product_id)->first();
        $pfsupplies = finished_products::where('product_name',$product_id)->get();
        $psupplies = product_supplies::where('product_id',$product_id)->get();
        $psales = product_sales::where('product_id',$product_id)->get();
        return view('product', compact('product','pfsupplies','psupplies','psales'));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */



    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, products $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        products::findOrFail($id)->delete();
        $message = 'The product has been deleted!';
        return redirect()->route('products')->with(['message'=>$message]);
    }

    public function removedproduct($id)
    {
        product_damages::findOrFail($id)->delete();
        $message = 'The damaged product has been deleted!';
        return redirect()->route('product-damages')->with(['message'=>$message]);
    }
}
