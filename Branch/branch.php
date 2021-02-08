
            <?php
            include("../layout/header.php");            
            use Classess\Auth\HeadManager;
                if(!($loginedUser instanceof HeadManager)){
                    header("location:../error/403.php");
                }
                $result = $loginedUser->branchList();
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
                                            <li><span class="bread-blod">Branches List</span>
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
        <div class="data-table-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list">
                            <div class="sparkline13-hd">
                                <div class="main-sparkline13-hd">
                                    <h1>Branches <span class="table-project-n">Details</span> Table</h1>
                                </div>
                                <div class="add-product">
                                    <a href="add-branch.php">Add Branch</a>
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
                                        <th data-field="state" data-checkbox="true"></th>
                                        <th data-field="id">Id</th>
                                        <th data-field="NameOfBranch">Name_of_Branch</th>
                                        <th data-field="Branch">Branch_Code</th>
                                        <th data-field="Status">Status</th>
                                        <th data-field="Type">Type</th>
                                        <th data-field="Address">Address</th>
                                        <th data-field="Contact">Contact</th>
                                        <th data-field="Opened">Opened_Date</th>
                                        <th data-field="Setting">Setting</th>
                                        <th data-field="Details">To_More_Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        
                                        echo $result;
                                    
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