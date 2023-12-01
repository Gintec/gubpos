<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\businessgroups;
use App\Models\tasks;
use App\Models\programmes;
use App\Models\settings;
use App\Models\admintable;
use App\Models\product;
use App\Models\transactions;
use App\Models\followups;
use Artisan;
// use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(isset($setting_id)){
            $role = admintable::select('role')->where('user_id',Auth()->user()->id)->where('setting_id',$setting_id)->first()->role;
        }else{
            $role = admintable::select('role')->where('user_id',Auth()->user()->id)->where('setting_id',Auth()->user()->setting_id)->first();
            if(isset($role->role)){
              $role = $role->role;
            }else{
                $role = Auth()->user()->role;
            }
            $setting_id = Auth()->user()->setting_id;
        }

        $jobs = transactions::select('id','account_head','dated','reference_no','payment_status','from')->get();

        return view('home')->with(['jobs'=>$jobs]);
           // return $this->userDashboard();

     }



    public function userDashboard(){
        $myinvoices = transactions::where('from',Auth::user()->id)->get();
        return view('user_dashboard',compact('myinvoices'));
     }

    public function logout()
    {
      Auth::logout();
      return redirect('/');
    }

    public function members()
    {
      $members = User::where('category','!=','Customer')->get();
      $users = User::select('name','id')->get();
      return view('members', compact('members','users'));
    }

    public function customers()
    {
      $members = User::where('category','Customer')->get();
      $users = User::select('name','id')->get();
      return view('members', compact('members','users'));
    }

    public function businesses()
    {
      return view('businesses');
    }


    public function membersSearch(request $request)
    {
      $members = User::where('name', $request->keyword)->orWhere('name', 'like', '%' . $request->keyword . '%')->get();
      $users = User::select('name','id')->get();
      return view('members', compact('members','users'));
    }

    public function member($id)
    {
      $member = User::where('id',$id)->first();
      $users = User::select('id','name','phone_number')->get(); // Be specific to member assigned to and invited by
      $tasks = tasks::where('assigned_to',$id)->get();
      $followups = followups::where('member',$id)->get();

      return view('member', compact('member','users','tasks','followups'));
    }

    public function addNew()
    {
      $users = User::select('name','id')->get();
      $businesses = settings::select('business_name','id')->get();
      return view('add-new', compact('users','businesses'));

    }

    protected function create(request $request)
    {

        if($request->email==""){

            $email = "guest@crmfct2.org";
            $password = Hash::make("prayer22");
        }else{
            $email = $request->email;
        }

        if($request->password!=""){
            $password = Hash::make($request->password);
        }else{
            $password = $request->oldpassword;
        }

        $userid = User::updateOrCreate(['id'=>$request->id],[
            'name' => $request->name,
            'email' => $email,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone_number'=>$request->phone_number,
            'password' => $password,
            'about' => $request->about,
            'address' => $request->address,
            'location' => $request->location,
            'category' => $request->category,
            //'business' => $request->business,
            'role'=>$request->role,
            'status'=>$request->status,
            'setting_id'=>Auth()->user()->setting_id

        ])->id;

        admintable::updateOrCreate([
            'user_id'=>$userid,
            'setting_id'=>Auth()->user()->setting_id
        ],[
            'user_id'=>$userid,
            'setting_id'=>Auth()->user()->setting_id,
            'role'=>$request->role,
        ]);

        $members = User::all();
        $users = User::select('name','id')->get();

        return view('members', compact('members','users'));

    }

    public function editMember($id)
    {
      $user = User::where('id',$id)->first();
      $users = User::select('id','name')->get();
      return view('edit-member', compact('user','users'));

    }

    public function deleteMember($id)
    {
      $user = User::where('id',$id)->delete();
      $message = 'The member has been deleted!';
      return redirect()->route('members')->with(['message'=>$message]);

    }

    public function communications()
    {
      $response = null;
      // system("ping -c 1 google.com", $response);
      if(!checkdnsrr('google.com'))
      {
          return redirect()->back()->with(['message'=>'Please connect your internect before going to communications page <a href="/communications">Retry</a>']);
      }else{



        $session = file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=login&owneremail=gcictng@gmail.com&subacct=CRMAPP&subacctpwd=@@prayer22");
        $sessionid = ltrim(substr($session,3),' ');

        \Cookie::queue('sessionidd', $sessionid, 30);

        $cbal = file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=querybalance&sessionid=".$sessionid);

        $creditbalance = ltrim(substr($cbal,3),' ');

        $members = User::select('name','status','category','phone_number')->get();
        $allnumbers = "";
        $lastrecord = end($members);
        $lastkey = key($lastrecord);

        foreach($members as $key => $mnumber){
          $number = $mnumber->phone_number;
          if($number=="")
            continue;

          if(substr($number,0,1)=="0")
            $number="234".ltrim($number,'0');

          $allnumbers.=$number.",";
          /*
          if($key !== $lastkey){
            $allnumbers.=$number.",";
          }else{
            $allnumbers.=$number;
          }
          */

        }
        $allnumbers = substr($allnumbers,0,-1);
        return view('communications', compact('members','allnumbers','creditbalance'));
      }
    }

    public function sendSMS(request $request){

      // 2 Jan 2008 6:30 PM   sendtime - date format for scheduling
      if(\Cookie::get('sessionidd')){
        $sessionid = \Cookie::get('sessionidd');
      }else{
        $session = file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=login&owneremail=gcictng@gmail.com&subacct=CRMAPP&subacctpwd=@@prayer22");
        $sessionid = ltrim(substr($session,3),' ');
      }

      $sessionid = \Cookie::get('sessionidd');
      $recipients = $request->recipients;
      $body = $request->body;


      $message = file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=sendmsg&sessionid=".$sessionid."&message=".urlencode($body)."&sender=CHURCH&sendto=".$recipients."&msgtype=0");


      // v20ylRY3Gp6jYEAvpaDtOQQTqwoCqc1n4CUG3IBboIMTciDeVk	  -  Token for smartsms solutions

      $members = User::select('name','status','category','phone_number')->get();
      $allnumbers = "";
      $lastrecord = end($members);
      $lastkey = key($lastrecord);

      foreach($members as $key => $mnumber){
        $number = $mnumber->phone_number;
        if($number=="")
          continue;

        if(substr($number,0,1)=="0")
          $number="234".ltrim($number,'0');

        $allnumbers.=$number.",";
        /*
        if($key !== $lastkey){
          $allnumbers.=$number.",";
        }else{
          $allnumbers.=$number;
        }
        */

      }
      // GET CREDIT BALANCE
      $cbal = file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=querybalance&sessionid=".$sessionid);

      $creditbalance = ltrim(substr($cbal,3),' ');

      $allnumbers = substr($allnumbers,0,-1);
      return view('communications', compact('members','allnumbers','message','creditbalance'));


    }

    public function sentSMS(request $request){

      if(\Cookie::get('sessionidd')){
        $sessionid = \Cookie::get('sessionidd');
      }else{
        $session = file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=login&owneremail=gcictng@gmail.com&subacct=CRMAPP&subacctpwd=@@prayer22");
        $sessionid = ltrim(substr($session,3),' ');
      }

      $sentmessages = file_get_contents("http://www.smslive247.com/http/index.aspx?cmd=getsentmsgs&sessionid=".$sessionid."&pagesize=200&pagenumber=1&begindate=".urlencode('06 Sep 2021')."&enddate=".urlencode('08 Sep 2021')."&sender=CHURCH");
      error_log("All SENT: ".$sentmessages);
      return view('sentmessages', compact('sentmessages'));
    }

    public function settings(request $request){
      $validateData = $request->validate([
          'logo'=>'image|mimes:jpg,png,jpeg,gif,svg',
          'background'=>'image|mimes:jpg,png,jpeg,gif,svg'
      ]);

      if(!empty($request->file('logo'))){

          $logo = time().'.'.$request->logo->extension();

          $request->logo->move(\public_path('images'),$logo);
      }else{
          $logo = $request->oldlogo;
      }

      if(!empty($request->file('background'))){

          $background = time().'.'.$request->background->extension();

          $request->background->move(\public_path('images'),$background);
      }else{
          $background = $request->oldbackground;
      }

        try{

            settings::updateOrCreate(['id'=>$request->id],[
                'business_name' => $request->business_name,
                'motto' => $request->motto,
                'logo' => $logo,
                'address' => $request->address,
                'background' => $background,
                'mode'=>$request->mode,
                'color'=>$request->color,
                'businessgroup_id'=>$request->businessgroup_id,
                'user_id'=>$request->user_id
            ]);
            $message = "The settings has been updated!";
            return redirect()->back()->with(['message'=>$message]);

        } catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            // if($errorCode == '1062'){
            //    return back()->with('error', 'Your message may be a duplicate. Did you refresh the page? We blocked that submission. If you feel this was in error, e-mail us or call us.');
            // }
            // else{
            return back()->with(['message'=>$e->getMessage(),'type'=>'Error']);
            //}
        }
    }

    public function switchbusiness(request $request){


        if(Auth()->user()->role=="Super"){
            $admininfo = admintable::select('role','settings_id')->where('settings_id',$request->settings_id)->first();

            User::where('id',Auth()->user()->id)->update([
                'settings_id'=>$request->settings_id,
            ]);
        }else{
            $admininfo = admintable::select('role','settings_id')->where('settings_id',$request->settings_id)->where('user_id',Auth()->user()->id)->first();

            User::where('id',Auth()->user()->id)->update([
                'settings_id'=>$request->settings_id,
                'role'=>$admininfo->role
            ]);

        }

        $business_name = settings::where('id',$request->settings_id)->first()->business_name;
        $message = "You have been switch to ".$business_name;
        return redirect()->route('home')->with(['message'=>$message,'settings_id'=>$admininfo->settings_id]);
    }

    public function Artisan1($command) {
        $artisan = Artisan::call($command);
        $output = Artisan::output();
        return dd($output);
    }

    public function Artisan2($command, $param) {

        $output = Artisan::call($command.":".$param);

        // $artisan = Artisan::call($command,['flag'=>$param]);
        // $output = Artisan::output();
        return dd($output);
    }

}
