<?php

namespace App;

use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'feide_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the user tokens for the user.
     */
    public function tokens()
    {
        return $this->hasMany(UserToken::class);
    }

    /**
     * Get the user integrations for the user.
     */
    public function integrations()
    {
        return $this->hasMany(Integration::class);
    }

    /**
     * Get the user rights for the user.
     */
    public function rights()
    {
        return $this->hasMany(Right::class);
    }

    public function getToken($tokenType)
    {
        return $this->tokens()
            ->where('token_type', '=', $tokenType)
            ->first();
    }

    public function isSiteAdmin()
    {
        return $this->rights()
            ->where('name', '=', 'site_admin')
            ->exists();
    }

    public function isCourseAdmin(Course $course)
    {
        return $this->rights()
            ->where('name', '=', 'course_admin')
            ->where('course_id', '=', $course->id)
            ->exists();
    }

    public function isCourseCreator()
    {
        return $this->rights()
            ->where('name', '=', 'course_creator')
            ->exists();
    }

    public function hasIntegration($serviceName)
    {
        return $this->integrations()->where('service_name', '=', $serviceName)->exists();
    }

    public function getIntegration($serviceName)
    {
        return $this->integrations()->where('service_name', '=', $serviceName)->first();
    }

    /**
     * @return GitHubManager
     */
    public function getGithubManager()
    {
        $integration = $this->getIntegration('github');

        if (is_null($integration)) {
            // THROW Exception or abort ???
            return abort(403, 'No GitHub integration set up.');
        }

        $github = app()->make('github');
        $github->authenticate($integration->account_data['token'], null, 'http_token');

        return $github;
    }
}
