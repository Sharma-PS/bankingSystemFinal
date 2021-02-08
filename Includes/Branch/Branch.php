<?php 
namespace Includes\Branch;
use Includes\DB\Connection;

class Branch extends Connection
{
    use constructable;
    private $B_code, $B_name, $B_address, $B_type, $B_contact, $B_status, $B_open;

    public function __construct1($B_code, $B_name, $B_address, $B_type, $B_contact, $B_status, $B_open)
    {
        $this->B_code = $B_code;
        $this->B_name = $B_name;
        $this->B_address = $B_address;
        $this->B_type = $B_type; 
        $this->B_contact = $B_contact;
        $this->B_status = $B_status;
        $this->B_open = $B_open;
    }

    public function __construct2()
    {
        # code...
    }

    

    public function addBranch($B_code, $B_name, $B_address, $B_type, $B_contact, $B_status, $B_open)
    {
        if ($B_code && $B_name && $B_address && $B_type && $B_contact && $B_open) {
            $sql = "SELECT * FROM branch WHERE branchCode = ?";
            $stmt = (new Connection)->connect()->prepare($sql);
            $stmt->execute([$B_code]);
            $result = $stmt->fetchAll();

            if ($result) {
                return "This Branch is already Exist.";
            }else{
                $query = "INSERT INTO branch(branchCode, branchName, Address, type, contactNo, openedDate, status) VALUES(?,?,?,?,?,?,?)";
                $st = (new Connection)->connect()->prepare($query);

                if($st->execute([$B_code, $B_name, $B_address, $B_type, $B_contact, $B_open, $B_status])):
                    return "Sucessfully Added.";
                else:
                    return "Failed to Add. \n Try Again";
                endif;
            }
        }else {
            return "There is No Data.";
        }
    }

    public function updateBranch($B_code, $B_name, $B_address, $B_type, $B_contact)
    {
        if ($B_code && $B_name && $B_address && $B_type && $B_contact) {
            $sql = "UPDATE `branch` SET  `branchName` = ?, `Address` = ?, `type` = ?, `contactNo` = ? WHERE `branch`.`branchCode` = ?";
            $stmt = (new Connection)->connect()->prepare($sql);

            if($stmt->execute([$B_name, $B_address, $B_type, $B_contact, $B_code])):
                return "Sucessfully Updated.";
            else:
                return "Failed to Update. \n Try Again";
            endif;
        }else {
            return "There is Not Enough Data.";
        }
    }

    public function showBranch($B_code):array
    {
        $conn = (new Connection)->connect();
        $sql_1 = "SELECT COUNT(NIC) AS TotalCustomer FROM customer WHERE openedBranch = ? AND leftDate IS NULL  ";
        $sql_2 = "SELECT COUNT(ID) AS TotalEmployee FROM employee WHERE branchCode = ?  AND leftDate IS NULL ";
        $sql_3 = 'SELECT e.`ID`, e.`name`, e.`email`, e.`designation`, e.`mobileNo`, e.`dp`, b.branchName, b.type, b.contactNo, b.Address AS branchAddress, b.status , b.openedDate FROM `employee` e INNER JOIN branch b USING (branchCode) WHERE b.branchCode = ? AND (e.designation = "manager" OR e.designation = "head_manager")';
        $stmt_1 = $conn->prepare($sql_1);
        $stmt_2 = $conn->prepare($sql_2);
        $stmt_3 = $conn->prepare($sql_3);
        $stmt_1->execute([$B_code]);
        $stmt_2->execute([$B_code]);
        $stmt_3->execute([$B_code]);
        $result_1 = $stmt_1->fetch();
        $result_2 = $stmt_2->fetch();
        $result_3 = $stmt_3->fetch();
        $result_3["TotalCustomer"] = $result_1["TotalCustomer"];
        $result_3["TotalEmployee"] = $result_2["TotalEmployee"];
        $result_3["type"] = ($result_3["type"] == "br" ? ("Branch") : ("Head Office"));
        $result_3["dp"] = ($result_3["dp"]) ? ($result_3["dp"]) : ("img/profile/empAvata.jpg");
        return $result_3;
    }

