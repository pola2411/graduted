<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\HelperApi;
use App\Models\User;

class HospitalController extends Controller
{
    use HelperApi;
    public function getHospitals(){
        $data=User::where('account_type','=',0)->get();
        return $this->onSuccess(200, 'List Hospital', $data);


    }


}
