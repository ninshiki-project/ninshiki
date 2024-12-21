<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * @throws ConnectionException
     */
    public function requestForProviderLogin(Request $request): Application|Redirector|RedirectResponse|InertiaResponse|Response
    {
        $response = Http::ninshiki()->get('/login/zoho');

        if ($response->status() !== 200) {
            return Inertia::render('Error', [
                'status' => $response->getStatusCode(),
                'redirect' => route('login.page'),
            ])
                ->toResponse($request)
                ->setStatusCode($response->status());
        }

        return redirect($response->object()->link);
    }

    /**
     * @throws ConnectionException
     */
    public function callbackForProviderLogin(Request $request): Application|RedirectResponse|Redirector|InertiaResponse|Response
    {
        $response = Http::ninshiki()
            ->post('/login/zoho', [
                'code' => $request->get('code'),
            ]);
        $body = $response->object();

        if ($response->status() === 401) {
            abort(401);
        }

        if ($response->status() === 422) {
            return Inertia::render('Error', [
                'status' => $response->getStatusCode(),
                'redirect' => route('login.page'),
                'message' => 'Whoops, It seems that the code generated by the provider already expired.',
            ])
                ->toResponse($request)
                ->setStatusCode($response->status());
        }

        $request->session()->regenerate();

        // store session and access tokens
        $request->session()->put([
            'token' => $body->token->accessToken,
            'userId' => $body->user->id,
            'user' => $body->user,
        ]);

        return redirect(route('feed'));

    }
}