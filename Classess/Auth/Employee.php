<?php
namespace Classess\Auth;

use Includes\DB\Connection;
use Classess\Account\Account;
use Includes\FD\FD;
use Includes\Plans\FDPlan;
use Includes\Plans\SavingPlan;
use Includes\Plans\LoanPlan;
use Includes\Transaction\Deposit;
use Includes\Transaction\TransferMoney;
use Includes\Transaction\Withdraw;

class Employee extends User implements Staff
{
    /**
     * employee person addition details
     */

    private $id, $designation;

    // constructor
    public function __construct($id, $email, $nic, $fname, $mobileNo, $designation, $branchCode, $DOB, $currentAddress, $dp, $joinedDate)
    {
        parent::__construct($email, $nic, $fname, $mobileNo, $branchCode, $DOB, $currentAddress, $dp, $joinedDate);
        $this->id = $id;
        $this->designation = $designation;
    }

    public static function login($userName, $password): string{
    /**
     * All staff members login to system
     */
        if ($userName && $password){
            $sql = "SELECT * FROM Employee WHERE email = ?";
            $stmt = (new Connection)->connect()->prepare($sql);
            $stmt->execute([$userName]);
            $result = $stmt->fetchAll();

            if(!$result){
                return "Your user name or password is wrong";
            }
            else{
                $resultRow = $result[0];
                if($password == $resultRow['password']){
                    $resultRow['dp'] = ($resultRow['dp']) ? $resultRow['dp'] : 'img/profile/empAvata.jpg' ;
                    if ($resultRow['designation'] == "head_manager") {
                        $_SESSION['core_bank_user'] = serialize(new HeadManager($resultRow['ID'], $userName, $resultRow['NIC'], $resultRow['name'], $resultRow['mobileNo'], $resultRow['designation'], $resultRow['branchCode'], $resultRow['DOB'], $resultRow['Address'], $resultRow['dp'], $resultRow['JoinedDate']));
                    }elseif ($resultRow['designation'] == "manager") {
                        $_SESSION['core_bank_user'] = serialize(new Manager($resultRow['ID'], $userName, $resultRow['NIC'], $resultRow['name'], $resultRow['mobileNo'], $resultRow['designation'], $resultRow['branchCode'], $resultRow['DOB'], $resultRow['Address'], $resultRow['dp'], $resultRow['JoinedDate']));
                    }elseif ($resultRow['designation'] == "staff") {
                        $_SESSION['core_bank_user'] = serialize(new Employee($resultRow['ID'], $userName, $resultRow['NIC'], $resultRow['name'], $resultRow['mobileNo'], $resultRow['designation'], $resultRow['branchCode'], $resultRow['DOB'], $resultRow['Address'], $resultRow['dp'], $resultRow['JoinedDate']));
                    }else {
                        return "Failed to login";
                    }
                    header("location:../home/");
                }
                else{
                    return "Your user name or password is wrong";
                }
            }
        }
        return "Warning: must give username and password";
    }

    /**
     * @return designation
     */
    public function getDesignation():string{
        return $this->designation;
    }

    /**
     * @return id
     */
    public function getID():int{
        return $this->id;
    }

    /**
     * Create new account 
     */
    public function createAccount($NIC, $branch, $balance, $type):string
    {
        $account = new Account(NULL, $NIC, $branch, $balance, $type);
        return $account->createNewAccount();
    }

    /**
     * Create new account 
     */
    public function createFD($said, $balance, $type):string
    {
        if($type == "3 year"){
            $mn = "36";
        }elseif($type == "one year"){
            $mn = "12";
        }elseif($type == "half year"){
            $mn = "6";
        }
        $fd = new FD(NULL, $said, $balance, $type, NULL, date('Y-m-d', strtotime(date("Y-m-d")."+ ".$mn." months")));
        return $fd->createNewFD();
    }

    /**
     * get All saving plans as Table
     */
    public function getAllSavingPlans($edit = NULL):string
    {
        $savingPlans = (new SavingPlan())->getAllSavingPlans();
        $tblQuery = "";
        foreach ($savingPlans as $key => $value) {
            $editRow = ($edit) ? ("<td><input type='text'/><button>Submit</button></td>") : "";
            $tblQuery = $tblQuery . 
            "<tr>
                <td>".(++$key)."</td>
                <td><b>".$value['s_plan_id']."</b></td>
                <td>".$value['s_plan_des']."</td>
                <td>".$value['minimum_amount']."</td>
                <td><span class='pie'>".$value['rate']."/100</span>".$value['rate']." %</td>
                $editRow
            </tr>";
        }
        return $tblQuery;
    }

