<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Aacotroneo\Saml2\Events\Saml2LogoutEvent;
use App\Integration;
use App\User;
use Illuminate\Events\Dispatcher;

class Saml2EventSubscriber
{
    protected $serviceName = 'webid';

    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  Saml2LoginEvent  $event
     * @return void
     */
    public function onUserLogin(Saml2LoginEvent $event)
    {
        $data = $event->getSaml2User();
        $uid = $data->getUserId();
        $attrs = $data->getAttributes();

        $feideId = array_get($attrs, 'eduPersonPrincipalName.0');

        if (!$feideId) {
            \Log::notice('No uid returned in SAML2 login event.');
            \Session::flash('error', 'An unknown error occured during login.');
            return;
        }

        $user = \Auth::user();

        if (!is_null($user)) {
            // User was already logged in, check if we should add an integration

            $integration = $user->integrations()
                ->where('service_name', '=', $this->serviceName)
                ->first();

            if (is_null($integration)) {

                $user->integrations()->create([
                    'user_id' => $user->id,
                    'service_name' =>  $this->serviceName,
                    'account_id' => $feideId,
                    'account_data' => [
                        'saml_id' => $uid,
                        'saml_session' => $data->getSessionIndex(),
                    ],
                ]);

                \Log::notice('Added WebID integration to existing user.', ['id' => $feideId]);
            }

        } else {
            // User was not logged in, check if we should create a new user or just log in

            $integration = Integration::where('service_name', '=', $this->serviceName)
                ->where('account_id', '=', $feideId)
                ->first();

            if (is_null($integration)) {
                $user = User::create([
                    'name' => $attrs['cn'][0],
                    'email' => $attrs['mail'][0],
                ]);
                $integration = $user->integrations()->create([
                    'service_name' =>  $this->serviceName,
                    'account_id' => $feideId,
                    'account_data' => [
                        'saml_id' => $uid,
                        'saml_session' => $data->getSessionIndex(),
                    ],
                ]);
                \Log::notice('Registered new WebID user.', ['id' => $feideId]);
            } else {
                $integration->account_data = [
                    'saml_id' => $uid,
                    'saml_session' => $data->getSessionIndex(),
                ];
                $integration->save();
            }

            \Auth::login($integration->user);
        }
    }

    /**
     * Handle the event.
     *
     * @param  Saml2LogoutEvent  $event
     * @return void
     */
    public function onUserLogout(Saml2LogoutEvent $event)
    {
        \Auth::logout();
        \Session::save();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'Aacotroneo\Saml2\Events\Saml2LoginEvent',
            'App\Listeners\Saml2EventSubscriber@onUserLogin'
        );
        $events->listen(
            'Aacotroneo\Saml2\Events\Saml2LogoutEvent',
            'App\Listeners\Saml2EventSubscriber@onUserLogout'
        );
    }
}
