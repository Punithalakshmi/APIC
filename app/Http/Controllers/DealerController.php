<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dealer;
use Illuminate\Support\Facades\Http;

class DealerController extends Controller
{
    //
    public function index()
    {
        $dealers = Dealer::orderBy('id', 'desc')->get();
        $total   = Dealer::count();
       
        return view('admin.dealer.list', compact(['dealers', 'total']));
    }

    public function create()
    {
        return view('admin.dealer.add');
    }

    public function save(Request $request)
    {
        $validation = $request->validate([
            'dealer_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'appuid' => 'required',
            'status' => 'required',
           // 'time_of_url_generation' => 'required',
            'current_url' => 'required',
            'onboarding_date' => 'required'
        ]);

        $data = Dealer::create($validation);
        if ($data) {
            session()->flash('success', 'Dealer Add Successfully');
            return redirect(route('admin/dealers'));
        } else {
            session()->flash('error', 'Some problem occure');
            return redirect(route('admin/dealers/create'));
        }
    }
    public function edit($id)
    {
        $dealers = Dealer::findOrFail($id);
        return view('admin.dealer.update', compact('dealers'));
    }
 
    public function delete($id)
    {
        $dealers = Dealer::findOrFail($id)->delete();
        if ($dealers) {
            session()->flash('success', 'Dealer Deleted Successfully');
            return redirect(route('admin/dealers'));
        } else {
            session()->flash('error', 'Dealer Not Delete successfully');
            return redirect(route('admin/dealers'));
        }
    }
 
    public function update(Request $request, $id)
    {
        $dealers = Dealer::findOrFail($id);
        $name = $request->name;
        $email = $request->email;
        $dealer_id = $request->dealer_id;
        $appuid  = $request->appuid;
        $status = $request->status;
        $current_url = $request->current_url;
        $onboarding_date = $request->onboarding_date;
 
        $dealers->name = $name;
        $dealers->email = $email;
        $dealers->dealer_id = $dealer_id;
        $dealers->appuid = $appuid;
        $dealers->status = $status;
        $dealers->current_url = $current_url;
        $dealers->onboarding_date = $onboarding_date;
        $data = $dealers->save();
        if ($data) {
            session()->flash('success', 'Dealer Update Successfully');
            return redirect(route('admin/dealers'));
        } else {
            session()->flash('error', 'Some problem occure');
            return redirect(route('admin/dealers/edit/{{$id}}'));
        }
    }
}