    /**
     * get All saving plans as Table
     */
    public function getAllFDPlans($edit = NULL):string
    {
        $savingPlans = (new FDPlan())->getAllFDPlans();
        $tblQuery = "";
        foreach ($savingPlans as $key => $value) {
            $editRow = ($edit) ? ("<td><input type='text'/><button>Submit</button></td>") : "";
            $tblQuery = $tblQuery . 
            "<tr>
                <td>".(++$key)."</td>
                <td><b>".$value['fd_plan_id']."</b></td>
                <td>".$value["description"]."</td>
                <td>".$value['duration_in_months']."</td>
                <td><span class='pie'>".$value['rate']."/100</span>".$value['rate']." %</td>
                $editRow
            </tr>";
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

    public function ViewAllAccounts():string
    {
        $accounts = (new Account)->ViewAllAccounts();
        $tblQuery = "";
        foreach ($accounts as $account) {
            $status = ($account["status"]) ? ("check") : ("ban");
            $tblQuery = $tblQuery . 
            "<tr><td></td><td>".$account["accID"]."</td>
            <td>".$account["NIC"]."</td>
            <td>".$account["branchName"]."</td>
            <td> R.s ".$account["balance"]."</td>
            <td>".$account["createdDate"]."</td>
            <td>".$account["type"]."</td>
            <td class='datatable-ct'><span class='pie'>".$account["no_of_withdrawals"]."/5</span>
            </td>
            <td class='datatable-ct'><i class='fa fa-$status'></i></td>
            <td>".$account["closed_date"]."</td></tr>";
        }
        return $tblQuery;
    }

    /**
     * View ALl FDs
     */
    public function ViewAllFDs():string
    {
        $fds = (new FD)->ViewAllFDs();
        $tblQuery = "";
        foreach ($fds as $fd) {
            $status = ($fd["withdrewOrNot"]) ? ("check") : ("ban");
            $tblQuery = $tblQuery . 
            "<tr><td></td><td>".$fd["FD_ID"]."</td>
            <td>".$fd["savingAcc_id"]."</td>
            <td>".$fd["FD_plan_id"]."</td>
            <td> R.s ".$fd["amount"]."</td>
            <td>".$fd["startDate"]."</td>
            <td>".$fd["maturityDate"]."</td>           
            <td class='datatable-ct'><i class='fa fa-$status'></i></td>            </tr>";
        }
        return $tblQuery;
    }

    /**
     * Deposit Money from Customer
     */
    public function depositMoney($accID, $amount, $description):string
    {
        $deposit = new Deposit($accID, $amount, $description, parent::getBrachCode(), $this->id);
        return $deposit->makeDeposit();
    }

    /**
     * Withdraw Money from Customer
     */
    public function withdrawMoney($accID, $amount, $description):string
    {
        $withDraw = new Withdraw($accID, $amount, $description, parent::getBrachCode(), $this->id);
        return $withDraw->makeWithDraw();
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
     * View All Deposits
     */
    public function ViewAllDeposits():string
    {
        $deposits = (new Deposit)->getAllDeposits();
        $tblQuery = "";
        foreach ($deposits as $deposit) {            
            $tblQuery = $tblQuery . 
            "<tr><td></td><td>".$deposit["deposit_id"]."</td>
            <td>".$deposit["accID"]."</td>
            <td> R.s ".$deposit["amount"]."</td>
            <td>".$deposit["Description"]."</td>
            <td>".$deposit["branchCode"]."</td>
            <td>".$deposit["deposit_by"]."</td>           
            <td>".$deposit["time"]."</td></tr>";
        }
        return $tblQuery;
    }

    /**
     * View All Withdrawals
     */
    public function ViewAllWithdrawal():string
    {
        $withdrews = (new Withdraw)->getAllWithdraws();
        $tblQuery = "";
        foreach ($withdrews as $withdrew) {            
            $tblQuery = $tblQuery . 
            "<tr><td></td><td>".$withdrew["withdrawal_id"]."</td>
            <td>".$withdrew["accID"]."</td>
            <td> R.s ".$withdrew["amount"]."</td>
            <td>".$withdrew["Description"]."</td>
            <td>".$withdrew["branchCode"]."</td>
            <td>".$withdrew["withdrew_by"]."</td>           
            <td>".$withdrew["time"]."</td></tr>";
        }
        return $tblQuery;
    }

    /**
     * View All Trasactions
     */
    public function ViewAllTransaction():string
    {
        $transactions = (new TransferMoney)->getAllTransfers();
        $tblQuery = "";
        foreach ($transactions as $transaction) {            
            $tblQuery = $tblQuery . 
            "<tr><td></td><td>".$transaction["transaction_id"]."</td>
            <td>".$transaction["sender_id"]."</td>
            <td>".$transaction["recipient_id"]."</td>
            <td> R.s ".$transaction["amount"]."</td>
            <td>".$transaction["description"]."</td>   
            <td>".$transaction["time"]."</td></tr>";
        }
        return $tblQuery;
    }
}

?>