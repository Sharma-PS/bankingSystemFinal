<?php

include("../layout/header.php");

use Classess\Auth\Customer;
use Includes\FD\FD;

if(!($loginedUser instanceof Customer)){
    header("location:../error/403.php");
}
$hasFD = $loginedUser->hasFD();
if (isset($_POST["requestLoan"])) {
    $msg = $loginedUser->requestLoan($loginedUser->getNIC(), $_POST["balance"], $_POST["reason"], $_POST["durationInMonth"], $_POST["planId"], $hasFD->getBalance());
}

?>
<script>
    changeTitle("Request A Loan | Core Bank");
</script>

<!-- end header -->
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
                                            <li><span class="bread-blod">Request Loan</span>
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="product-payment-inner-st">
                            <ul id="myTabedu1" class="tab-review-design">
                                <li class="active"><a href="#description">Request Loan Form</a></li>                        
                            </ul>
                            <div class="add-product">                                
                            </div>
                            <?php
                                if($hasFD instanceof FD){                                
                            ?>
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <div id="dropzone1" class="pro-ad addcoursepro">
                                                    <form action="#" class="dropzone dropzone-custom needsclick addaccountloan" id="demo1-upload" method="POST">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                                                             
                                                                <div class="form-group">
                                                                    <input name="balance" type="number" class="form-control" placeholder="Enter Intial Amount" min="0">
                                                                </div>                                                                
                                                                <div class="form-group">
                                                                    <textarea name="reason" class="form-control" placeholder="Reason of Your loan"></textarea>
                                                                </div>                
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <input name="durationInMonth" type="number" class="form-control" placeholder="Duration In Months" min="0">
                                                                </div>
                                                                <div class="form-group">
                                                                    <select name="planId" class="form-control">
																		<option value="none" selected disabled>Select Plan Id</option>
																		<?php 
                                                                            echo $loginedUser->getLoanPlanIdsAsOptions();
                                                                        ?>
																	</select>
                                                                </div>                                                                 
                                                                <div class="sparkline12-list mg-b-30">
                                                                    <div class="sparkline12-hd">
                                                                        <div class="main-sparkline12-hd">
                                                                            <h1>Loan Plans</h1>
                                                                        </div>
                                                                    </div>
                                                                    <div class="sparkline12-graph">
                                                                        <div class="static-table-list">                                                                
                                                                            <table class="table hover-table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Category</th>
                                                                                        <th>Description</th>                                                                                        
                                                                                        <th>Rate</th>
                                                                                        <th>Maximum Amount</th>
                                                                                        <th>Maximum Amount Apply tp FD</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                        echo $loginedUser->getAllLoanPlans();                                                                                        
                                                                                    ?>                                                                                                                                                    
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>                                                               
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="payment-adress">
                                                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="requestLoan">Submit</button>
                                                                    <br/><br/>
                                                                    <div class="col-lg-3 col-md-3 col-sm-3"></div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                        <div class="alert-icon shadow-inner res-mg-t-30 table-mg-t-pro-n">
                                                                            <?php echo @$msg; ?>
                                                                        </div>                  
                                                                    </div>                                        
                                                                    <div class="col-lg-3 col-md-3 col-sm-3"></div>          
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                }else{
                                    echo $hasFD;
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
include("../layout/footer.php");
?>