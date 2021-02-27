<?php

include("../layout/header.php");
use Classess\Auth\Staff;
if(!($loginedUser instanceof Staff)){
    header("location:../error/403.php");
}
if (isset($_POST["addAccount"])) {
    $msg = $loginedUser->createFD($_POST["sacid"], $_POST["balance"], $_POST["acctype"]);
}
?>
<script>
    changeTitle("Create FD Account | Core Bank");
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
                                            <li><span class="bread-blod">Create Fixed Deposit</span>
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
                                <li class="active"><a href="#description">Fixed Deppsit Details</a></li>                        
                            </ul>
                            <div class="add-product">
                                <a href="AllFD.php">View FDs</a>
                                </div>
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <div id="dropzone1" class="pro-ad addcoursepro">
                                                    <form action="#" class="dropzone dropzone-custom needsclick addaccountFD" id="demo1-upload" method="POST">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <input name="sacid" type="text" class="form-control" placeholder="Saving Account ID">
                                                                </div>                                                                
                                                                <div class="form-group">
                                                                    <input name="balance" type="number" class="form-control" placeholder="Enter Intial Amount" min="0">
                                                                </div>
                                                                <div class="form-group">
                                                                    <select name="acctype" class="form-control">
																		<option value="none" selected disabled>Select Plan </option>
																		<option value="3 year">3 years</option>
																		<option value="one year">one year</option>
																		<option value="half year">6 months</option>
																	</select>
                                                                </div>

                            

                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="sparkline12-list mg-b-30">
                                                                    <div class="sparkline12-hd">
                                                                        <div class="main-sparkline12-hd">
                                                                            <h1>Fixed Deposit Plans</h1>
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
                                                                                        <th>Duration in Month </th>
                                                                                        <th>Rate</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                        echo $loginedUser->getAllFDPlans();                                                                                        
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
                                                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="addAccount">Submit</button>
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