<?php
namespace Classess\Account;
use Includes\DB\Connection;
class Account extends Connection
{
    private $accID, $NIC, $branch, $balance, $type;

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
}

?>