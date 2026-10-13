<?php

require_once BASE_PATH . '/config/database.php';

class Invoice {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getInvoiceById($invoiceId) {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE invoice_id = ?");
        $stmt->execute([$invoiceId]);
        $invoice = $stmt->fetch();
        if ($invoice) {
            $invoice['items'] = $this->getInvoiceItems($invoiceId);
        }
        return $invoice;
    }

    public function getInvoicesByPatient($patientId) {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE patient_id = ? ORDER BY invoice_date DESC, created_at DESC");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getAllInvoices() {
        // Includes patient full name for list views
        $stmt = $this->db->query("
            SELECT i.*, p.full_name as patient_name 
            FROM invoices i 
            JOIN patients p ON i.patient_id = p.patient_id 
            ORDER BY i.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getInvoiceItems($invoiceId) {
        $stmt = $this->db->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll();
    }

    public function createInvoice($data, $items = []) {
        $stmt = $this->db->prepare("
            INSERT INTO invoices (
                invoice_number, patient_id, department_id, treatment_title, amount, 
                status, invoice_date, due_date, subtotal, tax_amount, payment_method, notes
            ) VALUES (
                :invoice_number, :patient_id, :department_id, :treatment_title, :amount,
                :status, :invoice_date, :due_date, :subtotal, :tax_amount, :payment_method, :notes
            )
        ");
        
        $stmt->execute([
            'invoice_number' => $data['invoice_number'] ?? 'INV-' . time(),
            'patient_id' => $data['patient_id'],
            'department_id' => $data['department_id'] ?? 1,
            'treatment_title' => $data['treatment_title'] ?? 'Dental Services',
            'amount' => $data['amount'] ?? 0.00,
            'status' => $data['status'] ?? 'Pending',
            'invoice_date' => $data['invoice_date'] ?? date('Y-m-d'),
            'due_date' => $data['due_date'] ?? null,
            'subtotal' => $data['subtotal'] ?? 0.00,
            'tax_amount' => $data['tax_amount'] ?? 0.00,
            'payment_method' => $data['payment_method'] ?? null,
            'notes' => $data['notes'] ?? null
        ]);

        $invoiceId = $this->db->lastInsertId();

        foreach ($items as $item) {
            $this->addInvoiceItem($invoiceId, $item);
        }

        return $invoiceId;
    }

    public function addInvoiceItem($invoiceId, $item) {
        $stmt = $this->db->prepare("
            INSERT INTO invoice_items (invoice_id, treatment_name, quantity, price, total) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $invoiceId,
            $item['treatment_name'],
            $item['quantity'] ?? 1,
            $item['price'],
            $item['total']
        ]);
    }
}
