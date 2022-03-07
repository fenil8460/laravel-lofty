<?php

namespace Lofty\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;


class SalesloftLibrary extends Controller
{
    //call api
    public static function getCall($updateDate,$page){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.salesloft.com/v2/activities/calls.json?per_page=100&page='.$page.'&include_paging_counts=true&updated_at%5Bgt%5D='.$updateDate.'',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.env("SALESLOFT_API_KEY")
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    
    //calldata api
    public static function getCallData($page){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.salesloft.com/v2/call_data_records.json?per_page=100&page='.$page.'&include_paging_counts=true',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.env("SALESLOFT_API_KEY")
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    //calldata using call id api
    public static function getCallDataID($call_Id){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.salesloft.com/v2/call_data_records/'.$call_Id.'.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.env("SALESLOFT_API_KEY")
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    //get cadence data api
    public static function getCadencesData($limit,$page){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.salesloft.com/v2/cadences.json?per_page='.$limit.'&page='.$page.'&include_paging_counts=true',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.env("SALESLOFT_API_KEY")
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    //get user api
    public static function getSalesUsers($limit,$page){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.salesloft.com/v2/users.json?per_page='.$limit.'&page='.$page.'&include_paging_counts=true',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.env("SALESLOFT_API_KEY")
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    


}


