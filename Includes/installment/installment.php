<?php
namespace Includes\Installment;
use Includes\DB\Connection;

class Installment extends Connection
{     

    /**
     * return All Installments
     */
    public function getAllInstallments():array
    {
        $sql = "SELECT * FROM `installment` ORDER BY installment_ID DESC";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function makePayment($loanId, $amount)
    {
        $sql = "INSERT INTO `installment` VALUES (NULL, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = (new Connection)->connect()->prepare($sql);
        if($stmt->execute([$loanId, $amount])){
            return SUCCESSPAY;
        }
        return CANTPAY;
    }

}


?>