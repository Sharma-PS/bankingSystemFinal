<?php
namespace Includes\Loan;
use Includes\DB\Connection;
class Loan extends Connection
{
    private $NIC, $balance, $reason, $duInMon, $planId, $loanId, $requestedDate, $pending, $approved, $installment, $approvedBy,$approvedDate, $nextPayDate, $endDate, $cntPay, $arrear, $status, $updateDate;

    /**
     * Set All properties of accounts
     */
    public function __construct($NIC = NULL, $balance = NULL, $reason = NULL, $duInMon = NULL, $planId = NULL, $loanId = NULL, $requestedDate = NULL, $pending = NULL, $approved = NULL, $installment = NULL, $approvedBy = NULL,$approvedDate = NULL, $nextPayDate = NULL, $endDate = NULL, $cntPay = NULL, $arrear = NULL, $status = NULL, $updateDate = NULL)
    {       
        $this->NIC = $NIC;
        $this->balance = $balance;
        $this->reason = $reason;
        $this->duInMon = $duInMon;
        $this->planId = $planId;
        $this->loanId = $loanId;
        $this->requestedDate = $requestedDate;
        $this->updateDate = $updateDate;
        $this->pending = $pending;
        $this->approved = $approved;
        $this->installment = $installment;
        $this->approvedDate = $approvedBy;
        $this->approvedBy = $approvedDate;
        $this->nextPayDate = $nextPayDate;
        $this->endDate = $endDate;
        $this->cntPay = $cntPay;
        $this->arrear = $arrear;
        $this->status = $status;
    }

    /**
     * Request a loan
     */
    public function requestLoan():string
    {
        if ($this->NIC && $this->balance && $this->reason && $this->duInMon && $this->planId){
            $sql = "SELECT NIC FROM customer WHERE NIC = ?";
            $conn = (new Connection)->connect();
            $stmt = $conn->prepare($sql);
            $stmt->execute([$this->NIC]);
            $result = $stmt->fetch();

            if(!$result){
                return DONTHAVEA;
            }
            else{
                $qry = "INSERT INTO `requestedloan` VALUES (NULL, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, CURRENT_TIMESTAMP, '1', '0')";
                $stmt = $conn->prepare($qry);
                if($stmt->execute([$this->NIC, $this->balance, $this->planId, $this->reason, $this->duInMon])){                    
                    return REQUESTEDSUCCESS;
                }
                return CANNOTCREATE;
            }
        }else{
            return "Details Missing";
        }
        return "Cannot Request Loan";
    }

    /**
     * return all pending  loan details from database
     */
    public function ViewAllPendingLoans():array
    {
        $sql = "SELECT * FROM requestedloan WHERE pending = 1 AND approved = 0 ORDER BY loan_id DESC";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } 
    
    /**
     * return all pending  loan details from database
     */
    public function getPendingLoanDetails()
    {
        $sql = "SELECT * FROM requestedloan WHERE loan_id = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$this->loanId]);
        $result = $stmt->fetch();
        if($result){
            $this->NIC = $result["NIC"];
            $this->balance = $result["Amount"];
            $this->reason = $result["reason"];
            $this->duInMon = $result["Duration_in_months"];
            $this->planId = $result["interestPlanId"];
            $this->requestedDate = $result["requestedDate"];

            $sql2 = "SELECT rate FROM loan_plan WHERE loanPlanId = ?";
            $stmt2 = (new Connection)->connect()->prepare($sql2);
            $stmt2->execute([$this->planId]);
            $result2 = $stmt2->fetch(); 
            $rate = $result2["rate"] + 100;
            
            $this->installment = number_format((float)(($this->balance * $rate) / ($this->duInMon * 100)), 2, '.', '');
        }
        return $this;
    }

    /**
     * return all pending  loan details from database
     */
    public function getApprovedLoanDetails()
    {
        $sql = "SELECT * FROM approvedloandetails WHERE loan_id = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$this->loanId]);
        $result = $stmt->fetch();
        if($result){
            $this->NIC = $result["NIC"];
            $this->balance = $result["Amount"];
            $this->reason = $result["reason"];
            $this->duInMon = $result["Duration_in_months"];
            $this->planId = $result["interestPlanId"];
            $this->requestedDate = $result["requestedDate"];            
            $this->installment = $result["installment_amount"]; 
            $this->approvedDate = $result["approvedDate"];
            $this->approvedBy = $result["approvedBy"];
            $this->nextPayDate = $result["nextPaymentDate"];
            $this->endDate = $result["endDate"];
            $this->cntPay = $result["countPayments"];
            $this->arrear = $result["arrear"];
            $this->status = ($result["status"]) ? "Active" : "Finished";
        }
        return $this;
    }

