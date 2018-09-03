<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UsersAddress;
use App\Company;

class VendorApplication extends Model
{
    protected $guarded = [];

    // check if user has applied with no response
    public static function hasUserApplied($userId)
    {
        return ! Self::where([
            'user_id' => $userId,
            'accepted' => null
        ])->get()->isEmpty();
    }

    // check if user has been rejected
    public static function hasApplicationBeenRejected($userId)
    {
        return ! Self::where([
            'user_id' => $userId,
            'accepted' => 0
        ])->get()->isEmpty();
    }

    // gets unanswered applications
    public static function scopeWhereFresh($query)
    {
        return $query->where('accepted', '=', NULL);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Returns an error in the application creation process.
     *
     * @param  int  $userId, string  $companyName, int  $usersAddressId
     * @return string|false The error text or false implying no errors occurred.
     */
    public static function getError($userId, $companyName, $usersAddressId)
    {
        /**
         * Error if previously applied
         */
        if(self::hasUserApplied($userId))
            return 'Your existing application is being processed.';

        /**
         * Error if previous application rejected
         */
        if(self::hasApplicationBeenRejected($userId))
            return 'Unfortunately your previous application was rejected and you cannot apply again. For more information contact administrator.';

        /**
         * Error if no address on file
         */
        if(UsersAddress::where('user_id', '=', $userId)->get()->isEmpty())
            return 'You must have at least one address on file.';

        /**
         * Error if company name not given |
         * Error if company name already exists
         */
        if(! isset($companyName))
        {
            return 'Company name not provided.';
        }
        elseif(self::doesCompanyNameExist($companyName) || Company::doesCompanyNameExist($companyName))
        {
            return 'Company Name already exists.';
        }

        /**
         * Error if address not given |
         * Error if address doesn't exist
         */
        if(! isset($usersAddressId) || ! is_numeric($usersAddressId))
        {
            return 'Address not provided.';
        }
        else
        {
            if(UsersAddress::where('id', '=', $usersAddressId)->get()->isEmpty())
            {
                return 'Address not provided.';
            }
        }

        return FALSE;
    }

    /**
     * Find whether a given company name already exists in this model.
     *
     * @param  string  $companyName
     */
    public static function doesCompanyNameExist($companyName)
    {
        return ! self::where('proposed_company_name', '=', $companyName)->get()->isEmpty();
    }
}
