<?php
// index.php - Main entry point for the ChronosDesk application

// Autoload classes
spl_autoload_register(function ($class_name) {
    include '../app/Models/' . $class_name . '.php';
});

// Start session
session_start();

// Include controllers
require_once '../app/Controllers/AuthController.php';
require_once '../app/Controllers/TicketController.php';

// Initialize controllers
$authController = new AuthController();
$ticketController = new TicketController();

// Routing logic
$requestUri = $_SERVER['REQUEST_URI'];

if (strpos($requestUri, '/login') !== false) {
    $authController->login();
} elseif (strpos($requestUri, '/logout') !== false) {
    $authController->logout();
} elseif (strpos($requestUri, '/tickets') !== false) {
    $ticketController->index();
} elseif (strpos($requestUri, '/ticket/') !== false) {
    $ticketController->show(substr($requestUri, strrpos($requestUri, '/') + 1));
} else {
    // Default to dashboard
    $ticketController->index();
}
?>