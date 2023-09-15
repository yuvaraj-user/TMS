<?php 
namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use Hash;
 use Session;
 use App\Models\User;
 use Illuminate\Support\Facades\Auth;
  
 class AuthController extends Controller
 {
  
     public function index()
     {
         return view('login');
     }  
        
  
     public function customLogin(Request $request)
     {
         $request->validate([
             'email' => 'required',
             'password' => 'required',
         ]);
     
         $credentials = $request->only('email', 'password');
         if (Auth::attempt($credentials)) {
             if(Auth::user()->role == 2) {
                 return redirect('/task_add');
             }
             return redirect('/dashboard')
                         ->withSuccess('Signed in');
         }
    
         return redirect("/")->withSuccess('Login details are not valid');
     }
     
    public function mazenetLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if($user) {
            if(Auth::loginUsingId($user->id)) {
                if(Auth::user()->role == 2) {
                    return redirect('/task_add');
                }
                return redirect('/dashboard')->withSuccess('Signed in');
            }
        }        
    }
  
     public function registration()
     {
         return view('auth.registration');
     }
        
  
     public function customRegistration(Request $request)
     {  
         $request->validate([
             'name' => 'required',
             'email' => 'required|email|unique:users',
             'password' => 'required|min:6',
         ]);
             
         $data = $request->all();
         $check = $this->create($data);
           
         return redirect("dashboard")->withSuccess('You have signed-in');
     }
  
  
     public function create(array $data)
     {
       return User::create([
         'name' => $data['name'],
         'email' => $data['email'],
         'password' => Hash::make($data['password'])
       ]);
     }    
      
  
     public function dashboard()
     {
         if(Auth::check()){
             return view('dashboard');
         }
    
         return redirect("login")->withSuccess('You are not allowed to access');
     }
      
  
     public function logout() {
         Session::flush();
         Auth::logout();
        //  return redirect('https://mazenet.in/gateway.aspx');
         return Redirect('/');
     }
 }