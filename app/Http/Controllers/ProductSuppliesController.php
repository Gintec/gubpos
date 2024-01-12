<?php

namespace App\Http\Controllers;

use App\Models\product_supplies;
use App\Models\product_stocks;
use App\Models\sstock;
use App\Models\servicepartsupplies;
use Illuminate\Http\Request;

class ProductSuppliesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $psupplies = product_supplies::paginate(50);
        return view('psupplies', compact('psupplies'));
    }

    public function sparepartSupplies()
    {
        $supplies = servicepartsupplies::paginate(50);
        return view('ssupplies', compact('supplies'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        product_supplies::updateOrCreate(['id'=>$request->id],[
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'cost_per' => $request->cost_per,
            'total_amount' => $request->total_amount,
            'date_supplied' => $request->date_supplied,
            'batchno' => $request->batchno,
            'confirmed_by' => $request->confirmed_by,
            'setting_id'=>Auth()->user()->setting_id

        ]);

        // Update Stock
        product_stocks::updateOrCreate(['product_id'=>$request->product_id],[
            'product_id'=>$request->product_id,
            'added_by' => Auth()->user()->id,
            'facility_location'=>$request->facility_location,
            'internal_location'=>$request->internal_location,
            'dated_added'=>$request->date_supplied,
            'setting_id'=>$request->setting_id

        ])->increment('quantity',$request->quantity);

        $psupplies = product_supplies::paginate(50);

        return view('psupplies', compact('psupplies'));

    }

    public function sSupplies(Request $request){
        sstock::updateOrCreate(['id'=>$request->spart_id],[
            'id'=>$request->spart_id
        ])->increment('quantity',$request->quantity);

        servicepartsupplies::create([
            'part_id' => $request->spart_id,
            'supplier' => $request->supplier_id,
            'quantity' => $request->quantity,
            'date_supplied' => $request->date_supplied,
            'batchno' => $request->batchno,
            'recorded_by' => auth()->user()->name
        ]);

        return redirect()->back()->with(['message'=>'Supply added to sparepart stock']);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\product_supplies  $product_supplies
     * @return \Illuminate\Http\Response
     */
    public function show(product_supplies $product_supplies)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\product_supplies  $product_supplies
     * @return \Illuminate\Http\Response
     */
    public function edit(product_supplies $product_supplies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product_supplies  $product_supplies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product_supplies $product_supplies)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product_supplies  $product_supplies
     * @return \Illuminate\Http\Response
     */
    public function destroy(product_supplies $products)
    {
        product_supplies::findOrFail($id)->delete();
        $message = 'The product supply record has been deleted!';
        return redirect()->route('psupplies')->with(['message'=>$message]);
    }
}
