<?php

include("../layout/header.php");

use Classess\Auth\Customer;

if(!($loginedUser instanceof Customer)){
    header("location:../error/403.php");
}
if (isset($_POST["transfer"])) {
    $des = ($_POST["descrip"]) ? ($_POST["descrip"]) : NULL;
    $msg = $loginedUser->TransferMoney($_POST["FaccID"], $_POST["TaccID"], $_POST["amountOfMoney"], $des);
}

?>
<script>
    changeTitle("WithDraw | Core Bank");
</script>
    <div class="breadcome-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcome-list single-page-breadcome">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                            
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <ul class="breadcome-menu">
                                    <li><a href="../home/">Home</a> <span class="bread-slash">/</span>
                                    </li>
                                    <li><span class="bread-blod">Money Transfer</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="basic-form-area mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline10-list mt-b-30">
                    <div class="sparkline10-hd">
                        <div class="main-sparkline10-hd">
                            <h1>Transfer Money<span class="basic-ds-n">Between Accounts</span></h1>
                        </div>
                    </div>
                    <div class="sparkline10-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="basic-login-inner inline-basic-form">
                                        <form action="#" class="TransferMoney" method="POST" onsubmit="return confirm('Check All Details')">
                                            <div class="form-group-inner">
                                                <div class="row"> 
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">                                                        
                                                        <select name="FaccID" class="form-control" readonly>
                                                            <option value="none" selected disabled>Select Your Account Id</option>
                                                            <?php 
                                                                echo $loginedUser->getAccIdAsOptions();
                                                            ?>
                                                        </select>
                                                    </div>                                                   
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">
                                                        <input type="number" class="form-control basic-ele-mg-b-10 responsive-mg-b-10" placeholder="To: Account ID" name="TaccID" min="0"/>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">
                                                        <input type="number" class="form-control basic-ele-mg-b-10 responsive-mg-b-10" placeholder="Amount" name="amountOfMoney" min="0"/>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">
                                                        <input type="text" class="form-control basic-ele-mg-b-10 responsive-mg-b-10" placeholder="Description (Optional)" name="descrip"/>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">
                                                        <div class="login-btn-inner">
                                                            <div class="row">                                                                
                                                                <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12 form-group">
                                                                    <div class="login-horizental lg-hz-mg"><button class="btn btn-sm btn-primary login-submit-cs" type="submit" name="transfer">Transfer</button></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                                <?php echo @$msg;?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include("../layout/footer.php");
?>