<?php
namespace Classess\Auth;
use Includes\DB\Connection;
use Includes\Loan\Loan;

class Manager extends Employee
{
    //construtor
    public function __construct($id, $email, $nic, $fname, $mobileNo, $designation, $branchCode, $DOB, $currentAddress, $dp, $joinedDate)
    {
        parent::__construct($id, $email, $nic, $fname, $mobileNo, $designation, $branchCode, $DOB, $currentAddress, $dp, $joinedDate);
    }

    public function getStaff($ID){
        $sql = "SELECT * FROM employee WHERE ID =?;";
        $stmt = (new Connection)->connect()->prepare($sql);
        
        $stmt->execute([$ID]);

        $result = $stmt->fetch();
        if($result){
            if(strtolower($result['designation']) == 'staff' ){

                $employee = new Employee($result['ID'],$result['email'],$result['NIC'],$result['name'],$result['mobileNo'],$result['designation'],$result['branchCode'],$result['DOB'],$result['Address'],$result['dp'],$result['JoinedDate']);
                $employee->setLeftDate($result['leftDate']);
                return $employee;
            }else{

                return NULL;  
            }   
        }
        return NULL;
    }

    public function getBranchCode(){
        $optQuery = "";
        $optQuery = $optQuery."<option value=".$this->getBrachCode().">".$this->getBrachCode()."</option>";
        return $optQuery;

    }


    public function updateleftDate($ID){

        $sql = "SELECT `leftDate` FROM `employee` WHERE ID=?;";
        $stmt = (new Connection)->connect()->prepare($sql);
        if($stmt->execute([$ID])){

            $result =  $stmt->fetch();

            if(($result['leftDate']) == NULL){

                $sql = "UPDATE `employee` SET leftDate=? WHERE ID=?;";
                $date = date('y-m-d H:i:s',time());
                $stmt = (new Connection)->connect()->prepare($sql);

                if($stmt->execute([$date,$ID])){
                    return SUCCESSFULUPDATE;
                }
                return FAILEDUPDATE;
            }else{
                return ALREADYLEFT;
            }
        }
        return FAILEDUPDATE;
    }

    public function getServicePeriod(){

        $start = date_create($this->getJoinedDate());
        $end = date_create(date("y-m-d",time()));
        $service  = date_diff($start,$end);

        return $service->format('%R%a');
    }

    public function updatePassword($ID,$password,$confirmPassword){
        if ($password == $confirmPassword){
            $sql = 'UPDATE employee SET `password`=? WHERE `ID`=?;';
            $stmt = (new Connection)->connect()->prepare($sql);
            if($stmt->execute([MD5($password),$ID])){
                return SUCCESSFULUPDATE;
            }
            return FAILEDUPDATE;
        }   
    }

    public function uploadDP($id,$dp,$fileTmpName,$fileDestination){

        $sql = 'SELECT ID,designation FROM employee WHERE ID=?;';
        $stmt = (new Connection)->connect()->prepare($sql);
        if($stmt->execute([$id])){
            $result = $stmt->fetch();
            if($result){
                if(($result['designation']=='staff') | $id==$this->getID()){
                    $sql = "UPDATE `employee` SET dp=? WHERE `ID`=? and `branchCode`=?";
                    $stmt = (new Connection)->connect()->prepare($sql);

                    if($stmt->execute([$dp,$id,$this->getBrachCode()])){
                        if($stmt->rowcount()){
                            move_uploaded_file($fileTmpName,$fileDestination);
                            return SUCCESSFULUPDATE;
                        }
                        return PERMISSONDENIED;
                        
                    }
                    return FAILEDINSERT; 
                }
               return PERMISSONDENIED;
            }
            return FAILEDINSERT; 
        } 

        return FAILEDINSERT; 
          
    } 


