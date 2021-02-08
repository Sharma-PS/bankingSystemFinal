<?php

include("../layout/header.php");

use Classess\Auth\Staff;

if(!($loginedUser instanceof Staff)){
    header("location:../error/403.php");
}
if (isset($_POST["deposit"])) {
    $des = ($_POST["descrip"]) ? ($_POST["descrip"]) : NULL;
    $msg = $loginedUser->depositMoney($_POST["accID"], $_POST["amountOfMoney"], $des);
}
?>
<script>
    changeTitle("With Draw | Core Bank");
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
                                    <li><span class="bread-blod">Deposit</span>
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
                            <h1>Deposit Money <span class="basic-ds-n">To Account</span></h1>
                        </div>
                    </div>
                    <div class="sparkline10-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="basic-login-inner inline-basic-form">
                                        <form action="#" class="depositMoney" method="POST" onsubmit="return confirm('Check All Details')">
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 form-group">
                                                        <input type="number" class="form-control basic-ele-mg-b-10 responsive-mg-b-10" placeholder="Account ID" name="accID" min="0"/>
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
                                                                    <div class="login-horizental lg-hz-mg"><button class="btn btn-sm btn-primary login-submit-cs" type="submit" name="deposit">Deposit</button></div>
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