    public function branchList()
    {
        $sql = "SELECT * FROM branch";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $tab = '';

        foreach ($result as $idx => $branch) {
                                            
            $status = 'Active';
            $color = "#40BF36";
            $ID_1 = "st-" . $branch['branchCode'];
            $ID_2 = "tg-" . $branch['branchCode'];
            $ID_3 = "ic-" . $branch['branchCode'];
            $icon = 'fa fa-toggle-on';
            $branchType = ($branch['type'] == "H_O") ? "Head Office" : "Branch";


            if ($branch['status'] == '0') {
                $status = 'Disable';
                $color = '#CC2020';
                $icon = 'fa fa-toggle-off';
            }

            $tab = $tab . "<tr>
                    <td></td>
                    <td>".(++$idx)."</td>
                    <td>{$branch['branchName']}</td>
                    <td>{$branch['branchCode']}</td>
                    <td><button id='{$ID_1}'  class='btn' style='background-color:{$color};'>{$status}</button></td>
                    <td>{$branchType}</td>
                    <td>{$branch['Address']}</td>
                    <td>{$branch['contactNo']}</td>
                    <td>{$branch['openedDate']}</td>
                    <td>
                        <a href='edit-branch.php?B_code=".$branch['branchCode']."'  role='button'  title='Edit' class='btn' style='border: 1px solid black;border-radius: 5px;background-color:#C0C0C0;'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>
                        <button  title='Change Branch Status' id='{$ID_2}' onclick='changeStatus(\"{$branch['branchCode']}\")' style='background-color:{$color};border: 1px solid black;border-radius: 5px;' class='btn'><i id='{$ID_3}' class='{$icon}' aria-hidden='true'></i></button>
                    </td>
                    <td><a role='button' class='btn' style='background-color:#E5E7E9;border: 1px solid black;border-radius: 5px;'  title='Show more...' href='show-branch.php?B_code=".$branch['branchCode']."'><i class='fa fa-info-circle edu-informatio' aria-hidden='true'></i> Show more...</a></td>
                </tr>";
        }
        return $tab;
    }

    public function changeStatus($B_code, $B_status)
    {
        $sql = "UPDATE `branch` SET `status` = ? WHERE `branch`.`branchCode` = ?";
        $stmt = (new Connection)->connect()->prepare($sql);

        if($stmt->execute([$B_status, $B_code])):
            return "Sucessfully Updated.";
        else:
            return "Failed to Update. \n Try Again";
        endif;

    }

    public function branchRow($B_code)
    {
        $sql = "SELECT * FROM branch WHERE branchCode = ?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$B_code]);
        $result = $stmt->fetch();
        $this->B_code = $B_code; 
        $this->B_name = $result["branchName"];
        $this->B_address = $result['Address'];
        $this->B_type = $result['type'];
        $this->B_contact = $result['contactNo'];
        return $this;
    }

    /**
     * Get the value of B_code
     */ 
    public function getB_code()
    {
            return $this->B_code;
    }

    /**
     * Get the value of B_address
     */ 
    public function getB_address()
    {
            return $this->B_address;
    }

    /**
     * Get the value of B_type
     */ 
    public function getB_type()
    {
            return $this->B_type;
    }

    /**
     * Get the value of B_contact
     */ 
    public function getB_contact()
    {
            return $this->B_contact;
    }

    /**
     * Get the value of B_status
     */ 
    public function getB_status()
    {
            return $this->B_status;
    }

    /**
     * Get the value of B_open
     */ 
    public function getB_open()
    {
            return $this->B_open;
    }

    /**
     * Get the value of B_name
     */ 
    public function getB_name()
    {
            return $this->B_name;
    }
}

trait constructable
{
    public function __construct() 
    { 
        $a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array([$this,$f],$a); 
        } 
    } 
}


?>