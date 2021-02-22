<?php
   include("../layout/header.php");
   use Classess\Auth\Manager;
   if(!($loginedUser instanceof Manager)){
       header("location:../error/403.php");
   }
   $fileDestination=NULL;
    if($_POST){
        if($_SESSION['W_id']==NULL){
        $_SESSION['W_id'] = $_POST['id'];         
        }
    }
    $employee = $loginedUser-> getStaff($_SESSION['W_id']);
    $ID = $_SESSION['W_id'];
    
    if( $_SESSION['W_id']==NULL ){
        header("Location:all-professors.php");
    }
    
    $dp = $employee->getDp();
    if( $dp == NULL){        
        $dp = "../img/profile/1.jpg";        
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

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <ul class="breadcome-menu">
                                            <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">Staff Profile</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>


        
        <!-- Single pro tab review Start-->
        <div class="single-pro-review-area mt-t-30 mg-b-15" >
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="profile-info-inner">
                            <div class="profile-img">
                                <img src=<?php echo $dp; ?> alt="" />
                            </div>
                            <div class="profile-details-hr">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p>
                                                <b>Branch Code:</b>
                                                <?php
                                                    echo $employee->getBrachCode();
                                                ?>
                                            </p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                            <p>
                                                <b>Bank ID:</b>
                                                <?php 
                                                    echo $ID; 
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="address-hr">
                                            <p>
                                                <b>Name</b><br /> 
                                                <?php 
                                                    echo $employee->getFname(); 
                                                    ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p>
                                                <b>Phone</b><br /> 
                                                <?php
                                                    echo $employee->getmobileNo();
                                                ?>
                                            </p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                        <p>
                                                <b>NIC</b><br /> 
                                                <?php
                                                    echo $employee->getNIC();
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="address-hr">
                                            <p>
                                                <b>Email</b><br /> <u><i>
                                                <?php
                                                    echo $employee->getMail();
                                                ?>
                                                </i></u>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p>
                                                <b>Designation</b><br /> 
                                                <?php
                                                    echo $employee->getDesignation();
                                                ?>
                                            </p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                        <p>
                                                <b>Date of Birth</b><br /> 
                                                <?php
                                                    echo $employee->getDOB();
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="address-hr">
                                            <p>
                                                <b>Address</b><br /> 
                                                <?php
                                                    echo $employee->getAddress();
                                                ?>
                                            
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr">
                                            <p>
                                                <b>Joined Date</b><br /> 
                                                <?php
                                                    echo $employee->getJoinedDate();
                                                ?>
                                            </p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                        <div class="address-hr tb-sm-res-d-n dps-tb-ntn">
                                        <p>
                                                <b>Left Date</b><br /> 
                                                <?php
                                                    $date = $employee->getLeftDate();

                                                    if($date){
                                                        echo $date;
                                                    }else{
                                                        echo "....";
                                                    }
                                                ?>
                                                    
                                                
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="product-payment-inner-st res-mg-t-30 analysis-progrebar-ctn" style="margin-bottom:50px;">
                            <ul id="myTabedu1" class="tab-review-design">
                                <li class="active"><a href="#description">Change Basic Info</a></li>
                                <li><a href="#reviews"> Change New Password </a></li>
                                <li><a href="#INFORMATION">Update Left Details</a></li>
                            </ul>
                        <div id="myTabContent" class="tab-content custom-product-edit">
                            <div class="product-tab-list tab-pane fade active in" id="description">
                                <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <form action="#" class="add-professors" method="POST" id="demo1-upload" >
                                                        <div class="row">
                                                                <input type="hidden" class="form-control" name="id" placeholder="ID" value="<?php echo $ID;?>" read-only>
                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> Full Name: </div>
                                                                    <input name="fullname" id="fullname" value="<?php echo $employee->getFname();?>" type="text" class="form-control" placeholder="Full Name">
                                                                </div>
                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> Branch Code : </div>
                                                                    
                                                                        <select name="branchCode" id="branchCode" class="form-control" >
																			<option  selected hidden><?php echo $employee->getBrachCode();?></option>
																			<?php
                                                                                echo $loginedUser->getBranchCode();
                                                                            ?>
																		</select>
                                                                </div>                                                              
                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> NIC: </div>
                                                                    <input name="NIC" id="NIC" type="text" class="form-control" placeholder="NIC" onblur="nicValidator()" value="<?php echo  $employee->getNIC() ?>">   
                                                                </div>
                                                                <span id="confirmNIC" style="color:#D80027; font-style:italic; "></span>
                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> Date of Birth : </div>
                                                                    <input name="dob" id="dob" type="text" class="form-control" placeholder="(DD-MM-YYYY)"  min="1950-01-01" onfocus="(this.type='date')" onblur="(this.type='text')" value="<?php echo $employee->getDOB()  ?>"> 
                                                                </div> 
                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> EMAIL: </div>
                                                                    
                                                                    <input name="email" id='email' type="email" class="form-control" placeholder="Email" value="<?php echo $employee->getMail() ?>">
                                                                </div>

                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; color: #AAAAAA; width:25%;">Phone Number : </div>
                                                                    <input name="mobileNo" id='mobileNo' type="text" placeholder="0777777777" onblur="phoneValidator()"  class="form-control" value="<?php echo $employee->getmobileNo() ?>">
                                                                    
                                                                </div>
                                                                    <span id="confirmTel" style="color:#D80027; font-style:italic;"></span>

                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> Address : </div>
                                                                    <input name="address" id='address' type="text" class="form-control" placeholder="Address" value="<?php echo  $employee->getAddress() ?>">
                                                                </div> 
                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> Designation : </div>
                                                                    
                                                                    <select name="job" id="job" class="form-control" readonly>
																		<option value="head_manager"  disabled >Head Manager</option>
																		<option value="manager" disabled>Manager</option>
																		<option value="staff" selected>Staff</option>
																	</select>
                                                                </div>
                                                        
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="payment-adress">
                                                                    <button type="submit" name='updateManagerBtn' id="updateManagerBtn" class="btn btn-primary waves-effect waves-light" style="width:100%; margin-bottom:50px;">Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                            </form> 
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="payment-adress">
                                                        <button type="submit" name='updateBtn' id="updateBtn" onclick="managerUpdator()" class="btn btn-primary waves-effect waves-light" style="width:100%; background-color:green;">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <?php

                                    if(isset($_POST['updateManagerBtn'])){

                                        $fullname = $_POST['fullname'];
                                        $address  = $_POST['address'];
                                        $phoneNo  = $_POST['mobileNo']; 
                                        $DOB      = $_POST['dob'];
                                        $nic      = $_POST['NIC'];
                                        $branchCode = $_POST['branchCode'];
                                        $email    = $_POST['email'];
                                        $id = $_POST['id'];

                                        
                                        echo $loginedUser->updateStaff( $_SESSION['W_id'], $fullname, $address, $phoneNo, $DOB, $nic, $branchCode, $email,'staff');          
                                        
                                        
                                       
                                    }

                                    ?>
                            </div>

                           

                            <div class="product-tab-list tab-pane fade" id="reviews">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <form id="acount-infors" action='#' class="acount-infor" method="POST">
                                                <div class="devit-card-custom">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="id" placeholder="ID" required>
                                                    </div>

                                                    <div class="form-group" style="display:flex; " >
                                                        <input name="password"  id="password" type="password" class="form-control" placeholder="New Password" required>
                                                        <i class="far fa-eye icon" style="margin-left:-30px; margin-top:auto; margin-bottom:auto; color:#AAAAAA; cursor:pointer;" onclick="passwordVisibler()" id="togglePassword" ></i>
                                                    </div>
                                                    <div class="form-group" >
                                                        <input name="confirmpassword" id="confirmpassword" type="password" class="form-control" placeholder="Confirm Password" onblur="validator()" required>
                                                        <span id="confirmPwd" style="color:#D80027; font-style:italic;"></span>
                                                    </div> 

                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="payment-adress">
                                                            <button type="submit" name='changePasswordBtn' id="changePasswordBtn" class="btn btn-primary waves-effect waves-light" style='width:100%; margin-bottom:50px;' >Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </form>
                                        <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="payment-adress">
                                                        <button type="submit" name='updatePasswordBtn' onclick="passwordUpdator()" id="updatePasswordBtn" class="btn btn-primary waves-effect waves-light" style="width:100%; background-color:green;">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <?php
                                
                                    if(isset($_POST['changePasswordBtn'])){

                                        $id = $_POST['id'];
                                        
                                        $password = $_POST['password'];
                                        $confirmPassword = $_POST['confirmpassword'];

                                        if ($id == $_SESSION['W_id']){    
                                            echo $loginedUser->updatePassword($_SESSION['W_id'],$password,$confirmPassword);
                                        }else{
                                            echo CHECKID;
                                        }

                                    }

                                ?>
                            
                            
                            
                            </div>

                          


                            <div class="product-tab-list tab-pane fade" id="INFORMATION">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <div class="row">
                                                <form id="acount-left" action='#' class="acount-infor" method="POST">
                                                    <div class="devit-card-custom">
                                                       
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="id" placeholder="ID">
                                                        </div>

                                                        

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="payment-adress">
                                                                <button type="submit" name='leftBtn' id="leftBtn" class="btn btn-primary waves-effect waves-light" style='width:100%; margin-bottom:50px;' >Left</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="payment-adress">
                                                        <button type="submit" name='updateLeftBtn' onclick="leftUpdator()" id="updateLeftBtn" class="btn btn-primary waves-effect waves-light" style="width:100%; background-color:green;">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                            
                                    <?php

                                        if(isset($_POST['leftBtn'])){

                                            $id = $_POST['id'];
                                            
                                            if($id == $_SESSION['W_id']){
                                                echo $loginedUser->updateleftDate($_SESSION['W_id']);
                                            }else{
                                                echo CHECKID;
                                            }
                                            
                                                                            
                                        }
                                    ?>
                            
                            
                            </div>

                            


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include("../layout/footer.php");
        ?>

    <script>

        const btn = document.getElementById('updateManagerBtn');


        function validator(){
            var btnp =document.getElementById('changePasswordBtn');
            var confirm = document.getElementById('confirmpassword').value;

            if(document.getElementById('password').value != confirm){

                document.getElementById('confirmPwd').innerHTML = 'Passwords are not matching';
                btnp.disabled = true;
            }else{
                
                document.getElementById('confirmPwd').innerHTML = '';
                btnp.disabled = false;
            }
        }


        function passwordVisibler(){
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const type = (password.type === 'password')? 'text':'password';
            password.type=type;
            
            togglePassword.classList.toggle('fa-eye-slash');
        }


        function nicValidator(){
            var pattern_1 = "^[0-9]{12}$";
            var pattern_2 = "^[0-9]{9}(V|v)$";
            var nic = document.getElementById('NIC').value;
            if ((nic.search(pattern_1) !=-1) | nic.search(pattern_2) !=-1){
                document.getElementById('confirmNIC').innerHTML ="";
                btn.disabled = true;
            }else{
                document.getElementById('confirmNIC').innerHTML ="NIC doesn't matches the format";
                btn.disabled = false;
            }

        }


        function phoneValidator(){
            var phone = document.getElementById('mobileNo').value;
            
            if(phone.search('^[0-9]+$')==-1){
                document.getElementById('confirmTel').innerHTML ="Phone Number doesn't matches the format";
                btn.disabled = true;
                
            }else{
                document.getElementById('confirmTel').innerHTML ="";
                btn.disabled = false;
            }
        }



        function managerUpdator(){
            var file = document.getElementById('demo1-upload');
            var btn  = document.getElementById('updateBtn');
            var content =btn.textContent;
            Updator(file,btn);
            if (content =="Cancel"){
                window.location.reload();
            }
        }

        function passwordUpdator(){
            var file = document.getElementById('acount-infors');
            var btn  = document.getElementById('updatePasswordBtn');
            var content =btn.textContent;
            Updator(file,btn);
            if (content =="Cancel"){
                Array.from(file.elements).forEach(node)
            }

        }

        function leftUpdator(){
            var file = document.getElementById('acount-left');
            var btn  = document.getElementById('updateLeftBtn');
            var content =btn.textContent;
            Updator(file,btn);
            if (content =="Cancel"){
                Array.from(file.elements).forEach(node);
            }
            
            
        }

        function node(v){
            if(v.nodeName == 'INPUT'){
                    v.value="";
                }
        }

        function Updator(file,btn){
            var form = file;
            var val  = btn.textContent;
            var elements = form.elements;
            
            if (val=="Cancel"){
                Array.from(elements).forEach(v => v.disabled = true);
                btn.textContent = "Update";
                btn.style.backgroundColor ="green";

            }else{
                Array.from(elements).forEach(v => v.disabled = false);
                btn.textContent = "Cancel";
                btn.style.backgroundColor ="#800000";
                btn.style.border ="none";
            }
        }

        $(document).ready(function(){
            var file_1 = document.getElementById('acount-left');
            var elements_1 = file_1.elements;
            Array.from(elements_1).forEach(v => v.disabled = true);

            var file_2 = document.getElementById('demo1-upload');
            var elements_2 = file_2.elements;
            Array.from(elements_2).forEach(v => v.disabled = true);

            var file_2 = document.getElementById('acount-infors');
            var elements_2 = file_2.elements;
            Array.from(elements_2).forEach(v => v.disabled = true);
            }
        );        

    </script>
