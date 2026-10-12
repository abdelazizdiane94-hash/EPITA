<?php
// JwtAuth.php

class JwtAuth {
    private $secretKey = 'your_secret_key'; // Change this to a secure key
    private $issuer = 'http://yourdomain.com'; // Change this to your domain
    private $audience = 'http://yourdomain.com'; // Change this to your domain
    private $expirationTime = 3600; // Token expiration time in seconds

    // Generate JWT token
    public function generateToken($userId) {
        $issuedAt = time();
        $expirationTime = $issuedAt + $this->expirationTime; // jwt valid for 1 hour
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'data' => [
                'userId' => $userId
            ]
        ];

        return $this->encode($payload);
    }

    // Encode the payload to JWT
    private function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    // Validate the JWT token
    public function validateToken($token) {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) !== 3) {
            return false; // Invalid token
        }

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $tokenParts;
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payloadEncoded)), true);

        // Check if the token is expired
        if ($payload['exp'] < time()) {
            return false; // Token expired
        }

        // Validate the signature
        $expectedSignature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, $this->secretKey, true);
        $expectedSignatureEncoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));

        return $signatureEncoded === $expectedSignatureEncoded;
    }
}
?>