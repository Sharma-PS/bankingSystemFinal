<?php
namespace Includes\Plans;
use Includes\DB\Connection;

class LoanPlan extends Connection
{
    private $allPlans;
    public function __construct()
    {
        $sql = "SELECT * FROM loan_plan";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        $this->allPlans = $stmt->fetchAll();
    }

    /**
     * return All FD plans
     */
    public function getAllLoanPlans():array
    {
        return $this->allPlans;
    }

    public function getLoanIds()
    {
        $ids = array();
        foreach ($this->allPlans as $id) {
            array_push($ids, $id["loanPlanId"]);
        }
        return $ids;
    }
    
}


?>