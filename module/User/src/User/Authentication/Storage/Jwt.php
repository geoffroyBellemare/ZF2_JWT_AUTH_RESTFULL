<?php

namespace User\Authentication\Storage;

use User\Service\Jwt as JwtService;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Parser;
use Zend\Authentication\Storage\StorageInterface;

/**
 * Class Jwt
 * @package Carnage\JwtZendAuth\Authentication\Storage
 */
class Jwt implements StorageInterface
{
    const SESSION_CLAIM_NAME = 'session-data';
    const DEFAULT_EXPIRATION_SECS = 6000;

    /**
     * @var bool
     */
    private $hasReadClaimData = false;

    /**
     * @var Token
     */
    public $token;

    /**
     * @var StorageInterface
     */
    public $wrapped;

    /**
     * @var JwtService
     */
    public $jwt;

    /**
     * @var int
     */
    private $expirationSecs;

    /**
     * @param JwtService $jwt
     * @param StorageInterface $wrapped
     * @param int $expirationSecs
     */
    public function __construct(
        JwtService $jwt,
        StorageInterface $wrapped,
        $expirationSecs = self::DEFAULT_EXPIRATION_SECS
    ) {
        $this->jwt = $jwt;
        $this->wrapped = $wrapped;
        $this->expirationSecs = $expirationSecs;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->read() === null;
    }

    /**
     * @return mixed
     */
    public function read()
    {
        if (!$this->hasReadClaimData) {
            $this->hasReadClaimData = true;
            if ($this->shouldRefreshToken()) {
                $this->writeToken($this->retrieveClaim());
            }
        }

        return $this->retrieveClaim();
    }

    /**
     * @param mixed $contents
     */
    public function write($contents)
    {

        if ($contents !== $this->read()) {
            $this->writeToken($contents);
        }
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->wrapped->clear();
    }

    /**
     * @return bool
     */
    private function hasTokenValue()
    {


        return ($this->wrapped->read() !== null);
    }

    /**
     * @return Token|null
     */
    public function retrieveToken()
    {

        if($this->wrapped->read() === null)return new Token();
        //('retriveToken == null', $this->wrapped->read());
        if ($this->token === null) {
            $this->token = $this->jwt->parseToken($this->wrapped->read());
            //var_dump('----parseToken----');
            $token = (new Parser())->parse($this->wrapped->read());
           // var_dump($token);
            //var_dump('----');
        }

        return $this->token;
    }

    /**
     * @return mixed|null
     */
    private function retrieveClaim()
    {
//        if (!$this->hasTokenValue()) {
//            var_dump('retrieveClaim: !$this->hasTokenValue');
//            return null;
//        }

        try {

            return $this->retrieveToken()->getClaim(self::SESSION_CLAIM_NAME);
        } catch (\OutOfBoundsException $e) {
            //var_dump('retrieveClaim: !$this->hasTokenValue');
            return null;
        }
    }

    /**
     * @return bool
     */
    private function shouldRefreshToken()
    {
        if (!$this->hasTokenValue()) {
            return false;
        }

        try {
            return date('U') >= ($this->retrieveToken()->getClaim('iat') + 60) && $this->retrieveClaim() !== null;
        } catch (\OutOfBoundsException $e) {
            return false;
        }
    }

    /**
     * @param $claim
     */
    private function writeToken($claim)
    {
        try {
            $this->token = $this->jwt->createSignedToken(self::SESSION_CLAIM_NAME, $claim, $this->expirationSecs);
            $this->wrapped->write(
                $this->token->__toString()
            );
            //var_dump($this->token->__toString());

        } catch (\RuntimeException $e) {}
    }
}