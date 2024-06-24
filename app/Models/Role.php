<?php

namespace App\Models;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','slug','created_at','updated_at'];

        public function permissions()
        {
            return $this->belongsToMany(Permission::class);
        }
        public function givePermissionTo(Permission $permission)
        {
            return $this->permissions()->save($permission);
        }
        /**
         * Determine if the user may perform the given permission.
         *
         * @param  Permission $permission
         * @return boolean
         */
        public function hasPermission(Permission $permission, User $user)
        {
            return $this->hasRole($permission->roles);
        }
        /**
         * Determine if the role has the given permission.
         *
         * @param  mixed $permission
         * @return boolean
         */
        public function inRole($permission)
        {
            if (is_string($permission)) {
                return $this->permissions->contains('name', $permission);
            }
            return !! $permission->intersect($this->permissions)->count();
        }
}
