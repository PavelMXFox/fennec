<?php
namespace agent;

class xcrypt {
    
    const cipher="AES-128-CBC";
    
    public static function encrypt($val, $key=null) {
        if (isset($key)) {
            $ENC_KEY=substr(md5($key),0,24);
        } else {
            return null;
        }
        
        $ivlen = openssl_cipher_iv_length(self::cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($val, self::cipher, $ENC_KEY, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $ENC_KEY, true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
        return $ciphertext;
            
    }
    
    public static function decrypt($val, $key=null) {
        if (isset($key)) {
            $ENC_KEY=substr(md5($key),0,24);
        } else {
            return null;
        }
        
        $c = base64_decode($val);
        $ivlen = openssl_cipher_iv_length(self::cipher);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $plaintext = openssl_decrypt($ciphertext_raw, self::cipher, $ENC_KEY, OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $ENC_KEY, true);
        if (hash_equals($hmac, $calcmac))
        {
            return $plaintext;
        } else {
            return null;
        }
    }
}