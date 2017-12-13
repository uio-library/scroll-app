<?php

namespace App\Http\Controllers;

use App\Integration;
use App\User;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;

class GithubController extends Controller
{

    protected $serviceName = 'github';

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @param Socialite $socialite
     * @return \Illuminate\Http\Response
     */
    public function connect(Socialite $socialite)
    {
        return $socialite->driver('github')
            ->scopes(['repo', 'write:repo_hook'])
            ->redirect();
    }

    public function disconnect(Request $request)
    {
        $request->user()->integrations()->where('service_name', '=', $this->serviceName)->delete();

        \Session::flash('status', 'GitHub account disconnected');
        return response()->redirectToRoute('account');
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param Socialite $socialite
     * @return \Illuminate\Http\Response
     */
    public function handleCallback(Socialite $socialite)
    {
        $user = Auth::user();

        $githubUser = $socialite->driver('github')->user();
        $accountId = $githubUser->getNickname();
        $accountData = [
            'id' => $githubUser->getId(),
            'name' => $githubUser->getName(),
            'nick' => $githubUser->getNickname(),
            'email' => $githubUser->getEmail(),
            'avatar' => $githubUser->getAvatar(),
            'token' => $githubUser->token,
            'refreshToken' => $githubUser->refreshToken, // can be null
            'expiresIn' => $githubUser->expiresIn, // can be null
        ];

        if (!is_null($user)) {
            // User was already logged in, check if we should add an integration

            $integration = $user->integrations()
                ->where('service_name', '=', $this->serviceName)
                ->first();

            if (is_null($integration)) {

                $user->integrations()->create([
                    'user_id' => $user->id,
                    'service_name' =>  $this->serviceName,
                    'account_id' => $accountId,
                    'account_data' => $accountData,
                ]);

                \Log::notice('Added Github integration to existing user.', ['id' => $accountId]);
                \Session::flash('status', 'GitHub account connected');
            }

        } else {
            // User was not logged in, check if we should create a new user or just log in

            $integration = Integration::where('service_name', '=', $this->serviceName)
                ->where('account_id', '=', $accountId)
                ->first();

            if (is_null($integration)) {
                \Session::flash('error', 'Beklager, du kan kun logge inn med GitHub hvis GitHub-kontoen din er koblet til en Scroll-konto.');
                return response()->redirectToRoute('login');
            }

            \Auth::login($integration->user);
        }

        return response()->redirectToRoute('account');
    }

    public function repos(Request $request)
    {
        $github = $request->user()->getGithubManager();

        return response()->json([
            'repos' => $github->me()->repositories(),
        ]);
    }
}
