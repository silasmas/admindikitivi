<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Resources\Group as ResourcesGroup;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\ApiClientManager;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class GroupController extends BaseController
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
        $groupes = $this::$api_client_manager::call('GET', getApiURL() . '/group?page=' . request()->get('page'),session()->get("tokenUserActive"));
        //  dd($groupes);
        return view("pages.groupes", compact('groupes'));

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
            'group_name_fr' => $request->group_name_fr,
            'group_name_en' => $request->group_name_en,
            'group_name_ln' => $request->group_name_ln,
            'group_description' => $request->group_description
        ];
       
        $rep = $this::$api_client_manager::call('POST', getApiURL() . '/group', session()->get("tokenUserActive"), $inputs);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => $rep->message]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show_Groupe($id)
    {
        $rep = $this::$api_client_manager::call('GET', getApiURL() . '/group/' . $id, session()->get("tokenUserActive"));
        //    dd($rep);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' =>"Groupe trouvé, vous pouvez modifier", 'data' => $rep->data]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        // Get inputs
        $inputs = [
            'id' => $request->id,
            'group_name_fr' => $request->group_name_fr,
            'group_name_en' => $request->group_name_en,
            'group_name_ln' => $request->group_name_ln,
            'group_description' => $request->group_description,
        ];
        // dd($inputs);
        $rep = $this::$api_client_manager::call('PUT', getApiURL() . '/group/' . $request->id, session()->get("tokenUserActive"), $inputs);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => "Modification réussi"]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de modification."]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rep = $this::$api_client_manager::call('DELETE', getApiURL() . '/group/' . $id, session()->get("tokenUserActive"));
        // dd($rep->success);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => $rep->message]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }
}
