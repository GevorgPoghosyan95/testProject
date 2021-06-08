<?php

namespace App\Services;

use App\BookItem;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookItemService extends Model
{
    public $errors = [];

    /**
     * @param $country_code
     * @return array|string
     * Validate country code
     */
    public function validateCountryCode($country_code){
        $client = new Client();
        try{
            $response = $client->request('GET', 'http://country.io/continent.json');
            $result = $response->getBody()->getContents();
        }catch (GuzzleException $exception){
            Log::info('Country code validation exception '.$exception->getMessage());
            return $this->errors[]['message'] = $exception->getMessage();
        }
        if (!array_key_exists($country_code, json_decode($result))) {
            $this->errors[]['message'] = 'Country code validation error';
        }
        return $this->errors;

    }

    /**
     * @param $time_zone
     * @return array|string
     * Validate time zone by name
     */
    public function validateTimeZone($time_zone){
        $client = new Client();
        try{
            $response = $client->request('GET', 'http://worldtimeapi.org/api/timezone');
            $result = $response->getBody()->getContents();
        }catch (GuzzleException $exception){
            Log::info('Time zone validation exception '.$exception->getMessage());
            return $this->errors[]['message'] = $exception->getMessage();
        }

        if (array_search($time_zone, json_decode($result)) === false) {
            $this->errors[]['message'] = 'Time zone validation error';
        }
        return $this->errors;
    }

    /**
     * @param $itemData
     * @return bool
     * Create book item in phone book
     */
    public function create($itemData){
        $duplicates = BookItem::where(['first_name'=>$itemData['first_name'],'phone'=>$itemData['phone']])->count();
        if($duplicates > 0){
            return false;
        }
        $bookItem = BookItem::create($itemData);
        $bookItem->user_id = Auth::id();
        $bookItem->save();
        return true;
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * Validate request (combine validate country & validate time zone)
     */
    public function validateRequest($request){
        $countryCodeValidationErrors = $this->validateCountryCode($request->get('country_code'));
        if(!empty($countryCodeValidationErrors)){
            return response()->json(['status'=>'failed','error'=>$countryCodeValidationErrors]);
        }

        $timeZoneValidationErrors = $this->validateTimeZone($request->get('timezone_name'));
        if(!empty($timeZoneValidationErrors)){
            return response()->json(['status'=>'failed','error'=>$timeZoneValidationErrors]);
        }
    }

}
