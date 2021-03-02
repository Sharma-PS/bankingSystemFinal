<?php
namespace Includes\Transaction;
use Includes\DB\Connection;

class TransferMoney extends Connection
{
    /**
     * var for return all deposits 
     */
    private $allTransfer;
    private $FaccID, $TaccID, $amount, $description;
    public function __construct($FaccID = null, $TaccID = null, $amount = null, $description = null)
    {
        $this->FaccID = $FaccID;
        $this->TaccID = $TaccID;
        $this->amount = $amount;
        $this->description = $description;
    }

    /**
     * return All Transfer plans
     */
    public function getAllTransfers():array
    {
        $sql = "SELECT * FROM transaction ORDER BY transaction_id  DESC";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }
    
    /**
     * make Transfer Between accounts
     */
    public function makeTransfer():string
    {
        $sql = "SELECT NIC,balance FROM account WHERE accID = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$this->FaccID]);
        $found = $stmt->fetch();
        if($found){
            $blnc = $found["balance"];
            if($blnc > $this->amount){
                $sql = "SELECT NIC FROM account WHERE accID = ?";
                $stmt = (new Connection)->connect()->prepare($sql);
                $stmt->execute([$this->TaccID]);
                $found = $stmt->fetch();
                if($found){
                    $sql2 = "INSERT INTO `transaction` (`transaction_id`, `sender_id`, `recipient_id`, `amount`, `description`, `time`) VALUES (NULL, ?, ?, ?, ?, CURRENT_TIMESTAMP);";
                    $stmt = (new Connection)->connect()->prepare($sql2);
                    $stmt->execute([$this->FaccID, $this->TaccID, $this->amount, $this->description]);
                    return SUCCESSTRANSFER;
                }
                return RECEIVERACCOUNTNOTFOUND; 
            }            
            return NOTENOUGHMONEY;                                              
        }
        return SENDERACCOUNTNOTFOUND;        
    }
}


?>