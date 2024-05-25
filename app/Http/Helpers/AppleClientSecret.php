<?php

namespace App\Http\Helpers;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\Clock\SystemClock;

class AppleClientSecret
{
    public static function generate()
    {
        $config = Configuration::forSymmetricSigner(
            Sha256::create(),
            InMemory::plainText(config('services.apple.private_key'))
        );

        $now = SystemClock::fromSystemTimezone()->now();

        $token = $config->builder()
            ->issuedBy(config('services.apple.team_id')) // iss
            ->issuedAt($now) // iat
            ->expiresAt($now->modify('+1 hour')) // exp
            ->relatedTo(config('services.apple.client_id')) // sub
            ->withClaim('aud', 'https://appleid.apple.com') // aud
            ->withHeader('kid', config('services.apple.key_id'))
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }
}
