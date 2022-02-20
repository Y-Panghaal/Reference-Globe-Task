<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register(Request $request) {

        $id = $this->store($request);

        if ($id !== true) {
            return $id;
        }

        return redirect()->route('user.login.view')->with('success', "Registration Successful! Please wait for admin approval to login.");

    }

    public function addUser(Request $request) {

        $id = $this->store($request, 1);

        if ($id !== true) {
            return $id;
        }

        return redirect()->route('dashboard');

    }

    private function store(Request $request, $status = 0) {
        $rules = [
            'name'              => 'required|max:50',
            'mobile'            => 'required|numeric|digits:10',
            'email'             => ['required','email:dns,filter', Rule::unique('users', 'email')->where('role', 0)],
            'password'          => 'required|confirmed',
            'address'           => 'required|max:255',
            'gender'            => 'required|in:0,1',
            'date_of_birth'     => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $maxTimestamp = now()->subYears(18)->timestamp;
                    $value = strtotime($value);
                    if ($value > $maxTimestamp) {
                        $fail("You must be at least 18 years old to register.");
                    }
                }
            ],
            'profile_picture'   => 'required|file|mimes:jpg,png',
            'signature'         => 'required|file|mimes:jpg,png',
        ];

        $messages = ['gender.in' => 'Gender can either be Male or Female.'];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $dataToInsert = $request->only(['name', 'mobile', 'email', 'password', 'address', 'gender', 'date_of_birth']);
        $dataToInsert['password'] = Hash::make($dataToInsert['password']);

        $profilePicture = Storage::disk('public')->putFile('', $request->file('profile_picture'));
        $signature = Storage::disk('public')->putFile('', $request->file('signature'));

        $dataToInsert['profile_picture'] = $profilePicture;
        $dataToInsert['signature'] = $signature;
        $dataToInsert['status'] = $status;

        $id = DB::table('users')->insertGetId($dataToInsert);
        if ($id > 0) {
            return true;
        }

        return redirect()->back()->withInput()->with('error', "Something went wrong. Please try again after sometime.");

    }
    
    public function login(Request $request) {

        $rules = [
            'email'     => 'required|email:dns,filter|exists:users,email',
            'password'  => 'required',
            'remember'  => 'sometimes|required|nullable',
        ];

        $messages = [
            'email.exists' => 'Invalid credentials.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $credentials = $request->only(['email', 'password']);

        $user = DB::table('users')->where('email', $request->input('email'))->first();
        if ((int)$user->role === 0) {
            $credentials['status'] = 1;
        }

        if (Auth::attempt($credentials, (int)$request->input('remember') === 1)) {
            return redirect()->route('dashboard');
        }

        return redirect()->back()->withErrors(['Invalid Credentials.']);

    }

    public function users(Request $request) {
        switch((int)Auth::user()->role) {
            case 0:
                $users = DB::table('users')->where('id', Auth::user()->id)->get();
                break;
            case 1:
            case 2:
                $users = DB::table('users')->where('role', 0)->get();
                break;
            default:
                return response()->json(['status' => 'fail', 'message' => 'Invalid role.'], 400);
        }
        if ($request->has('contentType') && $request->input('contentType') === 'html') {
            $users = view('users', compact('users'))->render();
        }
        return response()->json([
            'status'    => 'ok',
            'users'     => $users
        ]);
    }

    public function user($id) {
        $user = DB::table('users')->where('id', $id)->first();
        return $user;
    }

    public function approveUser($id) {
        if (!in_array((int)Auth::user()->role, [1,2])) {
            return redirect()->route('dashboard');
        }
        DB::table('users')->where('id', $id)->update(['status' => 1]);
        return redirect()->route('dashboard');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('user.login.view');
    }

    public function deleteUser($id) {
        if (!in_array((int)Auth::user()->role, [1,2])) {
            return redirect()->route('dashboard');
        }
        $user = DB::table('users')->where('id', $id)->first();
        if (Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        if (Storage::disk('public')->exists($user->signature)) {
            Storage::disk('public')->delete($user->signature);
        }
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('dashboard');
    }

    public function updateUser(Request $request, $id) {
        if (!in_array((int)Auth::user()->role, [0,2])) {
            return redirect()->route('dashboard');
        }
        if (Auth::user()->role != 2 && Auth::user()->id != $id) {
            return redirect()->route('dashboard');
        }
        $rules = [
            'name'              => 'sometimes|required|nullable|max:50',
            'mobile'            => 'sometimes|required|nullable|numeric|digits:10',
            'email'             => ['sometimes','required', 'nullable','email:dns,filter', Rule::unique('users', 'email')->where('role', 0)->ignore($id)],
            'password'          => 'sometimes|required|nullable|confirmed',
            'address'           => 'sometimes|required|nullable|max:255',
            'gender'            => 'sometimes|required|nullable|in:0,1',
            'date_of_birth'     => [
                'sometimes',
                'required',
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    $maxTimestamp = now()->subYears(18)->timestamp;
                    $value = strtotime($value);
                    if ($value > $maxTimestamp) {
                        $fail("You must be at least 18 years old to register.");
                    }
                }
            ],
            'profile_picture'   => 'sometimes|required|nullable|file|mimes:jpg,png',
            'signature'         => 'sometimes|required|nullable|file|mimes:jpg,png',
        ];

        $messages = ['gender.in' => 'Gender can either be Male or Female.'];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $dataToUpdate = [];
        if ($request->has('name')) {
            $dataToUpdate['name'] = $request->input('name');
        }
        if ($request->has('mobile')) {
            $dataToUpdate['mobile'] = $request->input('mobile');
        }
        if ($request->has('email')) {
            $dataToUpdate['email'] = $request->input('email');
        }
        if ($request->has('password')) {
            $dataToUpdate['password'] = Hash::make($request->input('password'));
        }
        if ($request->has('address')) {
            $dataToUpdate['address'] = $request->input('address');
        }
        if ($request->has('gender')) {
            $dataToUpdate['gender'] = $request->input('gender');
        }
        if ($request->has('date_of_birth')) {
            $dataToUpdate['date_of_birth'] = $request->input('date_of_birth');
        }
        $user = DB::table('users')->where('id', $id)->first();
        if ($request->has('profile_picture')) {
            if (Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $profilePicture = Storage::disk('public')->putFile('', $request->file('profile_picture'));
            $dataToUpdate['profile_picture'] = $profilePicture;
        }
        if ($request->has('signature')) {
            if (Storage::disk('public')->exists($user->signature)) {
                Storage::disk('public')->delete($user->signature);
            }
            $signature = Storage::disk('public')->putFile('', $request->file('signature'));
            $dataToUpdate['signature'] = $request->input('signature');
        }
        DB::table('users')->where('id', $id)->update($dataToUpdate);
        return redirect()->route('dashboard');
    }

}
