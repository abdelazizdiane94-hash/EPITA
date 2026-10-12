<?php
class BoardJwtAuth {
    public function isAuthorized($authorization) {
        if (!preg_match('/^Bearer\s+([A-Za-z0-9_.-]+)$/i', $authorization, $matches)) return false;
        $secret = getenv('COLLABBOARD_JWT_SECRET');
        if (!$secret || strlen($secret) < 32) return false;
        $parts = explode('.', $matches[1]);
        if (count($parts) !== 3) return false;
        [$headerPart, $payloadPart, $signaturePart] = $parts;
        $header = json_decode($this->decode($headerPart), true);
        $payload = json_decode($this->decode($payloadPart), true);
        if (!is_array($header) || ($header['alg'] ?? null) !== 'HS256' || !is_array($payload)) return false;
        if (!isset($payload['exp'], $payload['sub'], $payload['iss']) || !is_numeric($payload['exp']) || !is_numeric($payload['sub']) || (int) $payload['sub'] < 1 || (int) $payload['exp'] <= time() || $payload['iss'] !== 'collab-board') return false;
        $expected = rtrim(strtr(base64_encode(hash_hmac('sha256', $headerPart . '.' . $payloadPart, $secret, true)), '+/', '-_'), '=');
        return hash_equals($expected, $signaturePart);
    }

    private function decode($value) {
        $decoded = base64_decode(strtr($value, '-_', '+/') . str_repeat('=', (4 - strlen($value) % 4) % 4), true);
        return $decoded === false ? '' : $decoded;
    }
}