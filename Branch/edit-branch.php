
            <?php
            include("../layout/header.php");
            use Classess\Auth\HeadManager;
                if(!($loginedUser instanceof HeadManager)){
                    header("location:../error/403.php");
                }
                $txt = "";
                if(isset($_GET['B_code'])){
                        
                    $B_code = $_GET['B_code'];
                    $singleBranch = $loginedUser->branchRow($B_code);
                    
                }
                if (isset($_POST["edit_branch"])) 
                {
                    $name = $_POST['branch_name'];
                    $type = $_POST['branch_type'];
                    $address = $_POST['branch_address'];
                    $contact = $_POST['contact'];
                    
                    $txt = $singleBranch->updateBranch($B_code, $name, $address, $type, $contact);

                    $txt_color = 'red';
                    if ($txt == "Sucessfully Updated.") {
                        $txt_color = 'green';
                        header("location:branch.php");
                    }
                }
            ?>

            <div class="breadcome-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="breadcome-list single-page-breadcome">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="breadcome-heading">
                                            <!-- <form role="search" class="sr-input-func">
                                                <input type="text" placeholder="Search..." class="search-int form-control">
                                                <a href="#"><i class="fa fa-search"></i></a>
                                            </form> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <ul class="breadcome-menu">
                                            <li><a href="../home/">Home</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">Edit Branch</span>
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
                                <li class="active"><a href="#description">Edit Branch</a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <form id="edit-branch" action="#" class="edit-branch" method="POST" >
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="branch_name"> Branch Name</label>
                                                                <input name="branch_name" id="branch_name" type="text" class="form-control" placeholder="Branch Name" value="<?php echo $singleBranch->getB_name();?>" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="branch_name">Choose Type Of Branch</label>
                                                                <select name="branch_type" id="branch_type" class="form-control" >
                                                                    <option value="" disabled selected>Choose Type Of Branch</option>
                                                                    <option value="H_O">Head Office</option>
                                                                    <option value="br">Branch</option>
                                                                </select>         
                                                            </div>                                                      
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="branch_name"> Branch Contact</label>
                                                            <div class="form-group">
                                                                <input name="contact" id="contact" type="number" class="form-control" placeholder="Contact" value="<?php echo $singleBranch->getB_contact();?>" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="branch_name"> Branch Address</label>
                                                                <input name="branch_address" id="branch_address" type="text" class="form-control" placeholder="Branch Address" value="<?php echo $singleBranch->getB_address();?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="payment-adress">
                                                                <div style="color:<?php echo @$txt_color ?>;font-size:20px;"><?php echo @$txt; ?></div>
                                                                <button name="edit_branch" id="edit_branch" type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
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
<?php
    include("../layout/footer.php");
?>