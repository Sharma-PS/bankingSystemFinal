
<?php
        include("../layout/header.php");
        use Classess\Auth\Staff;
        if(!($loginedUser instanceof Staff)){
            header("location:../error/403.php");
        }
        use Classess\Auth\Customer;
        $data=$loginedUser->showCustomer($_SESSION['nic']);
        if (isset($_POST['edit'])){            
            $random_digit=rand(000000,999999);
            $fileName = $_FILES['dp']['name'];
            $dp = $random_digit.$fileName;
            $customer=new Customer($_REQUEST['email'],$_REQUEST['NIC'],$_REQUEST['firstName'],$_REQUEST['mobileNo'],$_REQUEST['openedBranch'],$_REQUEST['dob'],$_REQUEST['tempAddress'],$_REQUEST['permanentAddress'],$_REQUEST['job'],$_REQUEST['officialAddress'],$_REQUEST['openedBy'],$dp,$_REQUEST['joinedDate'],null);
            $result=$loginedUser->editCustomer($customer,$currentDate);
            if($result){
                echo "Edited";
                header("location: all-customers.php");
            }
            else{
                echo "Failed";
            }
        }
?>
<script>
    changeTitle("Edit Customer | Core Bank");
</script>
            <div class="breadcome-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="breadcome-list single-page-breadcome">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="breadcome-heading">                                           
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <ul class="breadcome-menu">
                                            <li><a href="../home/">Home</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">Edit Customer</span>
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
                                <li class="active"><a href="#description">Edit Customer Information</a></li>                              
                            </ul>
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <div id="dropzone1" class="pro-ad">
                                                    <form action="edit-customer.php" method="POST" class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload" enctype="multipart/form-data">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label>NIC</label>
                                                                    <input name="NIC" type="text" class="form-control" value=<?php echo $data['NIC'] ?> required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Name</label>
                                                                    <input name="firstName" type="text" class="form-control" value=<?php echo $data['name'] ?> required> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Mail</label>
                                                                    <input name="email" type="text" class="form-control" value=<?php echo $data['eMail'] ?> required>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label>Mobile No</label>
                                                                    <input name="mobileNo" type="number" class="form-control" value=<?php echo $data['mobileNo'] ?> required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Temporary Address</label>
                                                                    <input name="tempAddress" type="text" class="form-control" value=<?php echo $data['tempAddress'] ?> required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Permanent Address</label>
                                                                    <input name="permanentAddress" type="text" class="form-control" value=<?php echo $data['permanantAddress'] ?> required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>JOB</label>
                                                                    <input name="job"  type="text" class="form-control" value=<?php echo $data['job'] ?> required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Official Address</label>
                                                                    <input name="officialAddress" type="text" class="form-control" value=<?php echo $data['officialAddress'] ?> required>
                                                                </div>                                                                                                                            

                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label>Opened By</label>
                                                                    <input name="openedBy" type="text" class="form-control" value=<?php echo $data['openedBy'] ?> required disabled>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Opened Branch</label>
                                                                    <input name="openedBranch" type="text" class="form-control" value=<?php echo $data['openedBranch'] ?> required disabled> 
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Joined Date</label>
                                                                    <input name="joinedDate" type="text" class="form-control" value=<?php echo $data['joinedDate'] ?> required disabled>
                                                                </div> 
                                                                <div class="form-group">
                                                                    <label>Date of Birth</label>
                                                                    <input name="dob" type="date" class="form-control" value=<?php echo $data['DOB'] ?> required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>DP</label>
                                                                    <input name="dp" id="dp" class="hd-pro-img" type="file" value=<?php echo $data['dp'] ?> required>
                                                                    <div> <?php  $upload_dir = "../img/profile/";
                                                                                $data['dp'] = $data['dp'] ? ($data['dp']) : "customerAvatar.png";
                                                                                echo "<img src='".$upload_dir.$data['dp']."' width='100px' height='100px'>"; ?></div>
                                                                </div>                                                                                                                           
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="payment-adress">
                                                                    <!--button type="submit" name="edit" class="btn btn-primary waves-effect waves-light">Save</button-->
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