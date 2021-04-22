<?php
    namespace App\Controllers;
    use App\Dao\User;
    use App\Dao\Aplicacao;
    
    class AuthController {
        private static $key = 'HN2ws@GbLg@o'; //APPlication Key
        
        public function login(){
            $user = User::login($_POST['email'],$_POST['senha'])->fetchobject();
            if($user->ativo){
                //Header Token
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];

                //Payload - Content
                $payload = [
                    'name' => $user->nome,
                    'email' => $user->email,
                ];

                //JSON
                $header = json_encode($header);
                $payload = json_encode($payload);

                //Base 64
                $header = self::base64UrlEncode($header);
                $payload = self::base64UrlEncode($payload);

                //Sign
                $apl = Aplicacao::selectId($user->idAplicacao)->fetchobject();
                $sign = hash_hmac('sha256', $header . "." . $payload, $apl->key, true);
                $sign = self::base64UrlEncode($sign);

                //Token
                $token = $header . '.' . $payload . '.' . $sign;

                return $token;
            }
            
            throw new \Exception('Não autenticado');

        }

        public static function checkAuth()
        {
            $http_header = apache_request_headers();

            if (isset($http_header['Authorization']) && $http_header['Authorization'] != null) {
                $bearer = explode (' ', $http_header['Authorization']);

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

            throw new \Exception('Não autenticado');
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
