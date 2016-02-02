<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'superuser'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $_roles;
    protected $_permissions;
    protected $rolePermissions;
    
    public function getRoles() {
        // Check the 'cache' first
        if($this->_roles === null) {
            $this->_roles = array_filter(explode(',', $this->roles));
        }
        
        return $this->_roles;
    }
    
    public function hasRole($role) {
        return !!in_array($role, $this->getRoles());
    }
    
    
    public function getPermissions() {
        // Check the 'cache' first
        if($this->_permissions === null) {
            $this->_permissions = array_filter(explode(',', $this->permissions));
        }
        
        return $this->_permissions;
    }
    
    public function getRolePermissions() {
        // Check the 'cache' first
        if($this->rolePermissions === null) {
            $this->rolePermissions = [];            
            foreach($this->getRoles() as $role) {
                $configPermissions = config('acl.roles.'.$role);
                if(!is_array($configPermissions)) continue;
                
                $this->rolePermissions = array_merge($this->rolePermissions, config('acl.roles.'.$role));
            }
        }
        
        return $this->rolePermissions;
    }
    
    public function hasPermission($permission, $checkroles = true) {
        // check for permission
        if(!!in_array($permission, $this->getPermissions())) {
            return true;
        }
        
        // if user doesn't have permission, het might have the role parenting 
        // the permission
        if($checkroles && !!in_array($permission, $this->getRolePermissions())) {
            return true;
        }
        
        return false;
    }
}