    public function addStaff($fullname,$address,$phoneNo,$DOB,$nic,$branchCode,$email,$password,$fileDestination,$job){
        
        $joinedDate = date("y-m-d H:i:s");
        
        $sql = 'SELECT `ID` FROM employee WHERE `NIC`=? or `email`=?';
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$nic,$email]);
        $result = $stmt->fetchAll();
        if($result){
            return ALREADYEXIST;
        }
        $sql = 'INSERT INTO `employee`(`ID`,`name`,`NIC`,`email`,`password`,`branchCode`,`designation`,`mobileNo`,`Address`,`DOB`,`dp`,`JoinedDate`,`updatedDate`,`leftDate`) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,NULL)';
        $stmt = (new Connection)->connect();
        $prepareSQL = $stmt->prepare($sql);
        if($prepareSQL->execute([$fullname,$nic,$email,MD5($password),$branchCode,$job,(int)$phoneNo,$address,date('y-m-d',strtotime($DOB)),$fileDestination,$joinedDate,$joinedDate])){
           

            return (int)$stmt->lastInsertId();
        }
        return FAILEDINSERT;
    }

    public function updateStaff( $ID, $fullname, $address, $phoneNo, $DOB, $nic, $branchCode, $email,$job){
        $time = date('y-m-d',time());
        $sql = "UPDATE `employee` SET `name`=?, `NIC`=? , `email`=?, `branchCode`=?, `designation`=? , `mobileNo`=?, `Address`=?, `DOB`=?, `UpdatedDate`=? WHERE `ID`=?;";
        $stmt = (new Connection)->connect()->prepare($sql);
        if ($stmt->execute([$fullname,$nic,$email,$branchCode,$job,$phoneNo,$address,$DOB,$time,$ID])){
            return SUCCESSFULUPDATE;
        }
        return FAILEDUPDATE;

    } 
    
    public function getAllStaff(){

        $sql = "SELECT `ID`,`name`,`email`,`branchCode`,`mobileNo`,`dp`,`JoinedDate`,`leftDate`,`designation` FROM `employee` WHERE designation = ? and `branchCode`=?";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute(['staff',$this->getBrachCode()]);
        $results = $stmt->fetchAll();
        $arr = array();
        $content = "";
        $sess_id = 0;
        foreach($results as $result){


            $arr[strval($sess_id)]=$result['ID'];


            $start = date_create(explode(' ',$result['JoinedDate'])[0]);

            $status = '<a href="#" style="background:#800202;"><i class="fa fa-times" aria-hidden="true"></i></a>';
            if($result['leftDate']==NULL){
                $status = '<a href="#" style="background:green;"><i class="fa fa-check" aria-hidden="true"></i></a>';
                $end = date_create(date('y-m-d',time()));

            }else{
                $end =  date_create(explode(' ',$result['leftDate'])[0]);

            }
            $service = date_diff($start,$end);
            
            $dp = $result['dp'];
            if ( $dp==NULL ){
                $dp = '../img/contact/1.jpg';
            }


            $content = $content.' <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 search">
                                        <div class="hpanel hblue contact-panel contact-panel-cs mg-t-30">
                                            <div class="panel-body custom-panel-jw">
                                                <div class="social-media-in">
                                                    ID : '.$result['ID'].'<br>
                                                    Status: '.$status.'    
                                                </div>
                                                <img alt="logo" class="img-circle m-b" src="'.$dp.'" style="width:76px; height:76px;">
                                                <h3><a class="managerName" href="">'.$result['name'].'</a></h3>
                                                <p class="all-pro-ad">
                                                    '.$result["designation"].'
                                                </p>
                                                <p>
                                                    Phone No: '.$result['mobileNo'].'<br>
                                                    email: <u><i>'.$result['email'].'</u></i><br>  
                                                    Branch Code: '.$result['branchCode'].'<br>
                                                    Service:  '.$service->format("%R%y years %m mon %a days ").'
                                                    
                                                </p>
                                            </div>
                                    <div class="panel-footer contact-footer" style="margin:auto;  text-align:center;">
                                                <form method="POST" action="edit-staff.php">
                                                    <input type="hidden" value="'.$arr[strval($sess_id)].'"  name="id">
                                                    <button type="submit" class="contact-stat" style="width:100%; cursor:pointer; background-color:#006DF0; border:none;"><span> Read More </span> <strong style="font-size: 15px;"> >>> </strong></button>
                                                </form>
                                    </div>
                                </div>
                            </div>';

                            $sess_id++;

        }
        return $content;
                   
    }

    public function ViewAllPendingLoans()
    {
        $loans = (new Loan())->ViewAllPendingLoans();
        $tblQuery = "";
        foreach ($loans as $loan) {
            $tblQuery = $tblQuery . 
            "<tr><td></td><td>".$loan["loan_id"]."</td>
            <td>".$loan["NIC"]."</td>        
            <td> R.s ".$loan["Amount"]."</td>
            <td>".$loan["interestPlanId"]."</td>   
            <td>".$loan["reason"]."</td>   
            <td>".$loan["requestedDate"]."</td>   
            <td>".$loan["Duration_in_months"]." Months</td>
            <td> <a href='viewPendingLoan.php?loan_id=".$loan["loan_id"]."'><u><b>Show More --><u><b> </a></td></tr>";
        }
        return $tblQuery;
    }

    public function ViewAllRejectedLoans()
    {
        $loans = (new Loan())->ViewAllRejectedLoans();
        $tblQuery = "";
        foreach ($loans as $loan) {
            $tblQuery = $tblQuery . 
            "<tr><td></td><td>".$loan["loan_id"]."</td>
            <td>".$loan["NIC"]."</td>        
            <td> R.s ".$loan["Amount"]."</td>
            <td>".$loan["interestPlanId"]."</td>   
            <td>".$loan["reason"]."</td>   
            <td>".$loan["requestedDate"]."</td>   
            <td>".$loan["Duration_in_months"]." Months</td>
            <td> <a href='viewRejectedLoan.php?loan_id=".$loan["loan_id"]."'><u><b>Show More --><u><b> </a></td></tr>";
        }
        return $tblQuery;
    }

    public function ViewAllApprovedLoans()
    {
        $loans = (new Loan())->ViewAllApprovedLoans();
        $tblQuery = "";
        foreach ($loans as $loan) {
            $tblQuery = $tblQuery . 
            "<tr><td></td><td>".$loan["loan_id"]."</td>
            <td>".$loan["NIC"]."</td>        
            <td> R.s ".$loan["Amount"]."</td>
            <td>".$loan["interestPlanId"]."</td>   
            <td>".$loan["reason"]."</td>   
            <td>".$loan["requestedDate"]."</td>   
            <td>".$loan["Duration_in_months"]." Months</td>
            <td> <a href='viewApprovedLoan.php?loan_id=".$loan["loan_id"]."'><u><b>Show More --><u><b> </a></td></tr>";
        }
        return $tblQuery;
    }
    
}

?>