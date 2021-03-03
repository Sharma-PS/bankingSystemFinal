<?php
namespace Includes\FD;
use Includes\DB\Connection;
class FD extends Connection
{
    private $fdId, $said, $balance, $type, $startDate, $endDate;

    /**
     * Set All properties of accounts
     */
    public function __construct($fdId = Null, $said = Null, $balance = Null, $type = Null, $startDate = NULL, $endDate = NULL)
    {
        $this->fdId = $fdId;
        $this->said = $said;
        $this->balance = $balance;
        $this->type = $type;
        $this->startDate = $startDate;
        $this->endDate = $endDate;

    }

    /**
     * create New FD Account
     */
    public function createNewFD():string
    {
        if ($this->said && $this->balance && $this->endDate && $this->type){
            $sql = "SELECT accID FROM saving_account WHERE accID = ?";
            $conn = (new Connection)->connect();
            $stmt = $conn->prepare($sql);
            $stmt->execute([$this->said]);
            $result = $stmt->fetch();

            if(!$result){
                return DONTHAVESA;
            }
            else{
                $qry = "INSERT INTO `fd` VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP, ?, '0')";
                $stmt = $conn->prepare($qry);
                if($stmt->execute([$this->said, $this->type, $this->balance, $this->endDate])){                    
                    return SUCCESSADDED;
                }
                return CANNOTCREATE;
            }
        }else{
            return "Details Missing";
        }
        return "Cannot create FD";
    }

    /**
     * return all account details from database
     */
    public function ViewAllFDs():array
    {
        $sql = "SELECT * FROM fd";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

        /**
     * return all account details from database
     */
    public function ViewAllFDsCustomer($nic):array
    {
        $sql = "SELECT f.`FD_ID`, f.`savingAcc_id`,f.`FD_plan_id`,f.`amount`,f.`startDate`,f.`maturityDate`,f.`withdrewOrNot`, a.NIC FROM fd f INNER JOIN account a WHERE f.savingAcc_id = a.accID AND NIC = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$nic]);
        return $stmt->fetchAll();
    }

    /**
     * Get the value of endDate
     */ 
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Get the value of startDate
     */ 
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the value of balance
     */ 
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Get the value of said
     */ 
    public function getSaid()
    {
        return $this->said;
    }

    /**
     * Get the value of fdId
     */ 
    public function getFdId()
    {
        return $this->fdId;
    }
}

?>