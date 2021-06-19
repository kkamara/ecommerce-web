<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\UsersAddress;
use App\Models\Company\Company;

class VendorApplication extends Model
{
    use HasFactory;

    /**
     * This models immutable values.
     *
     * @property array
     */
    protected $guarded = [];

    /**
     * Checks if a given user has applied with no response.
     *
     * @param  \App\Models\User  $userId
     * @return bool
     */
    public function hasUserApplied($userId)
    {
        return false === $this->where([
                'user_id' => $userId,
                'accepted' => null
            ])
            ->get()
            ->isEmpty();
    }

    /**
     * Checks if a given user has been rejected.
     *
     * @param   \App\Models\User  $userId
     * @return  bool
     */
    public function hasApplicationBeenRejected($userId)
    {
        return ! $this->where([
                'user_id' => $userId,
                'accepted' => 0
            ])
            ->get()
            ->isEmpty();
    }

    /**
     * Adds onto a query for where vendor applications are unanswered.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $query
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeWhereFresh($query)
    {
        return $query->where('accepted', '=', NULL);
    }

    /**
     * This model relationship belongs to \App\Models\User
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Returns an error in the application creation process.
     *
     * @param  Int     $userId
     * @param  String  $companyName
     * @param  Int     $usersAddressId
     * @return String|False The error text or false implying no errors occurred.
     */
    public function getError($userId, $companyName, $usersAddressId)
    {
        /**
         * Error if previously applied
         */
        if($this->hasUserApplied($userId)) {
            return 'Your existing application is being processed.';
        }

        /**
         * Error if previous application rejected
         */
        if($this->hasApplicationBeenRejected($userId)) {
            return 'Unfortunately your previous application was rejected and you cannot apply again. For more information contact administrator.';
        }

        /**
         * Error if no address on file
         */
        if((new UsersAddress)->where('user_id', '=', $userId)->get()->isEmpty()) {
            return 'You must have at least one address on file.';
        }

        /**
         * Error if company name not given |
         * Error if company name already exists
         */
        if(! isset($companyName))
        {
            return 'Company name not provided.';
        }
        elseif(
            $this->doesCompanyNameExist($companyName) || 
            (new Company)->doesCompanyNameExist($companyName)
        ) {
            return 'Company Name already exists.';
        }
        elseif(strlen($companyName) > 191)
        {
            return 'Company Name exceeds maximum length 191.';
        }

        /**
         * Error if address not given |
         * Error if address doesn't exist
         */
        if(
            ! isset($usersAddressId) || 
            ! is_numeric($usersAddressId)
        ) {
            return 'Address not provided.';
        } else if(
            (new UsersAddress)->where([
                'id' => $usersAddressId,
                'user_id' => $userId,
            ])
                ->get()
                ->isEmpty()
        ) {
            return 'Address not provided.';
        }

        return FALSE;
    }

    /**
     * Find whether a given company name already exists for $this.
     *
     * @param  String  $companyName
     */
    public function doesCompanyNameExist($companyName)
    {
        return false === $this->where(
                'proposed_company_name', 
                '=', 
                $companyName
            )
            ->get()
            ->isEmpty();
    }

    /**
     * Returns an error in the decision process when a mod
     * reviews an instance of $this.
     *
     * @param  Int     $userId, 
     * @param  String  $companyName
     * @param  Int     $usersAddressId
     * @return String|False The error text or false implying no errors occurred.
     */
    public function getModDecisionError($reasonGiven, $acceptDecision, $declineDecision)
    {
        if(! isset($reasonGiven)) {
            return 'Reason not provided.';
        } elseif(strlen($reasonGiven) < 10) {
            return 'Reason must be longer than 10 characters.';
        } elseif(strlen($reasonGiven) > 191) {
            return 'Reason exceeds maximum length 191.';
        }

        if(! isset($acceptDecision) && ! isset($declineDecision)) {
            return 'Error processing that request. Contact system administrator.';
        }

        return FALSE;
    }
}
