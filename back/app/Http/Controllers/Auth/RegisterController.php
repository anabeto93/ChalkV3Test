<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone_number' => 'required|digits|unique:users,phone_number',
            'country'   =>  'required|string',
            'institution_id' => 'required|exists:institutions,id'
        ]);
    }

    protected function register(Request $request)
    {
        return $this->create($request->all());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = new User();
        return $user
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setInstitutionId($data['institution_id'])
            ->setPhoneNumber($data['phone_name'])
            ->setCountry($data['country'])
            ->store();
    }

    public function createAdmin(StoreAdminRequest $request)
    {
        return Admin::create([
            'username' => strtolower($request->input('username')),
            'password' => Hash::make($request->input('password'))
        ]);
    }
}
