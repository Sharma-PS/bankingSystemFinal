<?php
namespace Classess\Auth;

use Classess\Account\Account;
use Includes\DB\Connection;
use Includes\Installment\Installment;
use Includes\Loan\Loan;
use Includes\Plans\LoanPlan;
use Includes\Transaction\TransferMoney;
use Includes\Transaction\Withdraw;

class Customer extends User
{
    private $tempAddress, $officialAddress, $job, $openedBy;

    // constructor
    public function __construct($email, $nic, $fname, $mobileNo, $branchCode, $DOB, $tempAddress, $currentAddress, $job, $officialAddress, $openedBy, $dp, $joinedDate)
    {
        parent::__construct($email, $nic, $fname, $mobileNo, $branchCode, $DOB, $currentAddress, $dp, $joinedDate);
        $this->tempAddress = $tempAddress;
        $this->officialAddress = $officialAddress;
        $this->job = $job;
        $this->openedBy = $openedBy;
    }

    public static function login($userName, $password): string{
        /**
         * All staff members login to system
         */
            if ($userName && $password){
                $sql = "SELECT * FROM customer WHERE eMail = ?";
                $stmt = (new Connection)->connect()->prepare($sql);
                $stmt->execute([$userName]);
                $result = $stmt->fetchAll();
    
                if(!$result){
                    return "Your user name or password is wrong";
                }
                else{
                    $resultRow = $result[0];
                    if($password == $resultRow['password']){
                        $resultRow['dp'] = ($resultRow['dp']) ? $resultRow['dp'] : 'img/profile/customerAvatar.png' ;
                        $_SESSION['core_bank_user'] = serialize(new Customer($resultRow['eMail'], $resultRow['NIC'], $resultRow['name'], $resultRow['mobileNo'], $resultRow['openedBranch'], $resultRow['DOB'], $resultRow['tempAddress'], $resultRow['permanantAddress'], $resultRow['job'], $resultRow['officialAddress'], $resultRow['openedBy'],$resultRow['dp'],$resultRow['joinedDate']));                        
                        header("location:home/");
                    }
                    else{
                        return "Your user name or password is wrong";
                    }
                }
            }
            return "Warning: must give username and password";
        }

    /**
     * Register customer
     */
    public function register($password){
        $filenm=$this->getDp();
        if ($filenm != NULL) {
            $upload_dir = "../img/profile/";
            $uploaded_file = $upload_dir.$filenm; 
            move_uploaded_file($_FILES['dp']['tmp_name'],$uploaded_file);
        }
        
        $sql = "INSERT INTO `customer` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL)";
        $stmt = (new Connection)->connect()->prepare($sql);
        $result=$stmt->execute([$this->getNIC(), $this->getFname(), $this->getMail(), $password, $this->getmobileNo(), $this->getTempAddress(), $this->getAddress(), $this->getJob(), $this->getOfficialAddress(), $this->getDOB(),$filenm, $this->getOpenedBy(), $this->getBrachCode()]);
        if ($result){
            return true;
        }
        else{
            return false;
        }
    }

    public function getAccount(){
       
        return (new Account())->getAccount($this->getNIC());
    }

    public function hasFD(){
       
        return (new Account())->hasFD($this->getNIC());
    }

    /**
     * Withdraw Money from Customer
     */
    public function TransferMoney($FaccID, $TaccID, $amount, $description):string
    {      
        $transfer = new TransferMoney($FaccID, $TaccID, $amount, $description);
        return $transfer->makeTransfer();
    }

    /**
     * Acc IDs As options
     */
    public function getAccIdAsOptions()
    {
        $accIds = (new Account())->getAccIdAsOptions($this->getNIC());
        $tblQuery = "";
        foreach ($accIds as $value) {            
            $tblQuery = $tblQuery . 
            "<option value='".$value["accID"]."'>".$value["accID"]."</option>";
        }
        return $tblQuery;
    }

