<?php
namespace Includes\Plans;
use Includes\DB\Connection;

class FDPlan extends Connection
{
    private $allPlans;
    public function __construct()
    {
        $sql = "SELECT * FROM fd_plan";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        $this->allPlans = $stmt->fetchAll();
    }

    /**
     * return All FD plans
     */
    public function getAllFDPlans():array
    {
        return $this->allPlans;
    }
    
    public function getAllSavingPlansId():array
    {
        $ids = array();
        foreach ($this->allPlans as $id) {
            array_push($ids, $id["s_plan_id"]);
        }
        return $ids;
    }

    public function setRate($id,$rate)
    {

        $sql = "UPDATE `fd_plan` SET `rate` = ? WHERE `fd_plan`.`fd_plan_id` = ?"; 
        $stmt = (new Connection)->connect()->prepare($sql);

        if($stmt->execute([$rate, $id])):
            return "Sucessfully Updated.";
        else:
            return "Failed to Update. \n Try Again";
        endif;    
    }

}


?>