<?php
  
namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\CityLocation;
use Hash;
use Mail;
use DB;
  
class AuthController extends Controller
{

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('auth.login');
    }  
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view('auth.registration');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function adminRegistration()
    {
        
        return view('admin.registration');

    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            if(Auth::user()->user_group == User::ADMIN){

                return redirect()->intended('admin/dashboard')
                ->withSuccess('You have Successfully loggedin');

            }elseif(Auth::user()->user_group == User::CLIENT){

                return redirect()->intended('clientProfile')
                ->withSuccess('You have Successfully loggedin');

            }else{
                return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
            }
        }
        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
               
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    { 
        DB::beginTransaction();
        try {
            $previousRoute = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
            
            if($previousRoute == "admin.register"){
            
                $request->validate([
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'username' => 'required|unique:users,username',
                    'password' => 'required|min:6',
                ]);

                $request['user_group'] = User::ADMIN;
            }else{
                $request->validate([
                    'name' => 'required',
                    'phone_number' => 'required|digits:10|numeric|unique:users,phone_number',
                    'address' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6',
                ]);
                
                $request['user_group'] = User::CLIENT;
            }
            
            $request['password'] =  Hash::make($request->password);
            $data = $request->all(); 

            DB::commit();

            $user = User::create($data);
           
            if($previousRoute == "register"){
                
                if($user){

                    $cityLocation['user_id'] = $user->id;
                    $cityLocation['city_location'] = isset($request->address) ? $request->address : null;
                    $cityLocation['latitude'] = isset($request->latitude) ? $request->latitude : null;
                    $cityLocation['longitude'] = isset($request->longitude) ? $request->longitude : null;

                    $cityLocation = CityLocation::create($cityLocation);
                    
                }

            }
            
            //send admin email
            $subject = "New User Registered!";
            $this->sendNewUserRegisteredAdminMail($user->name,$user->email,$user->phone_number, $subject);

            DB::commit();
            return redirect("login")->withSuccess('Great! You have Successfully registered');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if($previousRoute == "admin.register"){
                return redirect("admin/register")->withSuccess($e->getMessage());
            }else{
                return redirect("registration")->withSuccess($e->getMessage());
            }
        }
        
        
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function clientProfile()
    {
        if(Auth::check()){

            $user = Auth::user();
           
            $client = User::with('cityLocation')->where('id',$user->id)->first();
            
            return view('clientProfile',compact('client'));
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function adminDashboard()
    {
        if(Auth::check()){
            return view('admin.dashboard');
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {

        Auth::logout();
        Session::flush();
  
        return Redirect('login');
    }

    public function sendNewUserRegisteredAdminMail($name, $email, $mobile,  $subject)
    {
        $send_to = env('MAIL_TO_ADDRESS');
        $from_email = env('MAIL_FROM_ADDRESS');
        $from_name = env('MAIL_FROM_NAME');
        
        try {
            Mail::send('emails.templates.newUserRegistered-template', [
                'sender_name' => $name,
                'sender_email' => $email,
                'subject' => $subject,
                'sender_mobile' => $mobile
            ],
                function ($mail) use ($send_to, $subject, $from_email, $from_name) {
                    $mail->from($from_email, $from_name);
                    $mail->to($send_to);
                    $mail->subject($subject);
                }
            );
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
}