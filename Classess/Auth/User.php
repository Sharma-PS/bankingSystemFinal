<?php
namespace Classess\Auth;

require 'autoloader.php';
use Includes\DB\Connection;

abstract class User extends Connection{

    /**
     * employee person  details
     */

    private $email, $nic, $fname, $mobileNo, $branchCode, $DOB, $currentAddress, $dp, $joinedDate;

    public function __construct($email, $nic, $fname, $mobileNo, $branchCode, $DOB, $currentAddress, $dp, $joinedDate)
    {
        $this->fname = $fname;
        $this->email = $email;
        $this->dp = $dp;
        $this->nic = $nic;
        $this->mobileNo = $mobileNo;
        $this->branchCode = $branchCode;
        $this->DOB = $DOB;
        $this->currentAddress = $currentAddress;
        $this->joinedDate = $joinedDate;
    }
    public static function logout():void
    {
        session_start();
        unset($_SESSION['core_bank_user']);
        session_destroy();
        header("location:index.php");
    }

    public static abstract function login($uname, $pass): string;

    /**
     * @return Fname
     */
    public function getFname(): string
    {
        return $this->fname;
    }

    /**
     * @return Email Id
     */
    public function getMail():String
    {
        return $this->email;
    }

    /**
     * @return mobileNo
     */
    public function getmobileNo():int
    {
        return $this->mobileNo;
    }

    /**
     * @return NIC
     */
    public function getNIC():String
    {   
        return $this->nic;
    }

    /**
     * @return Address
     */
    public function getAddress():String
    {
        return $this->currentAddress;
    }

    /**
     * @return Joindated
     */
    public function getJoinedDate():String
    {
        return $this->joinedDate;
    }

    /**
     * @return DOB
     */
    public function getDOB():String
    {
        return $this->DOB;
    }

    /**
     * @return brachCode
     */
    public function getBrachCode():String
    {
        return $this->branchCode;
    }

    /**
     * @return dp
     */
    public function getDp()
    {
        return $this->dp;
    }
    /**
     * @return left date
     */
    public function getLeftDate()
    {
        return $this->leftDate;
    }

    /**
     * setter
     */
    public function setLeftDate($date):void
    {
        $this->leftDate = $date;
    }
    
    
}

?>