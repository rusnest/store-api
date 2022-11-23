<?php

namespace App\Http\Grants;

use Laravel\Passport\Bridge\User;
use App\Models\v1\User as ModelsUser;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;

class IdGrant extends PasswordGrant
{
    protected function validateUser(
        ServerRequestInterface $request,
        ClientEntityInterface $client
    ) {
        $id = $this->getRequestParameter('id', $request);

        if (\is_null($id)) {
            throw OAuthServerException::invalidRequest('id');
        }

        $user = $this->getUser($id);

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidGrant();
        }

        return $user;
    }

    private function getUser($id)
    {
        $user = ModelsUser::find($id);
        if (!$user) {
            return;
        }
        return new User($user->getAuthIdentifier());
    }

    public function getIdentifier()
    {
        return 'id_grant';
    }
}
