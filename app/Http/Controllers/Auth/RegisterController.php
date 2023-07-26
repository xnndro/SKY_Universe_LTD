<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'dating_code' => ['required','string','starts_with:DT','max:5', 'min:5'],
            'dob' => ['required','date'],
            'gender' => ['required'],
            'phone' => ['required','string', 'starts_with:+62', 'min:10', 'max:14'],
            'profile_picture' => ['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
       $existingUser = User::where('dating_code', $data['dating_code'])->first();

       if ($existingUser) {
           if ($existingUser->gender === $data['gender']) {
               throw ValidationException::withMessages(['dating_code' => 'Dating Code is already used']);
           }
       }

       $gender_code = ($data['gender'] === 'male') ? '01' : '02';

       $destination_path = 'public/uploads/profile/';
       $file = $data['profile_picture'];
       $name_file = $data['name'] . '.jpg';

       $file->move($destination_path, $name_file);

       $dating_id = 'SKY' . substr($data['dating_code'], 2, 4) . $gender_code;

       $newUser = User::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' => Hash::make($data['password']),
           'dating_code' => $data['dating_code'],
           'birthday' => $data['dob'],
           'gender' => $data['gender'],
           'phone' => $data['phone'],
           'profile_picture' => $name_file,
           'user_dating_id' => $dating_id
       ]);

       // Manually log in the newly created user
       Auth::login($newUser);

       return $newUser;
    }
}
