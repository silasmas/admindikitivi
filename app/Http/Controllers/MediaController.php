<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Media as ResourcesMedia;
use App\Models\Media;
use App\Models\Session;
use App\Models\User;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends BaseController
{
    public static $api_client_manager;

    public function __construct()
    {
        $this::$api_client_manager = new ApiClientManager();
    }
    public function create()
    {
        $series = $this::$api_client_manager::call('GET', getApiURL() . '/media/find_all_by_type/fr/Série TV');
        $albums = $this::$api_client_manager::call('GET', getApiURL() . '/media/find_all_by_type/fr/Album musique');
        $type = $this::$api_client_manager::call('GET', getApiURL() . '/type/find_by_group/fr/Type de média');
        $categories = $this::$api_client_manager::call('GET', getApiURL() . '/category');
        $medias = (collect($series->data))->merge(collect($albums->data));

        // dd($medias);
        return view("pages.addMedia", compact("medias", "type", "categories"));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        // Get inputs
        $inputs = [
            'media_title' => $request->media_title,
            'media_description' => $request->media_description,
            'belonging_count' => $request->belonging_count,
            'source' => $request->source,
            'time_length' => $request->time_length,
            'media_url' => $request->media_url,
            'teaser_url' => $request->file('teaser_url'),
            'author_names' => $request->author_names,
            'artist_names' => $request->artist_names,
            'writer' => $request->writer,
            'director' => $request->director,
            'published_date' => $request->published_date,
            'cover_url' => $request->file('cover_url'),
            'price' => $request->price,
            'for_youth' => $request->for_youth,
            'is_live' => $request->is_live,
            'belongs_to' => $request->belongs_to,
            'type_id' => $request->type_id,
            'user_id' => $request->user_id,
        ];
        //  dd($inputs);
        $series = $this::$api_client_manager::call('POST', getApiURL() . '/media', session()->get("tokenUserActive"), $inputs);
        return redirect()->back()->with("msg", "Enregistrement réussi");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $m = $this::$api_client_manager::call('GET', getApiURL() . '/media/' . $id);
        $media=$m->data;
        $type = $this::$api_client_manager::call('GET', getApiURL() . '/type/find_by_group/fr/Type de média');
        $categories = $this::$api_client_manager::call('GET', getApiURL() . '/category');

        $series = $this::$api_client_manager::call('GET', getApiURL() . '/media/find_all_by_type/fr/Série TV');
        $albums = $this::$api_client_manager::call('GET', getApiURL() . '/media/find_all_by_type/fr/Album musique');
        $medias = (collect($series->data))->merge(collect($albums->data));
        // dd($media);
        return view("pages.addMedia", compact("medias",'media','type','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Media $media)
    {
        // Get inputs
        $inputs = [
            'id' => $request->id,
            'media_title' => $request->media_title,
            'media_description' => $request->media_description,
            'belonging_count' => $request->belonging_count,
            'source' => $request->source,
            'time_length' => $request->time_length,
            'media_url' => $request->media_url,
            'teaser_url' => $request->file('teaser_url'),
            'author_names' => $request->author_names,
            'artist_names' => $request->artist_names,
            'writer' => $request->writer,
            'director' => $request->director,
            'published_date' => $request->published_date,
            'cover_url' => $request->file('cover_url'),
            'price' => $request->price,
            'for_youth' => $request->for_youth,
            'is_live' => $request->is_live,
            'belongs_to' => $request->belongs_to,
            'type_id' => $request->type_id,
            'user_id' => $request->user_id,
        ];
        //  dd($inputs);
        $series = $this::$api_client_manager::call('PUT', getApiURL() . '/media/'.$request->id, session()->get("tokenUserActive"), $inputs);
        return redirect()->back()->with("msg", "Modification réussie");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $rep = $this::$api_client_manager::call('DELETE', getApiURL() . '/media/' . $id,session()->get("tokenUserActive"));
        // dd($rep->success);
        if ($rep->success) {
            return response()->json(['reponse' => true, 'msg' => $rep->message]);
        } else {
            return response()->json(['reponse' => false, 'msg' => "Erreur de suppression."]);

        }

    }

    // ==================================== CUSTOM METHODS ====================================
    /**
     * Get all by title.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $data
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $data)
    {
        $medias = Media::where('media_title', 'LIKE', '%' . $data . '%')->get();

        if ($request->user_id != null) {
            $session = Session::where(['user_id', $request->user_id])->first();

            if ($session->medias() == null) {
                $session->medias()->attach($medias->pluck('id'));
            }

            if ($session->medias() != null) {
                $session->medias()->sync($medias->pluck('id'));
            }
        }

        if ($request->ip_address != null) {
            $session = Session::where(['ip_address', $request->ip_address])->first();

            if ($session->medias() == null) {
                $session->medias()->attach($medias->pluck('id'));
            }

            if ($session->medias() != null) {
                $session->medias()->sync($medias->pluck('id'));
            }
        }

        return $this->handleResponse(ResourcesMedia::collection($medias), __('notifications.find_all_medias_success'));
    }

    /**
     * Get by age and type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $for_youth
     * @param  int $type_id
     * @return \Illuminate\Http\Response
     */
    public function findAllByAgeType(Request $request, $for_youth, $type_id)
    {
        if ($request->user_id != null) {
            $medias = Media::whereHas('sessions', function ($query) use ($request) {
                $query->where('sessions.user_id', $request->user_id);
            })->where([['medias.for_youth', $for_youth], ['medias.type_id', $type_id]])->orderByDesc('medias.created_at')->get();

            return $this->handleResponse(ResourcesMedia::collection($medias), __('notifications.find_all_medias_success'));

        } else if ($request->ip_address != null) {
            $medias = Media::whereHas('sessions', function ($query) use ($request) {
                $query->where('sessions.ip_address', $request->ip_address);
            })->where([['medias.for_youth', $for_youth], ['medias.type_id', $type_id]])->orderByDesc('medias.created_at')->get();

            return $this->handleResponse(ResourcesMedia::collection($medias), __('notifications.find_all_medias_success'));

        } else {
            $medias = Media::where([['for_youth', $for_youth], ['type_id', $type_id]])->get();

            return $this->handleResponse(ResourcesMedia::collection($medias), __('notifications.find_all_medias_success'));
        }
    }

    /**
     * Approve the media.
     *
     * @param  int $user_id
     * @param  int $media_id
     * @param  int $status_id
     * @return \Illuminate\Http\Response
     */
    public function setApprobation($user_id, $media_id, $status_id)
    {
        $user = User::find($user_id);

        if (is_null($user)) {
            return $this->handleError(__('notifications.find_user_404'));
        }

        foreach ($user->medias as $med) {
            if ($med->pivot->media_id == null) {
                $user->medias()->attach([$media_id => [
                    'status_id' => $status_id,
                ]]);
            }

            if ($med->pivot->media_id != null) {
                $user->medias()->sync([$media_id => [
                    'status_id' => $status_id,
                ]]);
            }
        }
    }

    /**
     * Add media cover in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function addImage(Request $request, $id)
    {
        $inputs = [
            'media_id' => $request->entity_id,
            'image_64' => $request->base64image,
        ];

        // $extension = explode('/', explode(':', substr($inputs['image_64'], 0, strpos($inputs['image_64'], ';')))[1])[1];
        $replace = substr($inputs['image_64'], 0, strpos($inputs['image_64'], ',') + 1);
        // Find substring from replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $inputs['image_64']);
        $image = str_replace(' ', '+', $image);

        // Clean selected "medias" directory
        $file = new Filesystem;
        $file->cleanDirectory($_SERVER['DOCUMENT_ROOT'] . '/public/storage/images/medias/' . $inputs['media_id']);
        // Create image URL
        $image_url = 'images/medias/' . $inputs['media_id'] . '/' . Str::random(50) . '.png';

        // Upload image
        Storage::url(Storage::disk('public')->put($image_url, base64_decode($image)));

        $media = Media::find($id);

        $media->update([
            'cover_url' => $image_url,
            'updated_at' => now(),
        ]);

        return $this->handleResponse(new ResourcesMedia($media), __('notifications.update_media_success'));
    }
}
