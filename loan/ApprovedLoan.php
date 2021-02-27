<?php

include("../layout/header.php");

use Classess\Auth\Manager;
if(!($loginedUser instanceof Manager)){
    header("location:../error/403.php");
}
?>
<script>
    changeTitle("View Approved Loan | Core Bank");
</script>
            <!-- Mobile Menu end -->
            <div class="breadcome-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="breadcome-list single-page-breadcome">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                        
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <ul class="breadcome-menu">
                                            <li><a href="../home/">Home</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">Approved Loan Table</span>
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
                                    <h1>View <span class="table-project-n">All</span> Approved Loans</h1>
                                </div>       
                                <div class="add-product">
                                <a href="requestLoan.php">Request Loan</a>
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
                                                <th data-field="Loan_ID">Loan_ID</th>
                                                <th data-field="NIC">NIC</th>
                                                <th data-field="Amount" >Amount</th>
                                                <th data-field="I_Plan" >I_Plan-Id</th>
                                                <th data-field="Reason" >Reason</th>                                                
                                                <th data-field="Requested_Date">Requested_Date</th>                                
                                                <th data-field="Duration">Duration</th>                                                                                                             
                                                <th data-field="Show">Show More</th>                                                                                                             
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $loginedUser->ViewAllApprovedLoans() ?>                                 
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