<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Profile;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Validator;
use Auth;

class ProfileController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param    int  $id
     *
     * @return  Response
     */
    public function show($id)
    {
        $profile = User::findOrFail($id);

        return view('profile.show', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int  $id
     *
     * @return  Response
     */
    public function edit()
    {
        $profile = auth()->user();

        return view('profile.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    int  $id
     *
     * @return  Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required', 
            'email' => 'required', 
            'password' => 'required'
        ]);
        
        $validator->after(function($validator) use ($request) {
            if (!$validator->errors()->has('password')) {
                if (!Auth::attempt(['email' => auth()->user()->email, 'password' => $request->input('password')])) {
                    $validator->errors()->add('password', 'Your current password is invalid');
                }
            }
        });

        if ($validator->fails()) {
            return redirect('profile')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $profile = auth()->user();
        
        $data = $request->except(['newpassword', 'password']);
        if($request->input('newpassword')) {
            $data['newpassword'] = bcrypt($request->input('newpassword'));
        }
        
        $profile->update($data);

        \Flash::success('Profile updated!');

        return redirect('profile');
    }
}
