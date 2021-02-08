<?php
namespace Includes\Transaction;
use Includes\DB\Connection;
require "../messages.php";

class Withdraw extends Connection
{
    /**
     * var for return all Withdraw 
     */
    private $allWithdraws;
    private $accID, $amount, $description, $branchCode, $withdredBy;
    public function __construct($accID = null, $amount = null, $description = null, $branchCode = null, $withdredBy = null)
    {
        $this->accID = $accID;
        $this->amount = $amount;
        $this->description = $description;
        $this->branchCode = $branchCode;
        $this->withdredBy = $withdredBy;
    }

    /**
     * return All Withdraw plans
     */
    public function getAllWithdraws():array
    {
        return $this->allWithdraws;
    }
    
    /**
     * make Withdrawal to account
     */
    public function makeWithDraw():string
    {   
        $conn = (new Connection);
        $sql = "SELECT balance,type,status,no_of_withdrawals FROM accountdetails WHERE accID = ?";
        $stmt = $conn->connect()->prepare($sql);
        $stmt->execute([$this->accID]);
        $found = $stmt->fetch();
        if($found){ //check account found or not
            if($found["status"]){ //check account active or not
                if($found["balance"] > $this->amount){                    
                    if($found["type"] == "current"){
                        $sql2 = "INSERT INTO `withdrawal` (`withdrawal_id`, `accID`, `amount`, `Description`, `branchCode`, `withdrew_by`, `time`) VALUES (NULL, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP);";
                        $stmt2 = $conn->connect()->prepare($sql2);
                        $stmt2->execute([$this->accID, $this->amount, $this->description, $this->branchCode, $this->withdredBy]);                        
                    }                    
                    else{
                        if($found["no_of_withdrawals"] < 5){
                            $sql2 = "INSERT INTO `withdrawal` (`withdrawal_id`, `accID`, `amount`, `Description`, `branchCode`, `withdrew_by`, `time`) VALUES (NULL, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP);";
                            $stmt2 = $conn->connect()->prepare($sql2);
                            $stmt2->execute([$this->accID, $this->amount, $this->description, $this->branchCode, $this->withdredBy]);                        
                            $sql3 = "UPDATE `saving_account` SET `no_of_withdrawals` = no_of_withdrawals + 1 WHERE accID = ?";
                            $stmt3 = $conn->connect()->prepare($sql3);
                            $stmt3->execute([$this->accID]);   
                            return SUCCESSWITHDRAW;   
                        }
                        return REACHEDMAXWITHDRAWAL;
                    }
                    return SUCCESSWITHDRAW;                    
                }else{
                    return NOTENOUGHMONEY;
                }              
            }
            else{ return ACCOUNTCLOSED; }            
        }
        return ACCOUNTNOTFOUND;
        
    }
}


?>