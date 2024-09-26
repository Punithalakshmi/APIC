<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dealer;
use Illuminate\Support\Facades\Http;

class DealerController extends Controller
{
    //
    public function index(Request $request)
    {
       // $dealers = Dealer::orderBy('id', 'desc')->get();
         $query = Dealer::query();
        // Search filter by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by email
        if ($request->has('email') && $request->email != '') {
            $query->where('email', $request->email);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', '=', $request->status);
        }

        if ($request->has('apic_user_type') && $request->apic_user_type != '') {
            $query->where('apic_user_type', '=', $request->apic_user_type);
        }

        // Paginate the results
        $dealers = $query->paginate(10);
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
            'current_url' => '',
            'onboarding_date' => 'required',
            'apic_user_type' => 'required'
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
        $apic_user_type = $request->apic_user_type;
 
        $dealers->name = $name;
        $dealers->email = $email;
        $dealers->dealer_id = $dealer_id;
        $dealers->appuid = $appuid;
        $dealers->status = $status;
        $dealers->current_url = $current_url;
        $dealers->onboarding_date = $onboarding_date;
        $dealers->apic_user_type = $apic_user_type;
        $data = $dealers->save();
        if ($data) {
            session()->flash('success', 'Dealer Update Successfully');
            return redirect(route('admin/dealers'));
        } else {
            session()->flash('error', 'Some problem occure');
            return redirect(route('admin/dealers/edit/{{$id}}'));
        }
    }


    public function dealer_list()
    {
        $dealersLists = Dealer::where(array("status" => 1))->get()->toArray();
       
        if(is_array($dealersLists) && count($dealersLists) > 0)
        {
           
           foreach($dealersLists as $d => $dealers ){

            $apiData    = array();
            $appUid                = generate_app_uid(6);
            $apiData['name']       = $dealers['name'];
            $apiData['id']         = $dealers['id'];
            $apiData['email']      = $dealers['email'];
            $apiData['app_uid']    = $appUid;
            $apiData['type']       = "Register";
            $apiData['url']        = getRegisterApiUrl();
        
            $apiRes = api_request($apiData);

            if($apiRes){
            
                $apiData['type']   = "Login";
                $apiData['url']    = getLoginApiUrl();
                $apiLoginres       = api_request($apiData);
            
                //domainname
                $domainName= getCoohomDomain();
                $coohomUrl = (isset($apiLoginres['d']['url']))?$apiLoginres['d']['url']:"";   
                $token     = (isset($apiLoginres['d']['token']))?$apiLoginres['d']['token']:"";
                $dealers   = Dealer::findOrFail($dealers['id']);

                $link = 'https://'.$domainName.'/api/saas/openapi/v2/redirect?url=https://'.$domainName."/pub/saas/apps/project/list&token=".$token."&locale=en_US";

                $dealers->current_url = $link;
                $dealers->token       = $token;

                $dealers->is_token_generated = 'Yes';
                $dealers->time_of_url_generation = date("Y-m-d H:i:s");	
                $data = $dealers->save();

                saveApiLogs($apiData['url'],'Token Generated Successfully',$dealers['id'],json_encode($apiLoginres)); 
            
                if ($data) {
                
                    $mailData = array(
                        'title' => 'Token Generated Successfully',
                        'link'  => $link,
                        'name' => $dealers['name']
                    );
                    
                    Mail::to($dealers['email'])->send(new CommonMail($mailData));
                    saveApiLogs($apiData['url'],'Mail Sent',$dealers['id'],'Sent mail to Dealer'); 
                }
              }
           } 
        }
    }
}
