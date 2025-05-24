<?php

Class Payment {
    public ?int $paymentID;
    public int $requestID;
    public int $amount;
    public string $status;
    public string $paymentMethod;
    public ?string $transactionDate;

    public function __construct(?int $paymentID, int $requestID, int $amount, string $status, string $paymentMethod, ?string $transactionDate) {
        $this->paymentID = $paymentID;
        $this->requestID = $requestID;
        $this->amount = $amount;
        $this->status = $status;
        $this->paymentMethod = $paymentMethod;
        $this->transactionDate = $transactionDate;
    }


    public static function getPaymentByRequestID($db, $requestID) {
        $stmt = $db->prepare('SELECT * FROM Payment where requestID = ?');
        $stmt->execute(array($requestID));

        $payment = $stmt->fetch();

        if ($payment == NULL) return NULL;

        return new Payment(
            $payment['paymentID'],
            $payment['requestID'],
            $payment['amount'],
            $payment['status'],
            $payment['paymentMethod'],
            $payment['transactionDate']
        );
    }


    public function save($db) {
        if (!empty($this->paymentID)) $this->updateDatabase($db);
        else $this->insertIntoDatabase($db);
        return $this->paymentID;
    }


    public function updateDatabase($db) {
        $stmt = $db->prepare('UPDATE Payment 
                            SET status = ?, transactionDate = ?
                            WHERE paymentID = ?');

        $stmt->execute(array($this->status, $this->transactionDate, $this->paymentID));
    }


    public function insertIntoDatabase($db) {
        $stmt = $db->prepare('INSERT INTO Payment (paymentID, requestID, amount, status, paymentMethod, transactionDate) VALUES 
        (?, ?, ?, ?, ?, ?)');

        $stmt->execute(array(null, $this->requestID, $this->amount, 'pending', $this->paymentMethod, null));
        $this->paymentID = intval($db->lastInsertId());
    }


}











//CREATE TABLE Payment (
//	paymentID INTEGER  PRIMARY KEY,
//	requestID INTEGER NOT NULL,
//	amount DECIMAL(10, 2),
//	status TEXT CHECK (status IN ('pending', 'completed')),
//	paymentMethod TEXT CHECK (paymentMethod IN ('visa', 'rupay', 'mastercard', 'paypal', 'mock')) NOT NULL,
//	transactionDate DATE,
//
//	FOREIGN KEY (requestID) REFERENCES Request(requestID)
//		ON DELETE CASCADE
//		ON UPDATE CASCADE
//);


?>