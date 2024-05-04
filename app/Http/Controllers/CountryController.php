<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\ApiClientManager;
use App\Http\Resources\Country as ResourcesCountry;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class CountryController extends BaseController
{
    public static $api_client_manager;

    public function __construct()
    {
        $this::$api_client_manager = new ApiClientManager();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pays = $this::$api_client_manager::call('GET', getApiURL() . '/country?page=' . request()->get('page'));
        // dd($medias);
        return view("pages.pays", compact('pays'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get inputs
        $inputs = [
            'country_name' => $request->country_name,
            'country_phone_code' => $request->country_phone_code,
            'country_lang_code' => $request->country_lang_code
        ];
        $rep = $this::$api_client_manager::call('POST', getApiURL() . '/country', session()->get("tokenUserActive"), $inputs);
        // dd($rep);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => "Enregistrement réussi"]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de modification."]);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rep = $this::$api_client_manager::call('GET', getApiURL() . '/country/' . $id);
        //   dd($rep->data);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' =>"Pays trouvé, vous pouvez modifier", 'data' => $rep->data]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        // Get inputs
        $inputs = [
            'id' => $request->id,
            'country_name' => $request->country_name,
            'country_phone_code' => $request->country_phone_code,
            'country_lang_code' => $request->country_lang_code,
            'updated_at' => now()
        ];
       
        // dd($inputs);
        $rep = $this::$api_client_manager::call('PUT', getApiURL() . '/country/' . $request->id, session()->get("tokenUserActive"), $inputs);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => "Modification réussi"]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de modification."]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rep = $this::$api_client_manager::call('DELETE', getApiURL() . '/country/' . $id, session()->get("tokenUserActive"));
        // dd($rep->success);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => $rep->message]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }

    // ==================================== CUSTOM METHODS ====================================
    /**
     * Search a country by its name.
     *
     * @param  string $data
     * @return \Illuminate\Http\Response
     */
    public function search($data)
    {
        $countries = Country::where('country_name', $data)->get();

        return $this->handleResponse(ResourcesCountry::collection($countries), __('notifications.find_all_countries_success'));
    }
}
