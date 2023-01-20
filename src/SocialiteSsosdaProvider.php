<?php

namespace KominfoSidoarjo\SocialiteSsosda;

use Laravel\Socialite\Two\User;
use GuzzleHttp\Exception\GuzzleException;
use Laravel\Socialite\Two\AbstractProvider;

class SocialiteSsosdaProvider extends AbstractProvider
{
    /**
     * @var string[]
     */
    protected $scopes = [
        'openid',
        'profile',
        'email',
        'email_alias',
        'mail',
        'name',
        'id',
        'sub',
        'uid',
        'upn',
        'username',
	'phone_number',
    ];

    /**
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * @return string
     */
    public function getCognitoUrl()
    {
        return config('services.cognito.base_uri') . '/oauth2';
    }

    /**
     * @param string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getCognitoUrl() . '/authorize', $state);
    }

    /**
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->getCognitoUrl() . '/token';
    }

    /**
     * @param string $token
     *
     * @throws GuzzleException
     *
     * @return array|mixed
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post($this->getCognitoUrl() . '/userinfo', [
            'headers' => [
                'cache-control' => 'no-cache',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return User
     */
    protected function mapUserToObject(array $user)
    {
#		dd($user);
        return (new User())->setRaw($user)->map([
            'id' => $user['sub'],
            'email' => $user['email'],
            'name' => $user['name'],
            'nickname' => $user['name'],
            'email' => $user['email'],
            'no_hp' => $user['phone_number'],
#            'email_verified' => $user['email_verified'],
#            'family_name' => $user['family_name'],
#            'id' => $user['id'],
#            'username' => $user['id'],
#            'email_verified' => $user['email_verified'],
#            'family_name' => $user['family_name'],

        ]);
    }
}
