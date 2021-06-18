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
     * @property Array
     */
    protected $guarded = ['name'];

    /**
     * This models immutable date values.
     *
     * @property Array
     */
    protected $dates = ['deleted_at'];

    /**
     * Set a publicily accessible identifier to get the path for this unique instance.
     *
     * @return  String
     */
    public function getPathAttribute()
    {
        return url('/companies/'.$this->attributes['slug']);
    }

    /**
     * Find whether a given company name already exists in this model.
     *
     * @param  String  $companyName
     */
    public static function doesCompanyNameExist($companyName)
    {
        return ! self::where('name', '=', $companyName)->get()->isEmpty();
    }

    /**
     * Check if this model relationship belongs to a given user id.
     *
     * @param   Int  $userId
     * @return  Bool
     */
    public function belongsToUser($userId)
    {
        return $userId === $this->user_id;
    }
}
