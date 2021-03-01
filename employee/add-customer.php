
<?php
        include("../layout/header.php");
        use Classess\Auth\Staff;
        if(!($loginedUser instanceof Staff)){
            header("location:../error/403.php");
        }
        use Classess\Auth\Customer;
        if (isset($_POST['add'])){
            $currentDate=date('Y-m-d h:i:s a', time());
            $random_digit=rand(000000,999999);
            if($_FILES['dp']['name']){
                $fileName = $_FILES['dp']['name'];
                $dp = $random_digit.$fileName;
            }else{
                $dp = NULL;
            }            
            $customer=new Customer($_REQUEST['email'],$_REQUEST['NIC'],$_REQUEST['firstName'],$_REQUEST['mobileNo'],$loginedUser->getBrachCode(),$_REQUEST['dob'],$_REQUEST['tempAddress'],$_REQUEST['permanentAddress'],$_REQUEST['job'],$_REQUEST['officialAddress'],$loginedUser->getID(),$dp,$currentDate);
            $password=md5($_REQUEST['password']);
            $result=$customer->register($password);
            if ($result){
                echo "Succesfully registerd"; 
            }
            else{
                echo "Failed";
            }
        }
?>
<script>
    changeTitle("Add Customer | Core Bank");
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
                                            <li><span class="bread-blod">Add CUstomer</span>
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
                                <li class="active"><a href="#description">Customer Information</a></li>                               
                            </ul>
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <div id="dropzone1" class="pro-ad">
                                                    <form action="./add-customer.php" class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload" method="POST" enctype="multipart/form-data">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                    <input name="NIC" type="text" class="form-control" placeholder="NIC" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input name="firstName" type="text" class="form-control" placeholder="Full Name" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input name="email" type="text" class="form-control" placeholder="example@email.com" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input name="password" type="password" class="form-control" placeholder="password" required>
                                                                </div>                                                               
                                                                <div class="form-group">
                                                                    <input name="tempAddress" type="text" class="form-control" placeholder="Temporary Address" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input name="permanentAddress" type="text" class="form-control" placeholder="Permanent Address" required>
                                                                </div>
                                                                                                                        

                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <input name="job"  type="text" class="form-control" placeholder="Job" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input name="officialAddress" type="text" class="form-control" placeholder="Official Address" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <h5>Date of Birth</h5>
                                                                    <input name="dob" type="date" class="form-control" placeholder="Date of Birth" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input name="mobileNo" type="number" class="form-control" placeholder="Mobile no." required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <h5>DP</h5>
                                                                    <input name="dp" id="dp" class="hd-pro-img" type="file" required />
                                                                </div>     
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="payment-adress">
                                                                    <button name="add" type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
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