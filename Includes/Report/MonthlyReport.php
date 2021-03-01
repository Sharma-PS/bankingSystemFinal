<?php
namespace Includes\Report;
use Includes\DB\Connection;

class MonthlyReport extends Connection
{     
    private $id, $startDate, $endDate, $totalDeposit, $totDepAmo, $totW, $totWA, $totTra, $totTraAmo, $activCus, $actEmp, $actFd, $actFDamo, $actLoan, $actLoanAmo, $penIns, $branchCode, $generatedOn;

    public function __construct($id = NULL, $startDate = NULL, $endDate = NULL, $totalDeposit = NULL, $totDepAmo = NULL, $totW = NULL, $totWA = NULL, $totTra = NULL, $totTraAmo = NULL, $activCus = NULL, $actEmp = NULL, $actFd = NULL, $actFDamo = NULL, $actLoan = NULL, $actLoanAmo = NULL, $penIns = NULL, $branchCode = NULL, $generatedOn = NULL)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->totalDeposit = $totalDeposit;
        $this->totDepAmo = $totDepAmo;
        $this->totW = $totW;
        $this->totWA = $totWA;
        $this->totTra = $totTra;
        $this->totTraAmo = $totTraAmo;
        $this->activCus = $activCus;
        $this->actEmp = $actEmp;
        $this->actFd = $actFd;
        $this->actFDamo = $actFDamo;
        $this->actLoan = $actLoan;
        $this->actLoanAmo = $actLoanAmo;
        $this->penIns = $penIns;
        $this->branchCode = $branchCode;
        $this->generatedOn = $generatedOn;
    }

    
    /**
     * return All Reports
     */
    public function getAllMonthlyReportBranch($brc):array
    {
        $sql = "SELECT id,startDate,endDate FROM `monthly_report` WHERE branchCode = ? ORDER BY generated_on DESC";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$brc]);
        return $stmt->fetchAll();
    }

    /**
     * Monthly Report Get
     */
    public function getMonthlyReport($id, $brc)
    {
        $sql = "SELECT * FROM `monthly_report` WHERE id = ? AND branchCode = ? ";
        $stmt = (new Connection)->connect()->prepare($sql);
        $stmt->execute([$id, $brc]);
        $result = $stmt->fetch();
        if($result){
            $this->id = $result["id"];
            $this->startDate = $result["startDate"];
            $this->endDate = $result["endDate"];
            $this->totalDeposit = $result["totalDeposit"];
            $this->totDepAmo = $result["total_deposit_amount"];
            $this->totW = $result["totalWithdrawal"];
            $this->totWA = $result["total_withdrawal_amount"];
            $this->totTra = $result["total transaction"];
            $this->totTraAmo = $result["total_transaction_amount"];
            $this->activCus = $result["total_active_customer"];
            $this->actEmp = $result["total_active_employee"];
            $this->actFd = $result["no_active_FD"];
            $this->actFDamo = $result["active_FD_amount"];
            $this->actLoan = $result["no_active_loan"];
            $this->actLoanAmo = $result["active_loan_amount"];
            $this->penIns = $result["no_pending_installments"];
            $this->branchCode = $result["branchCode"];
            $this->generatedOn = $result["generated_on"];
            return $this;
        }
        return "Failed";
    }
       

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of startDate
     */ 
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Get the value of endDate
     */ 
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Get the value of totalDeposit
     */ 
    public function getTotalDeposit()
    {
        return $this->totalDeposit;
    }

    /**
     * Get the value of totDepAmo
     */ 
    public function getTotDepAmo()
    {
        return $this->totDepAmo;
    }

    /**
     * Get the value of totW
     */ 
    public function getTotW()
    {
        return $this->totW;
    }

    /**
     * Get the value of totWA
     */ 
    public function getTotWA()
    {
        return $this->totWA;
    }

    /**
     * Get the value of totTra
     */ 
    public function getTotTra()
    {
        return $this->totTra;
    }

    /**
     * Get the value of totTraAmo
     */ 
    public function getTotTraAmo()
    {
        return $this->totTraAmo;
    }

    /**
     * Get the value of activCus
     */ 
    public function getActivCus()
    {
        return $this->activCus;
    }

    /**
     * Get the value of actEmp
     */ 
    public function getActEmp()
    {
        return $this->actEmp;
    }

    /**
     * Get the value of actFd
     */ 
    public function getActFd()
    {
        return $this->actFd;
    }

    /**
     * Get the value of actFDamo
     */ 
    public function getActFDamo()
    {
        return $this->actFDamo;
    }

    /**
     * Get the value of actLoan
     */ 
    public function getActLoan()
    {
        return $this->actLoan;
    }

    /**
     * Get the value of actLoanAmo
     */ 
    public function getActLoanAmo()
    {
        return $this->actLoanAmo;
    }

    /**
     * Get the value of penIns
     */ 
    public function getPenIns()
    {
        return $this->penIns;
    }

    /**
     * Get the value of branchCode
     */ 
    public function getBranchCode()
    {
        return $this->branchCode;
    }

    /**
     * Get the value of generatedOn
     */ 
    public function getGeneratedOn()
    {
        return $this->generatedOn;
    }
}


?>