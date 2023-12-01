<?php

namespace App\Http\Controllers;

use App\Models\product_sales;
use App\Models\products;
use App\Models\product_stocks;
use App\Models\User;
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
        if($request->amount_paid==$request->total_due){
            $pay_status = "Paid";
        }elseif($request->amount_paid>$request->total_due){
            $pay_status = "Overpaid";
        }elseif($request->amount_paid<$request->total_due){
            $pay_status = "Half Payment";
        }else{
            $pay_status = "Not Paid";
        }

        if (User::where('id', '=', $request->buyer)->exists()) {
            $buyer = $request->buyer;
         }else{
            if($request->customer!=''){
                $password = Hash::make("prayer22");
                $buyer = User::create([
                    'name' => $request->customer,
                    'email' => 'guest@prodsales.com',
                    'dob' => $request->dated_sold,
                    'password' => $password,
                    'about' => $request->details,
                    'role'=>"Customer",
                    'category'=>"Customer",
                    'status'=>"InActive",
                    'setting_id' => Auth()->user()->setting_id
                ])->id;
            }else{
                $buyer = 1;
            }

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
                'price' => $request->unit[$key],
                'amount_paid' => $request->amount[$key],
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
            'amount'=>$request->total_due,
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
            'balance' => $request->total_due-$request->amount_paid,
            'beneficiary' => Auth()->user()->setting_id,
            'setting_id' => Auth()->user()->setting_id
        ])->id;

        // $sales = product_sales::paginate(50);

        $message = "Successful";
        return redirect()->back()->with(['tid'=>$tid,'message'=>$message]);

    }

    public function addproforma(Request $request)
    {
        $request->amount_paid=0;
        $pay_status = $request->pay_method;

        if (User::where('id', '=', $request->buyer)->exists()) {
            $buyer = $request->buyer;
         }else{
            if($request->customer!=''){
                $password = Hash::make("prayer22");
                $buyer = User::create([
                    'name' => $request->customer,
                    'email' => 'guest@prodsales.com',
                    'dob' => $request->dated_sold,
                    'password' => $password,
                    'about' => $request->details,
                    'role'=>"Customer",
                    'category'=>"Customer",
                    'status'=>"InActive",
                    'setting_id' => Auth()->user()->setting_id
                ])->id;
            }else{
                $buyer = 1;
            }
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
                'price' => $request->unit[$key],
                'amount_paid' => $request->amount[$key],
                'pay_status' => $pay_status,
                'dated_sold' => $request->dated_sold,
                'group_id' => $group_id,
                'setting_id'=>Auth()->user()->setting_id
            ]);
        }

        // RECORD TRANSACTION
        $tid = transactions::create([
            'title'=>$pay_status." No: ".$group_id,
            'amount'=>$request->total_due,
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
            'beneficiary' => Auth()->user()->setting_id,
            'setting_id' => Auth()->user()->setting_id
        ])->id;

        // $sales = product_sales::paginate(50);
        $message = $pay_status." Created Successfully";
        return redirect()->back()->with(['tid'=>$tid,'message'=>$message,'category'=>$pay_status]);
    }


    public function sale()
    {
        $products = products::select('id','name','price','picture','measurement_unit')->get();
        return view('newsales', compact('products'));
    }

    public function newproforma()
    {
        $products = products::select('id','name','price','picture','measurement_unit')->get();
        return view('newproforma', compact('products'));
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
            $pdf_doc = PDF::loadView('invoice', compact('sale', 'products', 'customer', 'category'))
                ->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

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

            $pdf_doc = PDF::loadView('invoice', compact('sale', 'products', 'customer', 'category'))
                ->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

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
        $pdf_doc = PDF::loadView('invoice', compact('sale','products','customer','category'))->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true,]);

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
