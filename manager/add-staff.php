<?php
    include("../layout/header.php");
    use Classess\Auth\Manager;
    if(!($loginedUser instanceof Manager)){
        header("location:../error/403.php");
    }
    $fileDestination=NULL;
    $ID= NULL;
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
                                            <li><span class="bread-blod">Add Staff</span>
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
                                <li class="active"><a href="#description">Basic Information</a></li>
                                <li><a href="#reviews"> Additional Information</a></li>
                                
                            </ul>
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="review-content-section">
                                                <div id="dropzone1" class="pro-ad">

                                                    <form action="" class="add-professors" method="POST" id="demo1-upload" >
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <input name="fullname" id="fullname" type="text" class="form-control" placeholder="Full Name">
                                                                </div>
                                                                
                                                                <div class="form-group" style="display:flex; ">
                                                                    <div class="" style="margin:auto; text-align:center; color: #AAAAAA; width:25%;">Phone Number : </div>
                                                                    <input name="mobileNo" id='mobileNo' type="text" placeholder="0777777777" onblur="phoneValidator()" class="form-control" >
                                                                    
                                                                </div>
                                                                    <span id="confirmTel" style="color:#D80027; font-style:italic;"></span>
                                                                <div class="form-group">
                                                                    <input name="NIC" id="NIC" type="text" class="form-control" placeholder="NIC" onblur="nicValidator()">
                                                                    <span id="confirmNIC" style="color:#D80027; font-style:italic;"></span>
                                                                </div>
                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> Date of Birth : </div>
                                                                    <input name="dob" id="dob" type="text" class="form-control" placeholder="(DD-MM-YYYY)"  min="1950-01-01" onfocus="(this.type='date')" onblur="(this.type='text')">
                                                                    
                                                                </div> 
                                                                <div class="form-group">
                                                                    <input name="email" id='email' type="email" class="form-control" placeholder="Email">
                                                                </div>
                                                                  
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                
                                                            <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> Branch Code : </div>
                                                                    
                                                                    <select name="branchCode" id="branchCode" class="form-control" >
																			<option value="none" selected disabled hidden></option>
																			<?php
                                                                                echo $loginedUser->getBranchCode();
                                                                            ?>
																		</select>
                                                                </div>
                                                            

                                                                <div class="form-group">
                                                                    <input name="address" id='address' type="text" class="form-control" placeholder="Address">
                                                                </div> 

                                                                <div class="form-group" style="display:flex;">
                                                                    <div class="" style="margin:auto; text-align:center; width:25%; color: #AAAAAA;"> Designation : </div>
                                                                    
                                                                    <select name="job" id="job" class="form-control" readonly>
																		<option value="head_manager"  disabled >Head Manager</option>
																		<option value="manager" disabled>Manager</option>
																		<option value="staff" selected>Staff</option>
																	</select>
                                                                </div>
                                                                
                                                                <div class="form-group" style="display:flex; " >
                                                                    <input name="password"  id="password" type="password" class="form-control" placeholder="Password">
                                                                    <i class="far fa-eye icon" style="margin-left:-30px; margin-top:auto; margin-bottom:auto; color:#AAAAAA; cursor:pointer;" onclick="passwordVisibler()" id="togglePassword" style="cursor:pointer!important; "></i>
                                                                </div>

                                                                <div class="form-group" >
                                                                    <input name="confirmpassword" id="confirmpassword" type="password" class="form-control" placeholder="Confirm Password" onblur="validator()">
                                                                    <span id="confirmPwd" style="color:#D80027; font-style:italic;"></span>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="payment-adress">
                                                                    <button type="submit" name='addManagerBtn' id="addManagerBtn" class="btn btn-primary waves-effect waves-light" >Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                <?php
                                                    if(isset($_POST['addManagerBtn'])){
                                                        
                                                       $fullname = $_POST['fullname'];
                                                       $address  = $_POST['address'];
                                                       $phoneNo  = $_POST['mobileNo']; 
                                                       $DOB      = $_POST['dob'];
                                                       $nic      = $_POST['NIC'];
                                                       $branchCode = $_POST['branchCode'];
                                                       $email    = $_POST['email'];
                                                       $password = $_POST['password'];
                                                    
                                                       $ID = $loginedUser->addStaff($fullname,$address,$phoneNo,$DOB,$nic,$branchCode,$email,$password,$fileDestination,'staff');
                                                        
                                                       if(!is_int($ID)){
                                                           echo $ID;
                                                           $ID = NULL; 

                                                       }else{ 
                                                           echo SUCCESSFULINSERT;
                                                        }
                                                    
                                                    }
                                                ?>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-tab-list tab-pane fade" id="reviews">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <div class="review-content-section">
                                                <div class="row">
                                                <form id="image-infor" action="#" class="acount-infor"enctype="multipart/form-data" method="POST">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        
                                                            <div class="devit-card-custom">
                                                                <div class="form-group">
                                                                    <input name="ID" type="text" class="form-control" placeholder="ID" value="<?php echo $ID ?>" required>
                                                                </div>

                                                                <div class="file-upload-inner ts-forms" style="width:50%;">
                                                                    <div class="input prepend-big-btn">
                                                                        <label class="icon-right" for="prepend-big-btn">
                                                                                <i class="fa fa-download"></i>
                                                                            </label>
                                                                        <div class="file-button">
                                                                            Browse
                                                                            <input type="file" name="file" id="fileID">
                                                                        </div>
                                                                        <input type="text" id="prepend-big-btn" placeholder="no file selected">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row" style="margin:50px;">
                                                                    <div class="col-lg-12">
                                                                        <div class="payment-adress">
                                                                            <button type="submit" name="fileBtn" id="fileBtn" class="btn btn-primary waves-effect waves-light" style='width:100%; margin-bottom:50px;' >Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                       
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="profile-img">
                                                <img src="../img/profile/1.jpg" alt="" id="previewImage"/>
                                            </div>
                                            <div class="profile-details-hr" style="text-align:center;">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="address-hr" >
                                                            <p>
                                                                Preview Image
                                                                
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                                if(!empty($_FILES)){
  
                                   

                                    $fileName = $_FILES['file']['name'];
                                    $fileTmpName = $_FILES['file']['tmp_name'];
                                    $fileSize = $_FILES['file']['size'];
                                    $fileError = $_FILES['file']['error'];
                                    $fileType = $_FILES['file']['type'];
                                    
                                    $ext =explode('.',$fileName);
                                    $fileExt = strtolower(end($ext));
                                    $allowedExt = array('jpg','jpeg','png');
                                    
                                    if (in_array($fileExt,$allowedExt)){
                                        if($fileError ===0){
                                            if($fileSize<=2000000){
                                                $fileUniqueName = "../img/profile/".uniqid('',true).".".$fileExt;
                                                $fileDestination = $fileUniqueName;
                                                
                                            
                                                
                                            }else{
                                                echo FILESIZEBIG;
                                            }
                                        }else{
                                            echo UPLOADERROR;
                                        }
                                    }else{
                                        echo INVALIDTYPE;
                                    } 
                                }


                                if(isset($_POST['fileBtn']) & ($fileDestination !=NULL)){
                                    $id = $_POST['ID'];
                                    $dp = $fileUniqueName;

                                    echo $loginedUser->uploadDP($id,$dp,$fileTmpName,$fileDestination);
                                    
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


        const btn = document.getElementById('addManagerBtn');

        function validator(){
            
            var confirm = document.getElementById('confirmpassword').value;

            if(document.getElementById('password').value != confirm){

                document.getElementById('confirmPwd').innerHTML = 'Passwords are not matching';
                btn.disabled = true;
            }else{
                
                document.getElementById('confirmPwd').innerHTML = '';
                btn.disabled = false;
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
        
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#fileID").change(function(){
            readURL(this);
            document.getElementById('prepend-big-btn').value = document.getElementById('fileID').value.split('\\').pop(); 
        });



        </script>

