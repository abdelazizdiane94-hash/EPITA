<?php
class JwtAuth {
    private function secret() {
        $secret = getenv('STOCKPULSE_JWT_SECRET');
        if (!$secret || strlen($secret) < 32) {
            throw new RuntimeException('STOCKPULSE_JWT_SECRET must contain at least 32 characters.');
        }
        return $secret;
    }

    public function generateToken(int $userId) {
        $header = $this->base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload = $this->base64UrlEncode(json_encode(['sub' => $userId, 'iat' => time(), 'exp' => time() + 3600, 'iss' => 'stock-pulse']));
        $content = $header . '.' . $payload;
        return $content . '.' . $this->base64UrlEncode(hash_hmac('sha256', $content, $this->secret(), true));
    }

    public function validateToken($token) {
        $parts = explode('.', (string) $token);
        if (count($parts) !== 3) return false;
        [$headerPart, $payloadPart, $signaturePart] = $parts;
        $header = json_decode($this->base64UrlDecode($headerPart), true);
        $payload = json_decode($this->base64UrlDecode($payloadPart), true);
        if (!is_array($header) || ($header['alg'] ?? null) !== 'HS256' || !is_array($payload)) return false;
        if (!isset($payload['sub'], $payload['exp'], $payload['iss']) || !is_numeric($payload['sub']) || $payload['exp'] <= time() || $payload['iss'] !== 'stock-pulse') return false;
        $expected = $this->base64UrlEncode(hash_hmac('sha256', $headerPart . '.' . $payloadPart, $this->secret(), true));
        return hash_equals($expected, $signaturePart);
    }

    public function authenticatedUserId() {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/^Bearer\s+(.+)$/i', $header, $matches) || !$this->validateToken($matches[1])) return false;
        $payload = json_decode($this->base64UrlDecode(explode('.', $matches[1])[1]), true);
        return (int) $payload['sub'];
    }

    private function base64UrlEncode($value) {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode($value) {
        $decoded = base64_decode(strtr($value, '-_', '+/') . str_repeat('=', (4 - strlen($value) % 4) % 4), true);
        return $decoded === false ? '' : $decoded;
    }
}