
            <?php
            include("../layout/header.php");

            use Classess\Auth\HeadManager;
                if(!($loginedUser instanceof HeadManager)){
                    header("location:../error/403.php");
                }                  
                $txt = "";            
                if (isset($_POST["add_branch"])) 
                {
                    $name = $_POST['branch_name'];
                    $code = $_POST['branch_code'];
                    $type = $_POST['branch_type'];
                    $address = $_POST['branch_address'];
                    $contact = $_POST['contact'];
                    $status = $_POST['status'];
                    $open = $_POST['open'];        
            
                    $txt = $loginedUser->addBranch($code, $name, $address, $type, $contact, $status, $open);
                    $txt_color = 'red';
                    if ($txt == "Sucessfully Added.") {
                        $txt_color = 'green';
                    }
                }
            ?>
            <script>
            changeTitle("Add Branch | Core Bank");
            </script>

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
                                            <li><span class="bread-blod">Add Branch</span>
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
                                <li class="active"><a href="#description">Add Branch</a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <form id="add-branch" action="#" class="add-branch" method="POST" >
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <input name="branch_name" id="branch_name" type="text" class="form-control" placeholder="Branch Name" >
                                                            </div>
                                                            <div class="form-group">
                                                                <input name="branch_code" id="branch_code" type="text" class="form-control" placeholder="Branch Code" >
                                                            </div>
                                                            <div class="form-group">
                                                                <select name="branch_type" id="branch_type" class="form-control" >
                                                                    <option value="" disabled selected>Choose Type Of Branch</option>
                                                                    <option value="H_O">Head Office</option>
                                                                    <option value="br">Branch</option>
                                                                </select>         
                                                            </div>
                                                            <div class="form-group">
                                                                <input name="branch_address" id="branch_address" type="text" class="form-control" placeholder="Branch Address" >
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <input name="contact" id="contact" type="number" class="form-control" placeholder="Contact" >
                                                            </div>
                                                            <div class="form-group">
                                                                <select name="status" id="status" class="form-control" >
                                                                    <option value="" disabled selected>Choose Status Of Branch</option>
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Disable</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                    <input name="open" id="finish" type="text" class="form-control" placeholder="Open Date">
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="payment-adress">
                                                                <div style="color:<?php echo @$txt_color ?>;font-size:20px;"><?php echo @$txt; ?></div>
                                                                <button name="add_branch" id="add_branch" type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
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