<?php
namespace Classess\Auth;
use Includes\Branch\Branch;

class HeadManager extends Manager
{
    //constructor
    public function __construct($id, $email, $nic, $fname, $mobileNo, $designation, $branchCode, $DOB, $currentAddress, $dp, $joinedDate)
    {
        parent::__construct($id, $email, $nic, $fname, $mobileNo, $designation, $branchCode, $DOB, $currentAddress, $dp, $joinedDate);
    }

    public function addBranch($B_code, $B_name, $B_address, $B_type, $B_contact, $B_status, $B_open)
    {
        $branch = new Branch();
        $result = $branch->addBranch($B_code, $B_name, $B_address, $B_type, $B_contact, $B_status, $B_open);
        return $result;
    }

    public function branchList()
    {
        $branch = new Branch();
        $result = $branch->branchList();
        return $result;
    }

    public function showBranch($id)
    {
        $branch = new Branch();
        $result = $branch->showBranch($id);
        return $result;
    }

    public function changeStatus($ID, $status)
    {
        $branch = new Branch();
        $result = $branch->changeStatus($ID, $status);
    }

    public function branchRow($id)
    {
        $branch = new Branch();
        return $branch->branchRow($id);
        
    }
}

?>