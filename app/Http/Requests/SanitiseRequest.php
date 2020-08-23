<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;

class SanitiseRequest extends FormRequest
{    
    /**
     * Indicate whether request data is safe to use.
     * 
     * @var bool $clean
     */
    private $clean = false;
    
    /**
     * Override existing all method from parent class.
     * 
     * @param  $KEYS = NULL - Same signature as parent::all()
     * @return array
     */
    public function all($KEYS=NULL){
        return $this->sanitise(parent::all());
    }

    protected function sanitise($inputs){
        if($this->clean || null === $inputs) { 
            return $inputs;
        }

        if ("string" === gettype($inputs)) {
            $json_in = json_decode($inputs, true);
            if (!json_last_error()) {
                $inputs = $json_in;
            }
        }

        foreach($inputs as $i => $item){
            $inputs[$i] = CommonHelper::sanitise($item);
        }

        $this->replace($inputs);
        $this->clean = true;
        return $inputs;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $result = true;
        
        $user = \App\User::attemptAuth();
        $client_hash_key = $this->header("X-CLIENT-HASH-KEY");

        if(null === $user && null === $client_hash_key)
        {
            $result = false;
        }

        return $result;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
