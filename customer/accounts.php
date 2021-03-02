<?php
include("../layout/header.php");

use Classess\Account\Account;
use Classess\Auth\Customer;
if(!($loginedUser instanceof Customer)){
    header("location:../error/403.php");
}
$accounts = $loginedUser->getAccount();
?>
<style>
table, th, td {  
  border-collapse: collapse;
}
th, td {
  padding: 15px;
}
</style>
<script>
    changeTitle("My Account | Core Bank");
</script>
            <!-- Mobile Menu end -->
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
                                            <li><a href="index.php">Home</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">Account Details</span>
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
                            <ul id="myTab4" class="tab-review-design">
                                <li class="active"><a href="#description">My Account Details</a></li>                          
                            </ul>
                            <?php
                                if ($accounts[0] instanceof Account) { 
                                    foreach ($accounts as $account) {                                       
                            ?>
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <div class="demo-container">
                                                    <div class="card-wrapper"></div>
                                                    <form class="payment-form mg-t-30">
                                                    <table>
                                                        <tr>                                                            
                                                            <div class="form-group">                                                            
                                                                <td><h4>Account ID </h4></td><td><h4><?php echo $account->getAccID();?></h4></td>
                                                            </div>
                                                        </tr>                                                       
                                                        <tr>
                                                            <div class="form-group">                                                            
                                                                <td><h4>Your Branch </h4></td><td><h4><?php echo $account->getBranch();?></h4></td>
                                                            </div>
                                                        </tr>
                                                        <tr>
                                                            <div class="form-group">                                                            
                                                                <td><h4>Your Account Type </h4></td><td><h4> <?php echo $account->getType();?></h4></td>
                                                            </div>
                                                        </tr>
                                                        <tr>
                                                            <div class="form-group">                                                            
                                                                <td><h4>Opened Date </h4></td><td><h4><?php echo $account->getCreatedDate();?></h4></td>
                                                            </div>
                                                        </tr>
                                                        <tr>
                                                            <div class="form-group">                                                            
                                                                <td><h4>Updated Date </h4></td><td><h4><?php echo $account->getUpdatedDate();?></h4></td>
                                                            </div>                                                        
                                                        </tr>
                                                        <tr>
                                                            <div class="form-group">                                                            
                                                                <td><h4>Your Balance </h4></td><td><h4> R.s. <?php echo $account->getBalance();?></h4></td>
                                                            </div>        
                                                        </tr>
                                                        <tr>
                                                    </table>                                                                        
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                    }
                                    echo "<hr width=100%>";
                                }
                                else {
                                    echo $accounts;
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