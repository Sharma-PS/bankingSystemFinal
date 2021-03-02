<?php

include("../layout/header.php");

use Classess\Auth\Staff;

if(!($loginedUser instanceof Staff)){
    header("location:../error/403.php");
}
?>
<script>
    changeTitle("My Profile | Core Bank");
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
                                            <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">My Profile</span>
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
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="profile-info-inner">
                            <div class="profile-img">
                                <img src="../<?php echo $loginedUser->getDp();?>" alt="" />
                            </div>
                            <div class="profile-details-hr">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p><b>Name</b><br /> <?php echo $loginedUser->getFname();?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                            <p><b>Designation</b><br /> <?php echo $loginedUser->getDesignation();?>.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p><b>Email ID</b><br /> <?php echo $loginedUser->getMail();?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                            <p><b>Phone</b><br /> <?php echo $loginedUser->getMobileNo();?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="address-hr">
                                            <p><b>Address</b><br /> <?php echo $loginedUser->getAddress();?>.</p>
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p><b>Branch</b><br /> <?php echo $loginedUser->getBrachCode();?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                            <p><b>NIC</b><br /> <?php echo $loginedUser->getNIC();?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p><b>DOB</b><br /> <?php echo $loginedUser->getDOB();?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                            <p><b>Joined Date</b><br /> <?php echo $loginedUser->getJoinedDate();?></p>
                                        </div>
                                    </div>
                                </div>                           
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                    <!--div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="product-payment-inner-st res-mg-t-30 analysis-progrebar-ctn">
                            <ul id="myTabedu1" class="tab-review-design">                                
                                <li class="active"><a href="#INFORMATION">My Details</a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content custom-product-edit">                                               
                                <div class="product-tab-list tab-pane fade active in" id="INFORMATION">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <input name="number" type="text" class="form-control" placeholder="First Name" >
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Last Name">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Address">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Date of Birth">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Department">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="number" class="form-control" placeholder="Pincode">
                                                        </div>                           
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group sm-res-mg-15 tbpf-res-mg-15">
                                                            <input type="text" class="form-control" placeholder="Description">
                                                        </div>
                                                        <div class="form-group">
                                                            <select class="form-control">
																<option>Select Gender</option>
																<option>Male</option>
																<option>Female</option>
															</select>
                                                        </div>
                                                        <div class="form-group">
                                                            <select class="form-control">
																	<option>Select country</option>
																	<option>India</option>
																	<option>Pakistan</option>
																	<option>Amerika</option>
																	<option>China</option>
																	<option>Dubai</option>
																	<option>Nepal</option>
																</select>
                                                        </div>
                                                        <div class="form-group">
                                                            <select class="form-control">
																	<option>Select state</option>
																	<option>Gujarat</option>
																	<option>Maharastra</option>
																	<option>Rajastan</option>
																	<option>Maharastra</option>
																	<option>Rajastan</option>
																	<option>Gujarat</option>
																</select>
                                                        </div>
                                                        <div class="form-group">
                                                            <select class="form-control">
																	<option>Select city</option>
																	<option>Surat</option>
																	<option>Baroda</option>
																	<option>Navsari</option>
																	<option>Baroda</option>
																	<option>Surat</option>
																</select>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Website URL">
                                                        </div>
                                                        <input type="number" class="form-control" placeholder="Mobile no.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="payment-adress mg-t-15">
                                                            <button type="submit" class="btn btn-primary waves-effect waves-light mg-b-15">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div-->
                </div>
            </div>
        </div>
        <?php 
include("../layout/footer.php");
?>