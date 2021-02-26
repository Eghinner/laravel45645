<?php 
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{  
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
//////////////LOGIN/////////////
    public function show_login_form()
    {
        return view('login');
    }
    public function process_login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->except(['_token']);

        $user = User::where('name',$request->name)->first();

        if (auth()->attempt($credentials)) {

            return redirect()->route('home');

        }else{
            session()->flash('message', 'Invalid credentials');
            return redirect()->back();
        }
    }
  

//////////////////SIGNUP////////
    public function show_signup_form()
    {
        return view('register');
    }
    public function process_signup(Request $request)
    {   
$request->validate([
    'name' => 'required',
//    'email' => 'required',
//    'password' => 'required'
    
]);  
if ($request->hasFile('foto')) {

   $request->validate([     
	'foto' => 'required|max:10000|mimes:jpeg,png,jpg',
   	]);
   $request->foto->store('public');


        $user = User::create([
  //      $user = new User([
//                    'name' => trim($request->input('name')),
        	'name' => ($request->get('name')),
 //                   'email' => strtolower($request->input('email')),
 //                   'password' => bcrypt($request->input('password')),
        	'foto' => $request->foto->hashName()
                ]);               
  
        $user->save();
  
       	return $user;
			session()->flash('message', 'Your account is created');
       
//       return redirect()->route('login');
    }
}
   public function logout()
  {
       \Auth::logout();
        return redirect()->route('login');
   }
  }