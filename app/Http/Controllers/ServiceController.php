<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\categories;
use App\Models\transactions;
use App\Models\spareparts;
use App\Models\sstock;
use App\Models\serviceparts;
use App\Models\servicequestions;

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
        $categories = categories::select('category_group','title','id','description')->orderBy('id','asc')->get();
        $spareparts = spareparts::all();
        return view('new-service', compact('users','categories','spareparts'));
    }

    public function store(Request $request){
        if($request->id==0){
            $type = "New service saved ";
        }else{
            $type = "The service was updated ";
        }
        if(isset($request->takenparts) && $request->takenparts=="Yes"){
            $status = "In Progress";
        }else{
            $status = "Quotation";
        }
        $service_id = Service::updateOrCreate(['id' => $request['id']],
        [
            'customer'=>$request->customer,
            'start_date'=>$request->start_date,
            'completion_date'=>$request->completion_date,
            'category'=>$request->category,
            'description'=>$request->description,
            'status'=>$status,
            'amount'=>$request->amount,
            'amountpaid'=>$request->amountpaid,
        ])->id;
        // RECORD TRANSACTION

        if($request->amountpaid>0 && $request->amountpaid==$request->amount){
            $pay_status = "Paid";
            $balance = 0;
        }elseif($request->amountpaid>0){
            $pay_status= "Part Payment";
            $balance = $request->amount-$request->amountpaid;
        }else{
            $pay_status="Not Paid";
            $balance = $request->amount;
        }
        $group_id =  substr(md5(uniqid(mt_rand(), true).microtime(true)),0, 7);


        if(isset($request->sparepart) && $request->sparepart[0]!=0){
            foreach($request->sparepart as $key=>$part){
                serviceparts::updateOrCreate(['part_id'=>$part,'service_id'=>$service_id],[
                    'part_id'=>$part,
                    'quantity'=>$request->quantity[$key],
                    'service_id'=>$service_id
                ]);
                if(isset($request->takenparts) && $request->takenparts=="Yes"){
                    sstock::updateOrCreate(['sparepart'=>$part],[
                        'sparepart'=>$part,
                    ])->decrement('quantity',$request->quantity[$key]);
                }
            }
        }
        $categories = categories::select('category_group','title','id')->where('category_group','Technical Questions')->get();

        foreach($categories as $question){
            $qname = 'answer'.$question->id;
            if(isset($request->$qname) && $request->$qname!=''){
                servicequestions::updateOrCreate(['question_no'=>$question->id,'service_id'=>$service_id],[
                    'question_no'=>$question->id,
                    'answer'=>$request->$qname,
                    'service_id'=>$service_id
                ]);
            };
        };

        transactions::create([
            'title'=>"Customer Service",
            'amount'=>$request->amount,
            'account_head' => 6,
            'dated' => $request->date_started,
            'reference_no' => $service_id,
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

        return redirect()->route('services')->with(['message'=>$type.' successfully']);
    }
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $users = User::all();
        $spareparts = spareparts::all();
        $categories = categories::select('category_group','title','id','description')->orderBy('id','asc')->get();

        return view('edit-service', compact('service', 'users','categories','spareparts'));
    }

    public function getProducts()
    {
        $products = spareparts::all(); // Replace 'Product' with your model name

        return response()->json($products);
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->back()->with(['message'=>'Service deleted']);
    }
}
