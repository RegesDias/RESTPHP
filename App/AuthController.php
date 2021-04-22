<?php
    namespace App\Controllers;
    
    class AuthController {
        private static $key = '123456'; //APPlication Key
        
        public function login(){

            if ($_POST['email'] == 'teste@gmail.com' && $_POST['password'] == '123') {
                //Header Token
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];

                //Payload - Content
                $payload = [
                    'name' => 'APPel Capoani',
                    'email' => $_POST['email'],
                ];

                //JSON
                $header = json_encode($header);
                $payload = json_encode($payload);

                //Base 64
                $header = self::base64UrlEncode($header);
                $payload = self::base64UrlEncode($payload);

                //Sign
                $sign = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
                $sign = self::base64UrlEncode($sign);

                //Token
                $token = $header . '.' . $payload . '.' . $sign;

                return $token;
            }
            
            throw new \Exception('Não autenticado');

        }

        public static function checkAuth()
        {
            $APP_header = apache_request_headers();

            if (isset($APP_header['Authorization']) && $APP_header['Authorization'] != null) {
                $bearer = explode (' ', $APP_header['Authorization']);

                $token = explode('.', $bearer[1]);
                $header = $token[0];
                $payload = $token[1];
                $sign = $token[2];

                //Conferir Assinatura
                $valid = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
                $valid = self::base64UrlEncode($valid);

                if ($sign === $valid) {
                    return true;
                }
            }

            return false;
        }
        
        
        private static function base64UrlEncode($data)
        {
            $b64 = base64_encode($data);
            if ($b64 === false) {
                return false;
            }
            $url = strtr($b64, '+/', '-_');
            return rtrim($url, '=');
        }
    }