    /**
     * get All Loan plans as Table
     */
    public function getAllLoanPlans():string
    {        
        $loans = (new LoanPlan())->getAllLoanPlans();
        $tblQuery = "";
        foreach ($loans as $key => $value) {            
            $tblQuery = $tblQuery . 
            "<tr>
                <td>".(++$key)."</td>
                <td><b>".$value['loanPlanId']."</b></td>
                <td>".$value["description"]."</td>                
                <td><span class='pie'>".$value['rate']."/100</span>".$value['rate']." %</td>            
                <td>".$value['maximumAmount']."</td>
                <td>".$value['max_loan_in_SA']." %</td>
            </tr>";
        }
        return $tblQuery;
    }

    /**
     * Request A loan
     */
    public function requestLoan($NIC, $balance, $reason, $duInMon, $planId, $FDamount)
    {
        $newLoan = new Loan($NIC, $balance, $reason, $duInMon, $planId);
        return $newLoan->requestLoanOnline($FDamount);
    }

    /**
     * Get All My Loans
     */
    public function myLoans()
    {
        $loans = (new Loan())->myLoans($this->getNIC());
        $tblQuery = "";
        foreach ($loans as $loan) {        
            if($loan["status"] == "0"){
                $status = "<button type='button' class='btn btn-custon-rounded-three btn-warning'>Finished</button>";
            }elseif ($loan["approved"] == "1") {
                $status = "<button type='button' class='btn btn-custon-rounded-three btn-success'>Approved</button>";
            }
            elseif ($loan["pending"] == 1) {
                $status = "<button type='button' class='btn btn-custon-rounded-three btn-primary'>Pending</button>";
            }elseif ($loan["pending"] == 0) {
                $status = "<button type='button' class='btn btn-custon-rounded-three btn-danger'>Rejected</button>";
            }
            $tblQuery = $tblQuery . 
            "<tr><td></td><td>".$loan["loan_id"]."</td>              
            <td> R.s ".$loan["Amount"]."</td>            
            <td>".$loan["reason"]."</td>   
            <td>".$loan["requestedDate"]."</td>   
            <td>".$loan["Duration_in_months"]." Months</td>                
            <td>".$status."</td>
            <td> <a href='viewMyLoanDetails.php?loan_id=".$loan["loan_id"]."'><u><b>Show More --><u><b> </a></td></tr>";
        }
        return $tblQuery;
    }

    /**
     * Make Installment Payment
     */
    public function makePayment($accID, $loanId, $amount)
    {
        $enoghmoney = (new Account($accID))->hasEnoghMoney($amount);
        if($enoghmoney == "TRUE"){
            $withDrew = (new Withdraw($accID, $amount, "For Loan Payment"));
            if($withDrew->makeWithDraw() == SUCCESSWITHDRAW){
                return (new Installment())->makePayment($loanId, $amount);
            }
            return REACHEDMAXWITHDRAWAL;
        }
        return NOTENOUGHMONEY;
    }

    /**
     * Get All My Loans
     */
    public function myLoanDeatails($loanId)
    {
        $loan = (new Loan())->setLoanId($loanId);
        return $loan->getmyLoanDeatails();
    }
    /**
     * Check Pass
     */
    public function checkPass($oldPass)
    {
        $sql = "SELECT * FROM customer WHERE eMail = ? AND password = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$this->getMail(), $oldPass]);
        $result = $stmt->fetchAll();
        if ($result) {
            return "TRUE";
        }
        return "FALSE";
    }

    /**
     * change PAss
     */

    public function changePass($pass)
    {
        $sql = "UPDATE `customer` SET `password` = ? WHERE eMail = ?";
        $stmt = (new Connection)->connect()->prepare($sql);       
        if ($stmt->execute([$pass, $this->getMail()])) {
            return CHANGPASS;
        }
        return "FAILED";
    }

    /**
     * Get the value of tempAddress
     */ 
    public function getTempAddress():string
    {
        return $this->tempAddress;
    }

    /**
     * Get the value of officialAddress
     */ 
    public function getOfficialAddress():string
    {
        return $this->officialAddress;
    }

    /**
     * Get the value of job
     */ 
    public function getJob():string
    {
        return $this->job;
    }

    /**
     * Get the value of openedBy
     */ 
    public function getOpenedBy():string
    {
        return $this->openedBy;
    }
}


?>
