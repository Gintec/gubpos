<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\categories;
use App\Models\transactions;
use App\Models\spareparts;
use App\Models\sstock;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('user')->get();
        return view('services', compact('services'));
    }

    public function create()
    {
        $users = User::all();
        $categories = categories::select('category_group','title','id')->get();
        $spareparts = spareparts::all();
        return view('new-service', compact('users','categories','spareparts'));
    }

    public function store(Request $request){
        Service::updateOrCreate(['id' => $request['id']],$request->all());
        // RECORD TRANSACTION

        if($request->amountpaid>0 && $request->amountpaid==$request->amount){
            $pay_status = "Paid";
            $balance = 0;
        }else if($request->amountpaid>0){
            $pay_status= "Part Payment";
            $balance = $request->amount-$request->amountpaid;
        }else{
            $pay_status="Not Paid";
            $balance = $request->amount;
        }
        $group_id =  substr(md5(uniqid(mt_rand(), true).microtime(true)),0, 7);

        transactions::create([
            'title'=>"Customer Service",
            'amount'=>$request->amount,
            'account_head' => 6,
            'dated' => $request->date_started,
            'reference_no' => $group_id,
            'detail' => $request->description,
            'from' => $request->customer,
            'to' => Auth()->user()->id,
            'approved_by' => Auth()->user()->id,
            'recorded_by' => Auth()->user()->id,
            'payment_status' => $pay_status,
            'transaction_id' => $group_id,
            'balance' => $balance,
            'beneficiary' => Auth()->user()->setting_id,
            'setting_id' => Auth()->user()->setting_id
        ]);

        return redirect()->route('services')->with(['message'=>'New Service created successfully']);
    }
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $users = User::all();
        $categories = categories::select('title')->get();

        return view('edit-service', compact('service', 'users','categories'))->with(['message'=>'Service Modified and Saved Successfully']);
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->back()->with(['message'=>'Service deleted']);
    }
}
