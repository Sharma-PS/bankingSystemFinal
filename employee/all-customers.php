
<?php
  include("../layout/header.php");
  use Classess\Auth\Staff;
  if(!($loginedUser instanceof Staff)){
      header("location:../error/403.php");
  }
  if (isset($_POST['edit'])){
      $_SESSION['nic']=$_REQUEST['edit'];
      header("location: edit-customer.php");
  }
?>
<script>
    changeTitle("All Customer | Core Bank");
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
                                            <li><span class="bread-blod">Customers Details</span>
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
        <!-- Static Table Start -->
        <div class="data-table-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list">
                            <div class="sparkline13-hd">
                                <div class="main-sparkline13-hd">
                                    <h1>Customer <span class="table-project-n">Details</span></h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                    <div id="toolbar">
                                      <select class="form-control dt-tb">
                                      <option value="">Export Basic</option>
                                      <option value="all">Export All</option>
                                      <option value="selected">Export Selected</option>
                                    </select>
                                    </div>
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="nic">NIC</th>
                                                <th data-field="name" >Name</th>
                                                <th data-field="email" >Email</th>
                                                <th data-field="phone" >Phone</th>
                                                <th data-field="tempAddress" >Temporary Address</th>
                                                <th data-field="permanentAddress" >Permanent Address</th>
                                                <th data-field="job" >Job</th>
                                                <th data-field="officialAddress" >Official Address</th>
                                                <th data-field="dob" >Date of Birth</th>
                                                <th data-field="dp" >DP</th>
                                                <th data-field="openedBy" >Opened By</th>
                                                <th data-field="openedBranch" >Branch</th>
                                                <th data-field="joinedDate" >Joined Date</th>
                                                <th data-field="updatedDate" >Updated Date</th>
                                                <th data-field="status">Status</th>
                                                <th data-field="edit"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                          
                                            $upload_dir = "../img/profile/";                                            
                                            $all_customers=$loginedUser->showCustomers();
                                            foreach ($all_customers as $data){
                                              $data['dp'] = ($data['dp']) ? ($data['dp']) : "customerAvatar.png";
                                        ?>
                                        
                                            <tr>
                                                <td><?php echo $data['NIC'] ?></td>
                                                <td><?php echo $data['name'] ?></td>
                                                <td><?php echo $data['eMail'] ?></td>
                                                <td><?php echo $data['mobileNo'] ?></td>
                                                <td><?php echo $data['tempAddress'] ?></td>
                                                <td><?php echo $data['permanantAddress'] ?></td>
                                                <td><?php echo $data['job'] ?></td>
                                                <td><?php echo $data['officialAddress'] ?></td>
                                                <td><?php echo $data['DOB'] ?></td>
                                                <td><?php echo "<img src='".$upload_dir.$data['dp']."' width='70px' height='70px'>" ?></td>
                                                <td><?php echo $data['openedBy'] ?></td>
                                                <td><?php echo $data['openedBranch'] ?></td>
                                                <td><?php echo $data['joinedDate'] ?></td>
                                                <td><?php echo $data['updatedDate'] ?></td>
                                                <td ><?php if ($data['leftDate']==null){echo "Active";}
                                                        else {echo "Deactivated";}
                                                    ?>
                                                </td>
                                                <td><form action="" method="POST"><button type="submit" name="edit" value=<?php echo $data['NIC'] ?> class="btn btn-primary waves-effect waves-light">Edit</button></form></td>
                                            </tr>

                                        <?php 
                                            }
                                        ?>
                                        </tbody>
                                    </table>
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