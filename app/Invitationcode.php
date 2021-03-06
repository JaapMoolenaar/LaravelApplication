<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitationcode extends Model
{

    /**
     * The database table used by the model.
     *
     * @var  string
     */
    protected $table = 'invitationcodes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var  array
     */
    protected $fillable = ['code', 'used_at'];

}
