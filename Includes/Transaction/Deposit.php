<?php
namespace Includes\Transaction;
use Includes\DB\Connection;
require "../messages.php";

class Deposit extends Connection
{
    /**
     * var for return all deposits 
     */
    private $allDeposits;
    private $accID, $amount, $description, $branchCode, $depositBy;
    public function __construct($accID = null, $amount = null, $description = null, $branchCode = null, $depositBy = null)
    {
        $this->accID = $accID;
        $this->amount = $amount;
        $this->description = $description;
        $this->branchCode = $branchCode;
        $this->depositBy = $depositBy;
    }

    /**
     * return All Deposits plans
     */
    public function getAllDeposits():array
    {
        return $this->allDeposits;
    }
    
    /**
     * make deposit to account
     */
    public function makeDeposit():string
    {
        $sql = "SELECT NIC FROM account WHERE accID = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$this->accID]);
        $found = $stmt->fetch();
        if($found){
            $sql2 = "INSERT INTO `deposit` (`deposit_id`, `accID`, `amount`, `Description`, `branchCode`, `deposit_by`, `time`) VALUES (NULL, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP);";
            $stmt = (new Connection)->connect()->prepare($sql2);
            $stmt->execute([$this->accID, $this->amount, $this->description, $this->branchCode, $this->depositBy]);
            return SUCCESSDEPOSIT;
        }
        return ACCOUNTNOTFOUND;
        
    }
}


?>