<?php

function secEnv($name, $fallback = '') {
    try {
        $crypt = new Illuminate\Encryption\Encrypter('EXP4fKQTsXj3');
        if (!isset($secEnv[$name]) && env($name) && strpos(env($name), "ENC:") === 0) {
            $secEnv[$name] = $crypt->decrypt(substr(getenv($name), 4));
        }
        return $secEnv[$name];
    }catch (Exception $e){
        $e->getMessage();
    }
}
