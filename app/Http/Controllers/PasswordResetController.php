<?php

namespace App\Http\Controllers\API;

use App\Models\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Resources\PasswordReset as ResourcesPasswordReset;
use App\Models\User;
use Nette\Utils\Random;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class PasswordResetController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $password_resets = PasswordReset::orderByDesc('updated_at')->get();

        return $this->handleResponse(ResourcesPasswordReset::collection($password_resets), __('notifications.find_all_password_resets_success'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $random_string = (string) random_int(1000000, 9999999);
        // Get inputs
        $inputs = [
            'email' => $request->email,
            'phone' => $request->phone,
            'token' => $random_string,
            'former_password' => $request->former_password
        ];

        // Validate required fields
        if ($inputs['email'] == null AND $inputs['phone'] == null) {
            return $this->handleError(__('validation.email_or_phone.required'), 400);
        }

        if ($inputs['email'] == ' ' AND $inputs['phone'] == ' ') {
            return $this->handleError(__('validation.email_or_phone.required'), 400);
        }

        if ($inputs['email'] == null AND $inputs['phone'] == ' ') {
            return $this->handleError(__('validation.email_or_phone.required'), 400);
        }

        if ($inputs['email'] == ' ' AND $inputs['phone'] == null) {
            return $this->handleError(__('validation.email_or_phone.required'), 400);
        }

        if ($inputs['email'] != null) {
            $existing_password_reset = PasswordReset::where('email', $inputs['email'])->first();

            if ($existing_password_reset != null) {
                $existing_password_reset->delete();
            }

            $password_reset = PasswordReset::create($inputs);

            return $this->handleResponse(new ResourcesPasswordReset($password_reset), __('notifications.create_password_reset_success'));
        }

        if ($inputs['phone'] != null) {
            $existing_password_reset = PasswordReset::where('phone', $inputs['phone'])->first();

            if ($existing_password_reset != null) {
                $existing_password_reset->delete();
            }

            $password_reset = PasswordReset::create($inputs);

            return $this->handleResponse(new ResourcesPasswordReset($password_reset), __('notifications.create_password_reset_success'));
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
        $password_reset = PasswordReset::find($id);

        if (is_null($password_reset)) {
            return $this->handleError(__('notifications.find_password_reset_404'));
        }

        return $this->handleResponse(new ResourcesPasswordReset($password_reset), __('notifications.find_password_reset_success'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PasswordReset  $password_reset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PasswordReset $password_reset)
    {
        $random_string = (string) random_int(1000000, 9999999);
        // Get inputs
        $inputs = [
            'id' => $request->id,
            'email' => $request->email,
            'phone' => $request->phone,
            'token' => $random_string,
            'former_password' => $request->former_password,
            'updated_at' => now()
        ];

        if ($inputs['email'] == null AND $inputs['phone'] == null) {
            return $this->handleError(__('validation.email_or_phone.required'), 400);
        }

        if ($inputs['email'] == ' ' AND $inputs['phone'] == ' ') {
            return $this->handleError(__('validation.email_or_phone.required'), 400);
        }

        if ($inputs['email'] == null AND $inputs['phone'] == ' ') {
            return $this->handleError(__('validation.email_or_phone.required'), 400);
        }

        if ($inputs['email'] == ' ' AND $inputs['phone'] == null) {
            return $this->handleError(__('validation.email_or_phone.required'), 400);
        }

        $password_reset->update($inputs);

        return $this->handleResponse(new ResourcesPasswordReset($password_reset), __('notifications.update_password_reset_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PasswordReset  $password_reset
     * @return \Illuminate\Http\Response
     */
    public function destroy(PasswordReset $password_reset)
    {
        $password_reset->delete();

        $password_resets = PasswordReset::all();

        return $this->handleResponse(ResourcesPasswordReset::collection($password_resets), __('notifications.delete_password_reset_success'));
    }

    // ==================================== CUSTOM METHODS ====================================
    /**
     * Search a password reset by e-mail
     *
     * @param  string $data
     * @return \Illuminate\Http\Response
     */
    public function searchByEmail($data)
    {
        $password_reset = PasswordReset::where('email', $data)->first();
        $user = User::where('email', $data)->first();

        if (is_null($user)) {
            return $this->handleError(__('notifications.find_user_404'));
        }

        if (is_null($password_reset)) {
            return $this->handleError(__('notifications.find_password_reset_404'));
        }

        if ($password_reset->email != null) {
            $random_string = (string) random_int(1000000, 9999999);

            $password_reset->update([
                'former_password' => Random::generate(7),
                'token' => $random_string,
                'updated_at' => now()
            ]);

            // Mutator User hashe déjà : passer le clair uniquement
            $user->update([
                'password' => $password_reset->former_password,
                'updated_at' => now(),
            ]);
        }

        $password_reset->refresh();

        return $this->handleResponse([
            ...(new ResourcesPasswordReset($password_reset))->resolve(),
            'temporary_password' => $password_reset->former_password,
        ], __('notifications.find_password_reset_success'));
    }

    /**
     * Search a password reset by phone
     *
     * @param  string $data
     * @return \Illuminate\Http\Response
     */
    public function searchByPhone($data)
    {
        $password_reset = PasswordReset::where('phone', $data)->first();
        $user = User::where('phone', $data)->first();

        if (is_null($user)) {
            return $this->handleError(__('notifications.find_user_404'));
        }

        if (is_null($password_reset)) {
            return $this->handleError(__('notifications.find_password_reset_404'));
        }

        if ($password_reset->phone != null) {
            $random_string = (string) random_int(1000000, 9999999);

            $password_reset->update([
                'token' => $random_string,
                'updated_at' => now(),
            ]);

            $user->update([
                'password' => $password_reset->former_password,
                'updated_at' => now(),
            ]);
        }

        $password_reset->refresh();

        return $this->handleResponse([
            ...(new ResourcesPasswordReset($password_reset))->resolve(),
            'temporary_password' => $password_reset->former_password,
        ], __('notifications.find_password_reset_success'));
    }
}
