<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;

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
        return view('new-service', compact('users'));
    }

    public function store(Request $request){

        Service::create($request->all());
        return redirect()->route('services');
    }
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $users = User::all();
        return view('edit-service', compact('service', 'users'));
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->back()->with(['message'=>'Service deleted']);
    }
}
