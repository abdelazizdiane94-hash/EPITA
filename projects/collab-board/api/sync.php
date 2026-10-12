<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
require_once __DIR__ . '/JwtAuth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

try {
    if (!(new BoardJwtAuth())->isAuthorized($_SERVER['HTTP_AUTHORIZATION'] ?? '')) {
        http_response_code(401);
        echo json_encode(['error' => 'Valid bearer token required.']);
        exit;
    }

    require __DIR__ . '/config/db.php';
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!is_array($payload)) {
        http_response_code(422);
        echo json_encode(['error' => 'A valid JSON object is required.'], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }
    $sprintId = filter_var($payload['sprint_id'] ?? null, FILTER_VALIDATE_INT);
    $cards = $payload['cards'] ?? null;
    if (!$sprintId || $sprintId < 1 || !is_array($cards) || count($cards) > 100) {
        http_response_code(422);
        echo json_encode(['error' => 'A positive sprint id and up to 100 cards are required.'], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }

    foreach ($cards as $card) {
        if (!is_array($card) || !isset($card['title'], $card['status']) || !is_string($card['title']) || trim($card['title']) === '' || strlen($card['title']) > 255 || !in_array($card['status'], ['start', 'stop', 'continue'], true) || !is_int($card['position'] ?? null) || $card['position'] < 0 || !is_string($card['description'] ?? '') || strlen($card['description'] ?? '') > 10000) {
            http_response_code(422);
            echo json_encode(['error' => 'One or more cards are invalid.'], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
            exit;
        }
    }

    $pdo->beginTransaction();
    try {
        $delete = $pdo->prepare('DELETE FROM cards WHERE sprint_id = :sprint_id');
        $delete->execute([':sprint_id' => $sprintId]);
        $insert = $pdo->prepare('INSERT INTO cards (sprint_id, title, description, status, position) VALUES (:sprint_id, :title, :description, :status, :position)');
        foreach ($cards as $card) {
            $insert->execute([
                ':sprint_id' => $sprintId,
                ':title' => trim($card['title']),
                ':description' => trim((string) ($card['description'] ?? '')),
                ':status' => $card['status'],
                ':position' => $card['position']
            ]);
        }
        $pdo->commit();
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $exception;
    }
    echo json_encode(['status' => 'success', 'cards_saved' => count($cards)], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $exception) {
    error_log('CollabBoard sync request failed: ' . $exception->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'The board could not be synchronized.'], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
}