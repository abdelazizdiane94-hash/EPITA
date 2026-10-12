<?php
class TicketController {
    private $ticketModel;

    public function __construct(PDO $database) {
        $this->ticketModel = new Ticket($database);
    }

    public function index() {
        $this->requireUser();
        $tickets = $this->ticketModel->getAll();
        $user = $_SESSION['user'];
        require __DIR__ . '/../Views/dashboard.php';
    }

    public function show($id) {
        $this->requireUser();
        $ticket = $this->ticketModel->getById((int) $id);
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket introuvable.';
            return;
        }
        require __DIR__ . '/../Views/ticket_detail.php';
    }

    public function create() {
        $this->requireUser();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Allow: POST');
            http_response_code(405);
            echo 'Méthode non autorisée.';
            return;
        }
        if (!$this->validCsrf()) {
            http_response_code(403);
            echo 'Le formulaire a expiré. Actualisez la page puis réessayez.';
            return;
        }
        if (!$this->validateTicketData($_POST)) {
            http_response_code(422);
            echo 'Vérifiez que le sujet et la description sont renseignés et respectent les longueurs autorisées.';
            return;
        }
        $this->ticketModel->create($_POST, (int) $_SESSION['user']['id']);
        header('Location: ?route=dashboard');
        exit;
    }

    public function update($id) {
        $this->requireUser();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Allow: POST');
            http_response_code(405);
            echo 'Méthode non autorisée.';
            return;
        }
        $role = $_SESSION['user']['role'];
        $status = (string) ($_POST['status'] ?? '');
        if (!$this->validCsrf()) {
            http_response_code(403);
            echo 'Le formulaire a expiré. Actualisez la page puis réessayez.';
            return;
        }
        if (!in_array($role, ['Technicien', 'Admin'], true)) {
            http_response_code(403);
            echo 'Vous n’avez pas les droits nécessaires pour modifier ce ticket.';
            return;
        }
        if (!in_array($status, ['open', 'in_progress', 'closed'], true)) {
            http_response_code(422);
            echo 'Le statut sélectionné est invalide.';
            return;
        }
        $ticket = $this->ticketModel->getById((int) $id);
        if (!$ticket) {
            http_response_code(404);
            echo 'Ticket introuvable.';
            return;
        }
        $this->ticketModel->update((int) $id, ['title' => $ticket['title'], 'description' => $ticket['description'], 'status' => $status]);
        header('Location: ?route=dashboard');
        exit;
    }

    private function requireUser() {
        if (empty($_SESSION['user']['id'])) {
            header('Location: ?route=login');
            exit;
        }
    }

    private function validCsrf() {
        return isset($_SESSION['csrf_token'], $_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string) $_POST['csrf_token']);
    }

    private function validateTicketData($data) {
        $title = trim((string) ($data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        return $title !== '' && strlen($title) <= 255 && $description !== '' && strlen($description) <= 10000;
    }
}