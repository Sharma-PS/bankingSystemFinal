<?php
namespace Classess\Auth;
use Classess\Account\Account;

interface Staff{
    public function getDesignation():string;
    public function getID():int;
    public function createAccount($NIC, $branch, $balance, $type):string;
    public function ViewAllAccounts():string;
    public function depositMoney($accID, $amount, $description):string;
}
?>