
            <?php
            include("../layout/header.php");

            use Classess\Auth\Manager;

                if(!($loginedUser instanceof Manager)){
                    header("location:../error/403.php");
                }
                if(isset($_GET['loan_id'])){                
                    $B_code = $_GET['loan_id'];
                    $detail = $loginedUser->showPendingLoan($B_code);            
                }else{
                    header("location:../error/404.php");
                }

                if(isset($_POST['shiftToPending'])){                                
                    $msg = $detail->moveToPendingLoan() ;            
                }
            ?>
<script>
    changeTitle("View Rejected Loan | Core Bank");
</script>
            <div class="breadcome-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="breadcome-list single-page-breadcome">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="breadcome-heading">                                           
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <ul class="breadcome-menu">
                                            <li><a href="../home/">Home</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">Rejected Loan Details</span>
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
        <!-- Single pro tab review Start-->
        <div class="single-pro-review-area mt-t-30 mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-centered">
                        <div class="profile-info-inner">
                            <div class="profile-img">
                                <img style="border: 0.5px solid black;border-radius: 10px;" src="../img/loan/rejectedLoan.png" alt="Image" />
                            </div>
                            <div class="profile-details-hr">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="address-hr">                                            
                                            <p><b>Loan ID : <?php echo $detail->getLoanId(); ?></b></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p><b>Customer NIC</b><br /> <?php echo $detail->getNIC(); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                            <p><b>Amount</b><br /> <?php echo $detail->getBalance();?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p><b>Interest Plan ID</b><br /><?php echo $detail->getPlanId() ;?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                            <p><b>Requested Date </b><br /> <?php echo $detail->getRequestedDate() ;?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p><b>Duration In Months</b><br /> <?php echo $detail->getDuInMon(); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                            <p><b>Installment Amount</b><br /> Rs <?php echo $detail->getInstallment()?></p>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="address-hr">
                                            <p><b>Reason</b><br /> <?php echo $detail->getReason();?></p>
                                        </div>
                                    </div>
                                    <form class="address-hr " method="POST" action="#">
                                        <input type="submit" value="Move to Pending Loan" name="shiftToPending"  class="btn btn-primary waves-effect waves-light">
                                    </form>
                                    <?php echo @$msg; ?>
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