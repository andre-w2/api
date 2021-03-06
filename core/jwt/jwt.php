<?php
require_once __DIR__ .'/vendor/autoload.php';

use Firebase\JWT\JWT;

class JwtHandler
{
    protected $jwt_secrect;
    protected $token;
    protected $issuedAt;
    protected $expire;
    protected $jwt;

    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->issuedAt = time();

        $this->expire = $this->issuedAt + 3600;

        $this->jwt_secrect = "vuePHP";
    }

    public function jwtEncodeData($iss)
    {

        $this->token = array(
            "iss" => $iss,
            "aud" => $iss,
            "iat" => $this->issuedAt,
            "exp" => $this->expire,
        );

        $this->jwt = JWT::encode($this->token, $this->jwt_secrect, 'HS256');
        return $this->jwt;
    }

    public function jwtDecodeData($jwt_token)
    {
        try {
            $decode = JWT::decode($jwt_token, $this->jwt_secrect, array('HS256'));
            return $decode->{'iss'};
        } catch (Exception $e) {
            return [
                "message" => $e->getMessage()
            ];
        }
    }
}