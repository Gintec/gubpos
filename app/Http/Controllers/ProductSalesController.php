<?php

namespace App\Http\Controllers;

use App\Models\product_sales;
use App\Models\products;
use App\Models\product_stocks;
use App\Models\User;
use App\Models\delivery;
use App\Models\returns;
use Illuminate\Support\Facades\Hash;
use PDF;

use App\Models\transactions;
use Illuminate\Http\Request;
use App\Mail\SendPDFEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProductSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = product_sales::where('pay_status','!=','Proforma')->paginate(50);
        return view('sales', compact('sales'));
    }

    public function searchSales(Request $request){
        // Validate the incoming request data
        $request->validate([
            'filter_from' => 'required|date',
            'filter_to' => 'required|date|after_or_equal:filter_from',
        ]);

        // Retrieve sales data within the specified date range
        $sales = product_sales::whereBetween('dated_sold', [$request->filter_from, $request->filter_to])->paginate(50);

        // Pass the results to the view
        return view('sales', ['sales' => $sales]);

        // $sales = product_sales::where('pay_status','!=','Proforma')->paginate(50);
        // return view('sales', compact('sales'));
    }
    public function invoices()
    {
        $transactions = transactions::where('account_head',1)->orderBy('id','desc')->paginate(50);
        $users = User::select('id','name')->get();

        return view('invoices', compact('transactions','users'));
    }

    public function generateInvoiceReport(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $transactions = transactions::whereBetween('dated', [$from, $to])->where('account_head',1)->orderBy('id','desc')->get();
        $users = User::select('id','name')->get();
        return view('invoice_report', compact('transactions','users','from','to'));
    }

    public function proformas()
    {
        $transactions = transactions::where('account_head',5)->orderBy('id','desc')->paginate(50);
        $users = User::select('id','name')->get();

        return view('proformas', compact('transactions','users'));
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
        $amount_paid = str_replace(',', '',$request->amount_paid);
        $total_due = str_replace(',', '',$request->total_due);
        $balance = 0;

        if($amount_paid==$total_due){
            $pay_status = "Paid";
            $balance = 0;
        }elseif($amount_paid>$total_due){
            $pay_status = "Overpaid";
            $balance = $amount_paid-$total_due;
        }elseif($amount_paid<$total_due){
            $pay_status = "Part Payment";
            $balance = $total_due-$amount_paid;
        }else{
            $pay_status = "Not Paid";
            $balance = $total_due;
        }
        $delivery_fee = 0;

        if(isset($request->delivery_fee)){
            $delivery_fee=$request->delivery_fee;
        }
        $address = "";


        if (User::where('id', '=', $request->customer)->exists()) {
            $buyer = $request->customer;
            $address = user::select('address')->where('id',$buyer)->first()->address;
        }elseif($request->customer=='New'){
                $email = 'guest@gubabi.com';
                if(isset($request->email) && $request->email!=''){
                    $email = $request->email;
                }

                $password = Hash::make("prayer22");
                $buyer = User::create([
                    'name' => $request->customer_name,
                    'email' => $email,
                    'phone_number'=>$request->phone_number,
                    'dob' => $request->dated_sold,
                    'password' => $password,
                    'about' => $request->details,
                    'address' => $request->address,
                    'role'=>"Customer",
                    'category'=>"Customer",
                    'status'=>"InActive",
                    'setting_id' => Auth()->user()->setting_id
                ])->id;

                $address = $request->address;
        }else{
                $buyer = 1;
        }

         if($request->group_id==""){
            $group_id =  substr(md5(uniqid(mt_rand(), true).microtime(true)),0, 7);
         }else{
            $group_id = $request->group_id;
         }



        foreach($request->product_id as $key => $product_id){

            product_sales::create([
                'product_id' => $product_id,
                'quantity' => $request->qty[$key],
                'sales_person' => Auth()->user()->id,
                'confirmed_by' => Auth()->user()->id,
                'buyer' => $buyer,
                'price' => str_replace(',', '',$request->unit[$key]),
                'amount_paid' => str_replace(',', '',$request->amount[$key]),
                'pay_status' => $pay_status,
                'dated_sold' => $request->dated_sold,
                'group_id' => $group_id,
                'details' => $request->details,
                'setting_id'=>Auth()->user()->setting_id
            ]);

            // Update Product Stock
            product_stocks::updateOrCreate(['product_id'=>$product_id],[
                'product_id'=>$product_id,
            ])->decrement('quantity',$request->qty[$key]);

        }

        // RECORD TRANSACTION
        $tid = transactions::create([
            'title'=>"Item Sales - Invoice No: ".$group_id,
            'amount'=>$total_due+$delivery_fee,
            'account_head' => 1,
            'dated' => $request->dated_sold,
            'reference_no' => $group_id,
            'detail' => $request->details,
            'from' => $buyer,
            'to' => Auth()->user()->id,
            'approved_by' => Auth()->user()->id,
            'recorded_by' => Auth()->user()->id,
            'payment_status' => $pay_status,
            'transaction_id' => $group_id,
            'balance' => $balance,
            'vat' =>str_replace(',', '', $request->tax),
            'discount'=>$request->discount,
            'beneficiary' => Auth()->user()->setting_id,
            'setting_id' => Auth()->user()->setting_id
        ])->id;

        // $sales = product_sales::paginate(50);

        if($delivery_fee>0){
            delivery::create([
                'customer'=>$buyer,
                'invoice_no'=>$tid,
                'amount'=>$delivery_fee,
                'delivery_address'=>$address,
                'status'=>'In Progress'
            ]);
        }

        $message = "Sales Successful";
        return redirect()->back()->with(['tid'=>$tid,'message'=>$message]);

    }


    public function returnItem($itemid)
    {
        $item = product_sales::where('id',$itemid)->first();

        return view('new-returned', compact('item'));
    }

    public function saveReturned(Request $request)
    {
        $item = product_sales::where('id',$request->item_id)->first();

        // Update Transaction
        $itransaction = transactions::where('reference_no',$item->group_id)->orWhere('transaction_id',$item->group_id)->first();

            $itransaction->decrement('amount',$request->amount);
            $itransaction->save();

        if($item->quantity==$request->quantity){
            $item->group_id = "Returned to ".$request->returnedto;
            $item->pay_status = "Returned";
            $item->details = $request->reason;
            $item->save();
        }else{
            $item->decrement('quantity',$request->quantity);
            $item->save();
        }


        // Return to Stock
        if($request->returnedto=="Stock"){
            product_stocks::updateOrCreate(['product_id'=>$item->product_id],[
                'product_id'=>$item->product_id,
            ])->increment('quantity',$request->quantity);
        }

        returns::create($request->all());

        return redirect()->back()->with(['message'=>"Item return save successfully"]);
    }

    public function addproforma(Request $request)
    {
        $request->amount_paid=0;
        $pay_status = $request->pay_method;

        $delivery_fee = 0;
        if(isset($request->delivery_fee)){
            $delivery_fee=$request->delivery_fee;
        }

        $address = "";

        if (User::where('id', '=', $request->customer)->exists()) {
            $buyer = $request->customer;
            $address = user::select('address')->where('id',$buyer)->first()->address;
         }elseif($request->customer=='New'){
                $email = 'guest@gubabi.com';
                $address = $request->address;
                if(isset($request->email) && $request->email!=''){
                    $email = $request->email;
                }

                $password = Hash::make("prayer22");
                $buyer = User::create([
                    'name' => $request->customer_name,
                    'email' => $email,
                    'phone_number'=>$request->phone_number,
                    'dob' => $request->dated_sold,
                    'password' => $password,
                    'about' => $request->details,
                    'address' => $request->address,
                    'role'=>"Customer",
                    'category'=>"Customer",
                    'status'=>"InActive",
                    'setting_id' => Auth()->user()->setting_id
                ])->id;
            }else{
                $buyer = 1;
            }

         if($request->group_id==""){
            $group_id =  substr(md5(uniqid(mt_rand(), true).microtime(true)),0, 7);
         }else{
            $group_id = $request->group_id;
         }

        foreach($request->product_id as $key => $product_id){
            product_sales::create([
                'product_id' => $product_id,
                'quantity' => $request->qty[$key],
                'sales_person' => Auth()->user()->id,
                'confirmed_by' => Auth()->user()->id,
                'buyer' => $buyer,
                'price' => str_replace(',', '',$request->unit[$key]),
                'amount_paid' => str_replace(',', '',$request->amount[$key]),
                // 'amount_paid' => str_replace(',', '',$request->amount[$key]),
                'pay_status' => $pay_status,
                'dated_sold' => $request->dated_sold,
                'group_id' => $group_id,
                'setting_id'=>Auth()->user()->setting_id
            ]);
        }

        // RECORD TRANSACTION
        $tid = transactions::create([
            'title'=>$pay_status." No: ".$group_id,
            'amount'=>str_replace(',', '',$request->total_due)+$delivery_fee,
            'account_head' => 5,
            'dated' => $request->dated_sold,
            'reference_no' => $group_id,
            'detail' => $request->details,
            'from' => $buyer,
            'to' => Auth()->user()->id,
            'approved_by' => Auth()->user()->id,
            'recorded_by' => Auth()->user()->id,
            'payment_status' => $pay_status,
            'transaction_id' => $group_id,
            'balance' => 0,
            'vat' => str_replace(',', '',$request->tax),
            'discount'=>str_replace(',', '',$request->discount),
            'beneficiary' => Auth()->user()->setting_id,
            'setting_id' => Auth()->user()->setting_id
        ])->id;

        if($delivery_fee>0){
            delivery::create([
                'customer'=>$buyer,
                'invoice_no'=>$tid,
                'amount'=>$delivery_fee,
                'delivery_address'=>$address,
                'status'=>'Proforma'
            ]);
        }

        // $sales = product_sales::paginate(50);
        $message = $pay_status." Created Successfully";
        return redirect()->back()->with(['tid'=>$tid,'message'=>$message,'category'=>$pay_status]);
    }

    public function updateInvoice(Request $request)
    {
        $amount_paid = str_replace(',', '',$request->amount_paid);
        $total_due = str_replace(',', '',$request->total_due);
        $balance = 0;
        $pay_status = "Proforma";
        $title = "Profoma No: ";
        $accounthead = 5;

        if(isset($request->convert) && $request->convert=="Yes"){
            if($amount_paid==$total_due){
                $pay_status = "Paid";
                $balance = 0;
            }elseif($amount_paid>$total_due){
                $pay_status = "Overpaid";
                $balance = $amount_paid-$total_due;
            }elseif($amount_paid<$total_due){
                $pay_status = "Part Payment";
                $balance = $total_due-$amount_paid;
            }else{
                $pay_status = "Not Paid";
                $balance = $total_due;
            }
            $title = "Item Sales - Invoice No: ";
            $accounthead = 1;
        }
        $buyer = $request->customer;
        $address = user::select('address')->where('id',$buyer)->first()->address;
        $delivery_fee = 0;
        if(isset($request->delivery_fee)){
            $delivery_fee=$request->delivery_fee;
        }

         product_sales::where('group_id',$request->reference_no)->delete();

         if($request->group_id==""){
            $group_id =  substr(md5(uniqid(mt_rand(), true).microtime(true)),0, 7);
         }else{
            $group_id = $request->group_id;
         }


        foreach($request->product_id as $key => $product_id){
            product_sales::create([
                'product_id' => $product_id,
                'quantity' => $request->qty[$key],
                'sales_person' => Auth()->user()->id,
                'confirmed_by' => Auth()->user()->id,
                'buyer' => $buyer,
                'price' => str_replace(',', '',$request->unit[$key]),
                'amount_paid' => str_replace(',', '',$request->amount[$key]),
                'pay_status' => $pay_status,
                'dated_sold' => $request->dated_sold,
                'group_id' => $group_id,
                'details' => $request->details,
                'setting_id'=>Auth()->user()->setting_id
            ]);


            // Update Product Stock
            if(isset($request->convert) && $request->convert=="Yes"){
                product_stocks::updateOrCreate(['product_id'=>$product_id],[
                    'product_id'=>$product_id,
                ])->decrement('quantity',$request->qty[$key]);
                $accounthead = 1;
            }
        }

        // RECORD TRANSACTION
        $tid = transactions::updateOrCreate(['id'=>$request->id],[
            'title'=>$title.$group_id,
            'amount'=>str_replace(',', '',$request->total_due)+$delivery_fee,
            'account_head' => $accounthead,
            'dated' => $request->dated_sold,
            'reference_no' => $group_id,
            'detail' => $request->details,
            'from' => $buyer,
            'to' => Auth()->user()->id,
            'approved_by' => Auth()->user()->id,
            'recorded_by' => Auth()->user()->id,
            'payment_status' => $pay_status,
            'transaction_id' => $group_id,
            'balance' => str_replace(',', '',$request->total_due)-str_replace(',', '',$request->amount_paid),
            'vat' => str_replace(',', '',$request->tax),
            'discount'=>str_replace(',', '',$request->discount),
            'beneficiary' => Auth()->user()->setting_id,
            'setting_id' => Auth()->user()->setting_id
        ])->id;


            delivery::updateOrCreate(['invoice_no'=>$tid],[
                'customer'=>$buyer,
                'invoice_no'=>$tid,
                'amount'=>$delivery_fee,
                'delivery_address'=>$address,
                'status'=>$pay_status
            ]);


        // $sales = product_sales::paginate(50);
        $message = $pay_status." - Invoice Successfully Updated";
        return redirect()->back()->with(['tid'=>$tid,'message'=>$message]);
    }

    public function sale()
    {
        $lastInvoice = transactions::select('id','reference_no')->where('payment_status','!=','Proforma')->where('account_head',1)->orderBy('id','desc')->first();

        if(!empty($lastInvoice)){
            $lastInvoiceNo = $lastInvoice->reference_no;
        }else{
            $lastInvoiceNo = 0;
        }
        $products = products::select('id','name','price','picture','measurement_unit')->get();
        return view('newsales', compact('products','lastInvoiceNo'));
    }

    public function newproforma()
    {
        $lastInvoice = transactions::select('id','reference_no')->where('payment_status','Proforma')->orderBy('id','desc')->first();
        if(!empty($lastInvoice)){
            $lastInvoiceNo = $lastInvoice->reference_no;
        }else{
            $lastInvoiceNo = 0;
        }
        $products = products::select('id','name','price','picture','measurement_unit')->get();
        return view('newproforma', compact('products','lastInvoiceNo'));
    }

    public function invoice($category,$tid)
    {
        // Check if the PDF file exists in the public/pdf folder
        $pdfFilePath = public_path('pdf/' . $category . $tid . '.pdf');

        if (file_exists($pdfFilePath)) {
            // If the file exists, return the file directly
            return response()->file($pdfFilePath);
        } else {
            // If the file doesn't exist, fetch it from the database and generate the PDF
            $sale = transactions::where('id', $tid)->first();
            $products = product_sales::where('group_id', $sale->reference_no)->get();
            $customer = User::where('id', $sale->from)->first();
            $title = strtoupper($category);
            $pdf_doc = PDF::loadView('invoice', compact('sale', 'products', 'customer', 'category','title'))
                ->setOptions(['defaultFont' => 'DejaVuSans', 'isRemoteEnabled' => true]);

            // Get the path to the public directory and create the pdf folder if it doesn't exist
            $pdfDirectory = public_path('pdf');
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0777, true);
            }

            // Save the PDF to the pdf folder
            $pdfPath = $pdfDirectory . '/' . $category.'-'.$tid . '.pdf'; // Change 'filename.pdf' to your desired filename
            $pdf_doc->save($pdfPath);


            // Generate and stream the PDF
            return $pdf_doc->stream($category . $tid . '.pdf');
        }
    }

    public function editInvoice($tid)
    {

            // If the file doesn't exist, fetch it from the database and generate the PDF
            $trans = transactions::where('id', $tid)->first();
            $products = products::all();
            $psales = product_sales::where('group_id', $trans->reference_no)->get();

            return view('editsales', compact('products','trans','psales'));

    }

    public function newInvoice($category,$tid)
    {

            $sale = transactions::where('id', $tid)->first();
            $products = product_sales::where('group_id', $sale->reference_no)->get();
            $customer = User::where('id', $sale->from)->first();
            $title = strtoupper($category);
            $pdf_doc = PDF::loadView('invoice', compact('sale', 'products', 'customer', 'category','title'))
                ->setOptions(['defaultFont' => 'DejaVuSans', 'isRemoteEnabled' => true]);

            // Get the path to the public directory and create the pdf folder if it doesn't exist
            $pdfDirectory = public_path('pdf');
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0777, true);
            }

            // Save the PDF to the pdf folder
            $pdfPath = $pdfDirectory . '/' . $category.'-'.$tid . '.pdf'; // Change 'filename.pdf' to your desired filename
            $pdf_doc->save($pdfPath);

            // Generate and stream the PDF
            return $pdf_doc->stream($category . $tid . '.pdf');

    }

    public function sendDocument($category,$tid)
    {

        $sale = transactions::where('id',$tid)->first();
        $products = product_sales::where('group_id',$sale->reference_no)->get();
        $customer = User::where('id',$sale->from)->first();
        $title = strtoupper($category);
        $pdf_doc = PDF::loadView('invoice', compact('sale','products','customer','category','title'))->setOptions(['defaultFont' => 'DejaVuSans','isRemoteEnabled'=>true,]);

        // Get the path to the public directory and create the pdf folder if it doesn't exist
        $pdfDirectory = public_path('pdf');
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0777, true);
        }

        // Save the PDF to the pdf folder
        $pdfPath = $pdfDirectory . '/' . $category.'-'.$tid . '.pdf'; // Change 'filename.pdf' to your desired filename
        $pdf_doc->save($pdfPath);

        $receiverEmail = $sale->customer->email;
        $receiverName = $sale->customer->name;
        $subject = $sale->title.' From Gubabi';

        // $pdfPath = public_path($category.'-'.$tid . '.pdf');

        $pdfPath = public_path('pdf/'.$category.'-'.$tid . '.pdf');

        Mail::to($receiverEmail)->send(new SendPDFEmail($receiverName, $subject, $pdfPath));


        // Mail::to($receiverEmail)->send(new SendPDFMail($pdfPath));

        return redirect()->back()->with(['message'=>ucwords($category).' Sent successfully!']);
    }

    public function deliveries(){
        $deliveries = delivery::all();
        $deliverymen = User::select('id','name','about')->where('category','Contractor')->get();
        return view('deliveries',compact('deliveries','deliverymen'));
    }

    public function saveDelivery(Request $request){
        delivery::updateOrCreate(['id'=>$request->id],[
            'delivery_date'=>$request->delivery_date,
            'status'=>$request->status,
            'deliveredBy'=>$request->delivered_by,
            'details'=>$request->details,
        ]);

        $message = "Delivery record saved Successful";
        return redirect()->back()->with(['message'=>$message]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\product_sales  $product_sales
     * @return \Illuminate\Http\Response
     */
    public function show(product_sales $product_sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\product_sales  $product_sales
     * @return \Illuminate\Http\Response
     */
    public function edit(product_sales $product_sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product_sales  $product_sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product_sales $product_sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product_sales  $product_sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(product_sales $product_sales)
    {
        //
    }
}
