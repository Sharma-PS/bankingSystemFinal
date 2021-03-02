<?php
namespace Classess\Account;
use Includes\DB\Connection;
use Includes\FD\FD;

class Account extends Connection
{
    private $accID, $NIC, $branch, $balance, $type, $createdDate, $updatedDate;

    /**
     * Set All properties of accounts
     */
    public function __construct($accID = Null, $NIC = Null, $branch = Null, $balance = Null, $type = Null)
    {
        $this->accID = $accID;
        $this->NIC = $NIC;
        $this->branch = $branch;
        $this->balance = $balance;
        $this->type = $type;
    }

    /**
     * create New Account
     */
    public function createNewAccount():string
    {
        if ($this->NIC && $this->branch && $this->balance && $this->type){
            $sql = "SELECT NIC FROM account WHERE NIC = ?";
            $conn = (new Connection)->connect();
            $stmt = $conn->prepare($sql);
            $stmt->execute([$this->NIC]);
            $result = $stmt->fetch();

            if($result){
                return ALREADYHAVE;
            }
            else{
                $qry = "INSERT INTO `account` (`accID`, `NIC`, `branchCode`, `balance`, `type`) VALUES (NULL, ?, ?, ? ,?)";
                $stmt = $conn->prepare($qry);
                if($stmt->execute([$this->NIC, $this->branch, $this->balance, $this->type])){
                    if($this->type == "saving"){
                        $lastID = $conn->lastInsertId();
                        $qery = "CALL `getSavingPlanID`(?, @splanID);";
                        $stm = $conn->prepare($qery);
                        $stm->execute([$this->NIC]);
                        $sql = "SELECT @splanID AS `splan`";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $s_plan_id = $stmt->fetch();
                        $qry2 = "INSERT INTO `saving_account` (`accID`, `s_plan_id`, `no_of_withdrawals`) VALUES (?,?,?)";
                        $stmt2 = $conn->prepare($qry2);
                        $stmt2->execute([$lastID, $s_plan_id["splan"] , 0]);
                    }
                    return SUCCESSADDED;
                }
                return CANNOTCREATE;
            }
        }else{
            return "Details Missing";
        }
        return "Cannot create Account";
    }

    /**
     * return all account details from database
     */
    public function ViewAllAccounts():array
    {
        $sql = "SELECT * FROM accountdetails";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** change status */
    public function changeStatus($Acc_ID, $Acc_status)
    {
        $sql = "UPDATE `account` SET `status` = ? WHERE `account`.`accID` = ?"; 
        $stmt = (new Connection)->connect()->prepare($sql);

        if($stmt->execute([$Acc_status, $Acc_ID])):
            return "Sucessfully Updated.";
        else:
            return "Failed to Update. \n Try Again";
        endif;

    }

    /**
     * Get One account of Customer
     */
    public function getAccount($nic)
    {
        $sql = "SELECT * FROM account WHERE NIC = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$nic]);
        $results = $stmt->fetchAll();
        if($results){
            $accounts = array();
            foreach ($results as $result) {
               $acc = new Account($result["accID"], $nic, $result["branchCode"],$result["balance"],  $result["type"]);
               $acc->setCreatedDate($result["createdDate"]);
               $acc->setUpdatedDate($result["updatedDate"]);
               array_push($accounts, $acc);
            }  
            return $accounts;                                                                                              
        }
        return DONTHAVEA;        
    }

    public function hasFD($nic)
    {
        $sql = "SELECT a.accID,f.amount FROM account a INNER JOIN fd f WHERE a.accID = f.savingAcc_id AND a.NIC = ? AND a.type = ? AND f.maturityDate > NOW() ORDER BY f.amount DESC LIMIT 1";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$nic,"saving"]);
        $result = $stmt->fetch();
        if($result){           
            return new FD(NULL, $result["accID"], $result["amount"]);
        }
        return FDREQUIRED;
    }

    /**
     * Check Enough Money
     */
    public function hasEnoghMoney($amount)
    {
        $sql = "SELECT balance FROM account WHERE accID = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$this->accID]);
        $results = $stmt->fetch();
        if($results["balance"] > $amount){
            return "TRUE";
        }
        return "FALSE";
    }

    /**
     * Acc Ids
     */
    public function getAccIdAsOptions($nic)
    {
        $sql = "SELECT accID FROM account WHERE NIC = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$nic]);
        return $stmt->fetchAll();
    }


    /**
     * Get the value of accID
     */ 
    public function getAccID()
    {
        return $this->accID;
    }

    /**
     * Get the value of NIC
     */ 
    public function getNIC()
    {
        return $this->NIC;
    }

    /**
     * Get the value of branch
     */ 
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Get the value of balance
     */ 
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the value of createdDate
     */ 
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Get the value of updatedDate
     */ 
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set the value of createdDate
     *
     */ 
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;        
    }

    /**
     * Set the value of updatedDate
     */ 
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }
}

?>