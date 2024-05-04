<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Resources\Role as ResourcesRole;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\ApiClientManager;
/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class RoleController extends BaseController
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
        $roles = $this::$api_client_manager::call('GET', getApiURL() . '/role?page=' . request()->get('page'));
        // dd($medias);
        return view("pages.role", compact('roles'));
    }

    /**
     * Store a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get inputs
        $inputs = [
            'role_name' => $request->role_name,
            'role_description' => $request->role_description
        ];
        // dd($inputs);
        $rep = $this::$api_client_manager::call('POST', getApiURL() . '/role', session()->get("tokenUserActive"), $inputs);
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
        $rep = $this::$api_client_manager::call('GET', getApiURL() . '/role/' . $id);
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
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        // Get inputs
        $inputs = [
            'id' => $request->id,
            'role_name' => $request->role_name,
            'role_description' => $request->role_description,
            'updated_at' => now()
        ];
               
        // dd($inputs);
        $rep = $this::$api_client_manager::call('PUT', getApiURL() . '/role/' . $request->id, session()->get("tokenUserActive"), $inputs);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => "Modification réussi"]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de modification."]);

        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rep = $this::$api_client_manager::call('DELETE', getApiURL() . '/role/' . $id, session()->get("tokenUserActive"));
        // dd($rep->success);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => $rep->message]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }
}
