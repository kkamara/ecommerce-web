<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Company\Traits\CompanyRelations;

class Company extends Model
{
    use SoftDeletes, HasFactory;
    use CompanyRelations;

    /**
     * This models immutable values.
     *
     * @var array
     */
    protected $guarded = ['name'];

    /**
     * This models immutable date values.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Set a publicily accessible identifier to get the path for this unique instance.
     *
     * @return  string
     */
    public function getPathAttribute()
    {
        return url('/companies/'.$this->attributes['slug']);
    }

    /**
     * Find whether a given company name already exists in this model.
     *
     * @param  string  $companyName
     */
    public static function doesCompanyNameExist($companyName)
    {
        return ! self::where('name', '=', $companyName)->get()->isEmpty();
    }

    /**
     * Check if this model relationship belongs to a given user id.
     *
     * @param   int  $userId
     * @return  bool
     */
    public function belongsToUser($userId)
    {
        return $userId === $this->user_id;
    }
}