    /**
     * return all rejected  loan details from database
     */
    public function ViewAllRejectedLoans():array
    {
        $sql = "SELECT * FROM requestedloan WHERE pending = 0 ORDER BY loan_id DESC";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } 
    
    /**
     * return all Approved  loan details from database
     */
    public function ViewAllApprovedLoans():array
    {
        $sql = "SELECT * FROM requestedloan WHERE pending = 1 AND approved = 1 ORDER BY loan_id DESC";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } 

    public function givePermisionToLoan($approvedBy)
    {
        $this->approvedBy = $approvedBy;
        $this->approvedDate = date("Y-m-d h:i:s");  
        $this->nextPayDate =  date('Y-m-d h:i:s', strtotime("+1 months", strtotime($this->approvedDate)));      
        $this->endDate = date('Y-m-d h:i:s', strtotime("+"."$this->duInMon"." months", strtotime($this->approvedDate)));        
        $sql = "INSERT INTO `approvedloan` VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, '0', '0', '1')";
        $stmt = (new Connection)->connect()->prepare($sql);        
        if($stmt->execute([$this->loanId, $this->installment, $this->approvedBy, $this->nextPayDate, $this->endDate])){
            return GIVEPERMISSION;
        }
        return DONTGIVEPERMISSION;
    }

    /**
     * Move to pending loan
     */
    public function moveToPendingLoan()
    {
        $sql = "UPDATE `requestedloan` SET `pending` = '1' WHERE loan_id = ?";
        $stmt = (new Connection)->connect()->prepare($sql);        
        if($stmt->execute([$this->loanId])){
            return MOVETOPEN;
        }
        return DONTGIVEPERMISSION;
    }

    /**
     * Move to Rejected loan
     */
    public function rejectLoan()
    {
        $sql = "UPDATE `requestedloan` SET `pending` = '0' WHERE loan_id = ?";
        $stmt = (new Connection)->connect()->prepare($sql);        
        if($stmt->execute([$this->loanId])){
            return MOVETOREJ;
        }
        return DONTGIVEPERMISSION;
    }

    /**
     * Move to Rejected loan
     */
    public function changeStatus()
    {
        $sql = "UPDATE `approvedloan` SET `status` = '0' WHERE loan_id = ?";
        $stmt = (new Connection)->connect()->prepare($sql);        
        if($stmt->execute([$this->loanId])){
            return FINSIHEDTHELOAN;
        }
        return DONTGIVEPERMISSION;
    }

    /**
     * Get the value of NIC
     */ 
    public function getNIC()
    {
        return $this->NIC;
    }

    /**
     * Get the value of reason
     */ 
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Get the value of duInMon
     */ 
    public function getDuInMon()
    {
        return $this->duInMon;
    }

    /**
     * Get the value of planId
     */ 
    public function getPlanId()
    {
        return $this->planId;
    }

    /**
     * Get the value of loanId
     */ 
    public function getLoanId()
    {
        return $this->loanId;
    }

    /**
     * Get the value of requestedDate
     */ 
    public function getRequestedDate()
    {
        return $this->requestedDate;
    }

    /**
     * Get the value of pending
     */ 
    public function getPending()
    {
        return $this->pending;
    }

    /**
     * Get the value of approved
     */ 
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Get the value of installment
     */ 
    public function getInstallment()
    {
        return $this->installment;
    }

    /**
     * Get the value of approvedBy
     */ 
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * Get the value of approvedDate
     */ 
    public function getApprovedDate()
    {
        return $this->approvedDate;
    }

    /**
     * Get the value of nextPayDate
     */ 
    public function getNextPayDate()
    {
        return $this->nextPayDate;
    }

    /**
     * Get the value of endDate
     */ 
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Get the value of cntPay
     */ 
    public function getCntPay()
    {
        return $this->cntPay;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the value of arrear
     */ 
    public function getArrear()
    {
        return $this->arrear;
    }

    /**
     * Set the value of loanId
     *
     * @return  self
     */ 
    public function setLoanId($loanId)
    {
        $this->loanId = $loanId;

        return $this;
    }

    /**
     * Get the value of updateDate
     */ 
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Get the value of balance
     */ 
    public function getBalance()
    {
        return $this->balance;
    }
}

?>