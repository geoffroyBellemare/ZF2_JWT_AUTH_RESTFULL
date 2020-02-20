<?php

namespace User\Service;
use InvalidArgumentException;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

class Jwt
{
    /**
     * @var Signer
     */
    private $signer;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $verifyKey;

    /**
     * @var string
     */
    private $signKey;
    private $keychain;
    /**
     * @param Signer $signer
     * @param Parser $parser
     * @param $verifyKey
     * @param $signKey
     */
    public function __construct(Signer $signer, Parser $parser, $verifyKey, $signKey)
    {
        $this->signer = $signer;
        $this->verifyKey = $verifyKey;
        $this->signKey = $signKey;
        $this->parser = $parser;
        $this->keychain = new Keychain();
    }

    public function createSignedToken($claim, $value, $expirationSecs)
    {
        if (empty($this->signKey)) {
            throw new \RuntimeException('Cannot sign a token, no sign key was provided');
        }

        $timestamp = date('U');

//        return (new Builder())
//            ->setIssuedAt($timestamp)
//            ->setExpiration($timestamp + $expirationSecs)
//            ->set($claim, $value)
//            ->sign($this->signer, $this->signKey)
//            ->getToken();

        $token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
        ->setAudience('http://example.org') // Configures the audience (aud claim)
        ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item// Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time()) // Configures the time that the token was issued (iat claim)
        ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
        ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
        ->set($claim, $value) // Configures a new claim, called "uid"
        //->set('uid', 1)
        ->sign($this->signer,  $this->keychain->getPrivateKey('file://config/jwt/private.pem'))
            ->getToken();

        return $token;

    }

    public function parseToken($token)
    {
        try {
            $token = $this->parser->parse($token);
        } catch (\InvalidArgumentException $invalidToken) {
            //throw new InvalidArgumentException($invalidToken->getMessage(), 401);
            return new Token();
        }
        if (!$token->validate(new ValidationData())) {
            //throw new InvalidArgumentException("parseToken validation fail",401);
            return new Token();
        }
        if (!$token->verify($this->signer, $this->keychain->getPublicKey('file://config/jwt/public.pem'))) {
            //throw new InvalidArgumentException("parseToken signing fail",401);
            return new Token();
        }

        return $token;
    }
}