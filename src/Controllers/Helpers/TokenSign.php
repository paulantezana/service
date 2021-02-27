<?php


$apiPrivateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/Rn
vuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL9
5+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQAB
AoGAb/MXV46XxCFRxNuB8LyAtmLDgi/xRnTAlMHjSACddwkyKem8//8eZtw9fzxz
bWZ/1/doQOuHBGYZU8aDzzj59FZ78dyzNFoF91hbvZKkg+6wGyd/LrGVEB+Xre0J
Nil0GReM2AHDNZUYRv+HYJPIOrB0CRczLQsgFJ8K6aAD6F0CQQDzbpjYdx10qgK1
cP59UHiHjPZYC0loEsk7s+hUmT3QHerAQJMZWC11Qrn2N+ybwwNblDKv+s5qgMQ5
5tNoQ9IfAkEAxkyffU6ythpg/H0Ixe1I2rd0GbF05biIzO/i77Det3n4YsJVlDck
ZkcvY3SK2iRIL4c9yY6hlIhs+K9wXTtGWwJBAO9Dskl48mO7woPR9uD22jDpNSwe
k90OMepTjzSvlhjbfuPN1IdhqvSJTDychRwn1kIJ7LQZgQ8fVz9OCFZ/6qMCQGOb
qaGwHmUK6xzpUbbacnYrIM6nLSkXgOAwv7XXCojvY614ILTK3iXiLBOxPu5Eu13k
eUz9sHyD6vkgZzjtxXECQAkp4Xerf5TGfQXGXhxIX52yH+N2LtujCdkQZjXAsGdm
B2zNzvrlgRmgBrklMTrMYgm1NPcW+bRLGcwgW2PTvNM=
-----END RSA PRIVATE KEY-----
EOD;

$apiPublicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC8kGa1pSjbSYZVebtTRBLxBz5H
4i2p/llLCrEeQhta5kaQu/RnvuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t
0tyazyZ8JXw+KgXTxldMPEL95+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4
ehde/zUxo6UvS7UrBQIDAQAB
-----END PUBLIC KEY-----
EOD;

class TokenSign
{
private static $apiPrivateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/Rn
vuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL9
5+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQAB
AoGAb/MXV46XxCFRxNuB8LyAtmLDgi/xRnTAlMHjSACddwkyKem8//8eZtw9fzxz
bWZ/1/doQOuHBGYZU8aDzzj59FZ78dyzNFoF91hbvZKkg+6wGyd/LrGVEB+Xre0J
Nil0GReM2AHDNZUYRv+HYJPIOrB0CRczLQsgFJ8K6aAD6F0CQQDzbpjYdx10qgK1
cP59UHiHjPZYC0loEsk7s+hUmT3QHerAQJMZWC11Qrn2N+ybwwNblDKv+s5qgMQ5
5tNoQ9IfAkEAxkyffU6ythpg/H0Ixe1I2rd0GbF05biIzO/i77Det3n4YsJVlDck
ZkcvY3SK2iRIL4c9yY6hlIhs+K9wXTtGWwJBAO9Dskl48mO7woPR9uD22jDpNSwe
k90OMepTjzSvlhjbfuPN1IdhqvSJTDychRwn1kIJ7LQZgQ8fVz9OCFZ/6qMCQGOb
qaGwHmUK6xzpUbbacnYrIM6nLSkXgOAwv7XXCojvY614ILTK3iXiLBOxPu5Eu13k
eUz9sHyD6vkgZzjtxXECQAkp4Xerf5TGfQXGXhxIX52yH+N2LtujCdkQZjXAsGdm
B2zNzvrlgRmgBrklMTrMYgm1NPcW+bRLGcwgW2PTvNM=
-----END RSA PRIVATE KEY-----
EOD;

private static $apiPublicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC8kGa1pSjbSYZVebtTRBLxBz5H
4i2p/llLCrEeQhta5kaQu/RnvuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t
0tyazyZ8JXw+KgXTxldMPEL95+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4
ehde/zUxo6UvS7UrBQIDAQAB
-----END PUBLIC KEY-----
EOD;

    private static function urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
    private static function urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    private static function sign($msg, $key, $alg = 'SHA256')
    {
        if ($alg != 'SHA256') {
            throw new DomainException('Algorithm not supported');
        }
        $signature = '';
        $success = openssl_sign($msg, $signature, $key, $alg);
        if (!$success) {
            throw new DomainException("OpenSSL unable to sign data");
        } else {
            return $signature;
        }
    }

    private static function verify($msg, $signature, $key, $alg){
        if ($alg != 'SHA256') {
            throw new DomainException('Algorithm not supported');
        }

        $success = openssl_verify($msg, $signature, $key, $alg);
        if ($success === 1) {
            return true;
        } elseif ($success === 0) {
            return false;
        }

        // returns 1 on success, 0 on failure, -1 on error.
        throw new DomainException(
            'OpenSSL error: ' . openssl_error_string()
        );
    }

    public static function encode($payload, $alg = 'SHA256')
    {
        $segments = array();
        $segments[] = static::urlsafeB64Encode(json_encode($payload));
        $signing_input = implode('.', $segments);

        $signature = static::sign($signing_input, static::$apiPrivateKey, $alg);
        $segments[] = static::urlsafeB64Encode($signature);

        return implode('.', $segments);
    }

    public static function decode($data)
    {
//        $timestamp = is_null(static::$timestamp) ? time() : static::$timestamp;

//        if (empty($key)) {
//            throw new InvalidArgumentException('Key may not be empty');
//        }
        $tks = explode('.', $data);
        if (count($tks) != 2) {
            throw new UnexpectedValueException('Wrong number of segments');
        }
        list($bodyb64, $cryptob64) = $tks;
        if (null === $payload = json_decode(static::urlsafeB64Decode($bodyb64), true)) {
            throw new UnexpectedValueException('Invalid claims encoding');
        }

        if (false === ($sig = static::urlsafeB64Decode($cryptob64))) {
            throw new UnexpectedValueException('Invalid signature encoding');
        }

        // Check the signature
        if (!static::verify($bodyb64, $sig, static::$apiPublicKey, 'SHA256')) {
            throw new Exception('Signature verification failed');
        }

        // Check if the nbf if it is defined. This is the time that the
        // token can actually be used. If it's not yet that time, abort.
//        if (isset($payload->nbf) && $payload->nbf > ($timestamp + static::$leeway)) {
//            throw new BeforeValidException(
//                'Cannot handle token prior to ' . date(DateTime::ISO8601, $payload->nbf)
//            );
//        }
//
//        // Check that this token has been created before 'now'. This prevents
//        // using tokens that have been created for later use (and haven't
//        // correctly used the nbf claim).
//        if (isset($payload->iat) && $payload->iat > ($timestamp + static::$leeway)) {
//            throw new BeforeValidException(
//                'Cannot handle token prior to ' . date(DateTime::ISO8601, $payload->iat)
//            );
//        }
//
//        // Check if this token has expired.
//        if (isset($payload->exp) && ($timestamp - static::$leeway) >= $payload->exp) {
//            throw new ExpiredException('Expired token');
//        }

        return $payload;
    }
}
