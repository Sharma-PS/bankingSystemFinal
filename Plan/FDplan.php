<?php

include("../layout/header.php");

use Classess\Auth\HeadManager;
if(!($loginedUser instanceof HeadManager)){
    header("location:../error/403.php");
}
?>
<script>
    changeTitle("Fixed Deposit Plan | Core Bank");
</script>
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
                                            <li><span class="bread-blod">FD Plan Table</span>
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
        <div class="static-table-area">
            <div class="container-fluid">
                <div class="row">                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline11-list mt-b-30">
                            <div class="sparkline11-hd">
                                <div class="main-sparkline11-hd">
                                    <h1>Fixed Deposit Plans &nbsp;&nbsp;<span data-diameter="40" class="updating-chart">2,5,9,6,5,9,7,3,5,2,5,3,9,6,5,8,7,8,5,2</span></h1> 
                                </div>
                            </div>
                            <div class="sparkline11-graph">
                                <div class="static-table-list">
                                    <table class="table sparkle-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>FD Plan ID</th>
                                                <th>Description</th>
                                                <th>In Months</th>
                                                <th>Interest Rate</th>
                                                <th>New Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                echo $loginedUser->getAllFDPlans(TRUE);                                                                                        
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