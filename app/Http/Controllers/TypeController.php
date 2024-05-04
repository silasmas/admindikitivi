<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Resources\Type as ResourcesType;
use App\Models\Group;
use App\Http\Controllers\ApiClientManager;
use App\Http\Controllers\BaseController;
/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class TypeController extends BaseController
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
        $types = Type::all();
        return $this->handleResponse(ResourcesType::collection($types), __('notifications.find_all_types_success'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_type(Request $request)
    {
        // Get inputs
        $inputs = [
            'type_name_fr' => $request->type_name_fr,
            'type_name_en' => $request->type_name_en,
            'type_name_ln' => $request->type_name_ln,
            'type_description' => $request->type_description,
            'group_id' => $request->group_id,
        ];
        // dd($inputs);
        $rep = $this::$api_client_manager::call('POST', getApiURL() . '/type', session()->get("tokenUserActive"), $inputs);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => $rep->message]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }
    public function update_categorie(Request $request, Type $media)
    {
        //  dd($request->id);
        // Get inputs
        $inputs = [
            'id' => $request->id,
            'category_name_fr' => $request->category_name_fr,
            'category_name_en' => $request->category_name_en,
            'category_name_ln' => $request->category_name_ln,
            'category_description' => $request->category_description,
        ];
        // dd($inputs);
        $rep = $this::$api_client_manager::call('PUT', getApiURL() . '/category/' . $request->id, session()->get("tokenUserActive"), $inputs);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => "Modification réussi"]);
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
    public function show_type($id)
    {
        $rep = $this::$api_client_manager::call('GET', getApiURL() . '/type/' . $id);
        //   dd($rep->data);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' =>"Type trouvé, vous pouvez modifier", 'data' => $rep->data]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        // Get inputs
        $inputs = [
            'id' => $request->id,
            'type_name_fr' => $request->type_name_fr,
            'type_name_en' => $request->type_name_en,
            'type_name_ln' => $request->type_name_ln,
            'type_description' => $request->type_description,
            'group_id' => $request->group_id,
        ];
        // dd($inputs);
        $rep = $this::$api_client_manager::call('PUT', getApiURL() . '/type/' . $request->id, session()->get("tokenUserActive"), $inputs);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => "Modification réussi"]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de modification."]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rep = $this::$api_client_manager::call('DELETE', getApiURL() . '/type/' . $id, session()->get("tokenUserActive"));
        // dd($rep->success);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => $rep->message]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }
    public function destroyType($id)
    {
         $rep = $this::$api_client_manager::call('DELETE', getApiURL() . '/type/' . $id, session()->get("tokenUserActive"));
        // dd($rep->success);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => $rep->message]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }
    }

    // ==================================== CUSTOM METHODS ====================================
    /**
     * Search a type by its name.
     *
     * @param  string $locale
     * @param  string $data
     * @return \Illuminate\Http\Response
     */
    public function search($locale, $data)
    {
        $type = Type::where('type_name->' . $locale, $data)->first();

        if (is_null($type)) {
            return $this->handleError(__('notifications.find_type_404'));
        }

        return $this->handleResponse(new ResourcesType($type), __('notifications.find_type_success'));
    }

    /**
     * Find all type by group.
     *
     * @param  string $locale
     * @param  string $group_name
     * @return \Illuminate\Http\Response
     */
    public function findByGroup($locale, $group_name)
    {
        $group = Group::where('group_name->' . $locale, $group_name)->first();

        if (is_null($group)) {
            return $this->handleError(__('notifications.find_group_404'));
        }

        $types = Type::where('group_id', $group->id)->get();

        return $this->handleResponse(ResourcesType::collection($types), __('notifications.find_all_types_success'));
    }
}
