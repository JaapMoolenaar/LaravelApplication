<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Gate;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return  Response
     */
    public function index()
    {
        $users = User::orderBy('superuser', 'desc')->orderBy('name', 'asc')->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return  Response
     */
    public function store(Request $request)
    {
        $this->authorize('create-user');
        
        $this->validate($request, ['name' => 'required', 'email' => 'required|email', 'password' => 'required', ]);

        $data = $request->except(['superuser', 'password']);
        
        $data['superuser'] = false;
        $data['password'] = bcrypt($request->input('password'));
        
        User::create($data);

        \Flash::success('User added!');

        return redirect('users');
    }

    /**
     * Display the specified resource.
     *
     * @param    int  $id
     *
     * @return  Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int  $id
     *
     * @return  Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $this->authorize('update-user', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    int  $id
     *
     * @return  Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, ['name' => 'required', 'email' => 'required|email',]);

        $user = User::findOrFail($id);
        
        $this->authorize('update-user', $user);
        if($request->input('superuser')) {
            $this->authorize('createsuperuser', $user);
        }
        
        $data = $request->except(['password', 'roles', 'permissions']);
        if($request->input('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }
        
        if (Gate::allows('manage-roles', $user)) {
            // Get and set the roles
            $rolesCollection = $request->input('roles');
            if(!$rolesCollection) {
                $rolesCollection = array();
            }
            
            $user->roles = implode(',', $rolesCollection);

            // Get the permissions
            $permissionCollection = $request->input('permissions');
            if(!$permissionCollection) {
                $permissionCollection = array();
            }

            // We'll save permissions too only for clarity, but if a user has a role
            // and not the complementing permission, it will still pass because of
            // the role
            foreach($rolesCollection as $role) {
                $permissionsFromConfig = config('acl.roles.'.$role);
                if(!is_array($permissionsFromConfig)) continue;
                
                $permissionCollection = array_merge($permissionCollection, $permissionsFromConfig);
            }

            $user->permissions = implode(',', $permissionCollection);
        }
        
        $user->update($data);

        \Flash::success('User updated!');

        return redirect('users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int  $id
     *
     * @return  Response
     */
    public function destroy($id, Request $request)
    {
        $user = User::findOrFail($id);
        
        $this->authorize('delete-user', $user);
        
        $this->validate($request, ['confirm' => 'required']);
        
        User::destroy($id);

        \Flash::success('User deleted!');

        return redirect('users');
    }
    
    public function predelete($id)
    {        
        $user = User::findOrFail($id);
        
        $this->authorize('delete-user', $user);

        return view('users.predelete', compact('user'));
    }

}
