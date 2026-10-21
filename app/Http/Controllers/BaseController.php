<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class BaseController extends Controller
{
    public static $api_client_manager;

    public function __construct()
    {
        $this::$api_client_manager = new ApiClientManager();
    }

    /**
     * Dashboard legacy (faute historique conservée pour les routes existantes).
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function dashbord()
    {
        return $this->dashboard();
    }

    /**
     * Tableau de bord Blade legacy (données via API distante).
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        $membres = $this::$api_client_manager::call('GET', getApiURL() . '/user/find_by_role/fr/Membre', session()->get('tokenUserActive'));
        $medias = $this::$api_client_manager::call('GET', getApiURL() . '/media');
        $dons = $this::$api_client_manager::call('GET', getApiURL() . '/donation', session()->get('tokenUserActive'));
        $online = $this::$api_client_manager::call('GET', getApiURL() . '/userOnline', session()->get('tokenUserActive'));

        return view('pages.home', compact('membres', 'medias', 'dons', 'online'));
    }
    public function index()
    {
        $medias = $this::$api_client_manager::call('GET', getApiURL() . '/media?page=' . request()->get('page'));
        // dd($medias);

        return view("pages.film", compact('medias'));
    }

    public function types()
    {
        $types = $this::$api_client_manager::call('GET', getApiURL() . '/type');
        $groups = $this::$api_client_manager::call('GET', getApiURL() . '/group', session()->get("tokenUserActive"));
        // dd($groups);

        return view("pages.type", compact('types', 'groups'));
    }
    public function categories()
    {
        $categories = $this::$api_client_manager::call('GET', getApiURL() . '/category');
        // dd($categories);

        return view("pages.serie", compact('categories'));
    }
    public function client()
    {
        $membres = $this::$api_client_manager::call('GET', getApiURL() . '/user/find_by_role/fr/Membre', session()->get("tokenUserActive"));
        $type = $this::$api_client_manager::call('GET', getApiURL() . '/media/find_all_by_age_type/0/4', session()->get("tokenUserActive"));
        // dd($type);

        return view("pages.client", compact('membres'));
    }

    /**
     * Handle response
     *
     * @param  $result
     * @param  $msg
     * @return \Illuminate\Http\Response
     */
    public function handleResponse($result, $msg)
    {
        $res = [
            'success' => true,
            'message' => $msg,
            'data' => $result,
        ];

        return response()->json($res, 200);
    }

    /**
     * Handle response error
     *
     * @param  $error
     * @param array  $errorMsg
     * @param  $code
     * @return \Illuminate\Http\Response
     */
    public function handleError($error, $errorMsg = [], $code = 404)
    {
        $res = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMsg)) {
            $res['data'] = $errorMsg;
        }

        return response()->json($res, $code);
    }
}
