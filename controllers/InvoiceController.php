<?php

require_once BASE_PATH . '/services/AuthService.php';
require_once BASE_PATH . '/models/Invoice.php';
require_once BASE_PATH . '/models/Patient.php';

class InvoiceController {
    
    private $invoiceModel;
    private $patientModel;
    
    public function __construct() {
        // Can be accessed by both admin and practitioner, but let's restrict to authenticated users
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }
        $this->invoiceModel = new Invoice();
        $this->patientModel = new Patient();
    }

    public function index() {
        $invoices = $this->invoiceModel->getAllInvoices();
        $current_page = 'practitioner_invoices';
        require_once VIEWS_PATH . '/practitioner/invoices.php';
    }

    public function view($invoiceId) {
        $invoice = $this->invoiceModel->getInvoiceById($invoiceId);
        if (!$invoice) {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
            exit;
        }
        $patient = $this->patientModel->findById($invoice['patient_id']);
        
        header('Content-Type: application/json');
        echo json_encode([
            'invoice' => $invoice,
            'patient' => $patient
        ]);
        exit;
    }
}
