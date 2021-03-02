<?php

include("../layout/header.php");

use Classess\Auth\Customer;
if(!($loginedUser instanceof Customer)){
    header("location:../error/403.php");
}
if (isset($_POST["Pay"])) {
    $msg = $loginedUser->makePayment($_POST["FaccID"],$_POST["loanID"], $_POST["payment"]);
}
?>
<script>
    changeTitle("Loan Installment | Core Bank");
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
                                            <li><span class="bread-blod">Loan Installment</span>
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
                                <li class="active"><a href="#description">Payment Details</a></li>                        
                            </ul>
                            <div class="add-product">                                
                                </div>
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <div id="dropzone1" class="pro-ad addcoursepro">
                                                    <form action="#" class="dropzone dropzone-custom needsclick addpaymentAcc" id="demo1-upload" method="POST">
                                                        <div class="row">
                                                            
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                <label for="FaccID">Select Your Account</label>
                                                                <select name="FaccID" class="form-control" readonly id="FaccID">
                                                                    <option value="none" selected disabled>Select Your Account Id</option>
                                                                    <?php 
                                                                        echo $loginedUser->getAccIdAsOptions();
                                                                    ?>
                                                                </select>
                                                                </div>                                                                                          
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <label for="loanID">Enter Loan ID</label>
                                                                    <input name="loanID" type="number" class="form-control" placeholder="Loan ID" id="loanID" min=0>
                                                                </div>                                                                                          
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <label for="payment">Enter Amount Here</label>
                                                                    <input name="payment" type="number" class="form-control" placeholder="Payment Amount" id="payment" min=0>
                                                                </div>       
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="payment-adress">
                                                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="Pay">Pay</button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
include("../layout/footer.php");
?>