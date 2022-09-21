<?php

namespace App\Http\Controllers;

use App\Models\transactions;
use Illuminate\Http\Request;

use App\Models\accountheads;
use App\Models\User;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accountheads = accountheads::where('setting_id',Auth()->user()->setting_id)->get();

        $transactions = transactions::where('setting_id',Auth()->user()->setting_id)->paginate(50);
        $users = User::select('id','name')->get();

        return view('transactions', compact('transactions','users','accountheads'));
    }

    public function myInvoices()
    {
        $transactions = transactions::where('from',Auth()->user()->id)->paginate(50);
        return view('myinvoices', compact('transactions'));
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
        transactions::updateOrCreate(['id'=>$request->id],[
            'title' => $request->title,
            'amount' => $request->amount,
            'account_head' => $request->account_head,
            'dated'=>$request->date,
            'reference_no' => $request->reference_no,
            'upload'=>'',
            'detail'=>$request->detail,
            'from'=>$request->from,
            'to'=>$request->to,
            'approved_by'=>$request->approved_by,
            'recorded_by'=>$request->recorded_by,

        ]);
        $transactions = transactions::paginate(50);
        $accountheads = accountheads::select('title','category')->get();
        $users = User::select('id','name')->get();

        return view('transactions', compact('transactions','accountheads','users'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function show(transactions $transactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function edit(transactions $transactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, transactions $transactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        transactions::findOrFail($id)->delete();
        $message = 'The transaction\'s Record has been deleted!';
        return redirect()->route('transactions')->with(['message'=>$message]);
    }
}
