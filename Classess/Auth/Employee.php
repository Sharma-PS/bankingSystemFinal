<?php
namespace Classess\Auth;

use Includes\DB\Connection;
use Classess\Account\Account;
use Includes\Plans\FDPlan;
use Includes\Plans\SavingPlan;

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
}

?>