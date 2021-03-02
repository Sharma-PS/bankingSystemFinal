<!doctype html>
<?php
ob_start();
session_start();
require "../loginCheck.php";

use Classess\Auth\Customer;
use Classess\Auth\HeadManager;
use Classess\Auth\Manager;
use Classess\Auth\Staff;
?>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard | Core Bank</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="../css/owl.carousel.css">
    <link rel="stylesheet" href="../css/owl.theme.css">
    <link rel="stylesheet" href="../css/owl.transitions.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="../css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="../css/normalize.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="../css/meanmenu.min.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="css/alerts.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="../css/main.css">
    <!-- educate icon CSS
		============================================ -->
    <link rel="stylesheet" href="../css/educate-custon-icon.css">
    <!-- morrisjs CSS
		============================================ -->
    <link rel="stylesheet" href="../css/morrisjs/morris.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="../css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="../css/metisMenu/metisMenu.min.css">
    <link rel="stylesheet" href="../css/metisMenu/metisMenu-vertical.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="../css/data-table/bootstrap-table.css">
    <link rel="stylesheet" href="../css/data-table/bootstrap-editable.css">
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href="../css/calendar/fullcalendar.min.css">
    <link rel="stylesheet" href="../css/calendar/fullcalendar.print.min.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="../style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="../css/responsive.css">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="../css/form/all-type-forms.css">
    <!-- modernizr JS
		============================================ -->
    <script src="../js/vendor/modernizr-2.8.3.min.js"></script>
    <script src="../js/changeTitle.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <!-- Start Header menu area -->
    <div class="left-sidebar-pro">
        <nav id="sidebar" class="">
            <div class="sidebar-header">
                <a href="index.php"><img class="main-logo" src="../img/logo/logosn.png" alt="logo" width="50px"/></a>
                <strong><a href="index.php"><img src="../img/logo/logosn.png" alt="logo" /></a></strong>
            </div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
                <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1">
                                                
                        <?php
                            if ($loginedUser instanceof Customer) {                                                        
                        ?>
                        <li>
                                <a  href="../customer/accounts.php">
								   <span class="educate-icon educate-home icon-wrap"></span>
								   <span class="mini-click-non">Accounts</span>
								</a>
                        </li>
                        
                        <li>
                            <a href="../customer/transaction.php" aria-expanded="false"><span class="educate-icon educate-professor icon-wrap"></span> <span class="mini-click-non">Online Transaction</span></a>
                           
                        </li>
                        <li class="active">
                            <a class="has-arrow" aria-expanded="false"><span class="educate-icon educate-library icon-wrap"></span> <span class="mini-click-non">Loans</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="New Loan" href="../customer/new-loan.php"><span class="mini-sub-pro">Get New Loan</span></a></li>                                
                                <li><a title="My Loan" href="../customer/myLoan.php"><span class="mini-sub-pro">My Loans</span></a></li>
                                <li><a title="Payment" href="../customer/pay-loan.php"><span class="mini-sub-pro">Pay Installment</span></a></li>
                            </ul>
                        </li>                        
                        <?php
                            }
                            if ($loginedUser instanceof Staff) {
            
                        ?>
                        <li>
                            <a class="has-arrow" href="all-courses.php" aria-expanded="false"><span class="educate-icon educate-library icon-wrap"></span> <span class="mini-click-non">Account</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Add account" href="../Account/addAccount.php"><span class="mini-sub-pro">Add Account</span></a></li>
                                <li><a title="View All Account" href="../Account/viewAccount.php"><span class="mini-sub-pro">View All Account</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="all-courses.php" aria-expanded="false"><span class="educate-icon educate-professor icon-wrap"></span> <span class="mini-click-non">Customer</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="View All Account" href="../employee/all-customers.php"><span class="mini-sub-pro">All Customer</span></a></li>
                                <li><a title="Add account" href="../employee/add-customer.php"><span class="mini-sub-pro">Add Customer</span></a></li>                                
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="mailbox.php" aria-expanded="false"><span class="educate-icon educate-interface icon-wrap"></span> <span class="mini-click-non">Transacation</span></a>
                            <ul class="submenu-angle interface-mini-nb-dp" aria-expanded="false">                                
                                <li><a title="Depositr" href="../Transaction/deposit.php"><span class="mini-sub-pro">Deposit</span></a></li>
                                <li><a title="Withdraw" href="../Transaction/withdrawal.php"><span class="mini-sub-pro">Withdraw</span></a></li>
                                <li><a title="TransferMoney" href="../Transaction/MoneyTransfer.php"><span class="mini-sub-pro">TransferMoney</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="mailbox.php" aria-expanded="false"><span class="educate-icon educate-form icon-wrap"></span> <span class="mini-click-non">View Transaction</span></a>
                            <ul class="submenu-angle form-mini-nb-dp" aria-expanded="false">
                                <li><a title=">View Deposits" href="../Transaction/viewDeposit.php"><span class="mini-sub-pro">View Deposits</span></a></li>                                
                                <li><a title=">View Withdraws" href="../Transaction/viewWithdrawal.php"><span class="mini-sub-pro">View Withdrews</span></a></li>                                
                                <li><a title=">View Transaction" href="../Transaction/viewMoneyTransfer.php"><span class="mini-sub-pro">View Transfer</span></a></li>                                
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="mailbox.php" aria-expanded="false"><span class="educate-icon educate-data-table icon-wrap"></span> <span class="mini-click-non">Fixed Deposit</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Create FD" href="../FD/createFD.php"><span class="mini-sub-pro">Create FD</span></a></li>
                                <li><a title="All FD" href="../FD/AllFD.php"><span class="mini-sub-pro">All FD</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="mailbox.php" aria-expanded="false"><span class="educate-icon educate-data-table icon-wrap"></span> <span class="mini-click-non">Loan</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Request Loan" href="../loan/requestLoan.php"><span class="mini-sub-pro">Request a Loan</span></a></li>
                                <?php
                                    if ($loginedUser instanceof Manager) {                    
                                ?>
                                <li><a title="Approved Loan" href="../loan/pendingLoan.php"><span class="mini-sub-pro">Pending Loan</span></a></li>
                                <li><a title="Approved Loan" href="../loan/ApprovedLoan.php"><span class="mini-sub-pro">Approved Loan</span></a></li>
                                <li><a title="Approved Loan" href="../loan/rejectedLoan.php"><span class="mini-sub-pro">Rejected Loan</span></a></li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </li>
                        <li><a href="../installment/installmentPayment.php"><span class="educate-icon educate-form icon-wrap"></span> <span class="mini-click-non">Payments</span></a></li>
                        <?php
                            }
                            if ($loginedUser instanceof Manager){
                        ?>
                        <li>
                            <a class="has-arrow" href="all-professors.php" aria-expanded="false"><span class="educate-icon educate-professor icon-wrap"></span> <span class="mini-click-non">Staffs</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="All Staff" href="../manager/all-staff.php"><span class="mini-sub-pro">All Staffs</span></a></li>
                                <li><a title="Add Staff" href="../manager/add-staff.php"><span class="mini-sub-pro">Add Staff</span></a></li>                               
                            </ul>
                        </li> 
                        <li>
                            <a class="has-arrow" href="all-professors.php" aria-expanded="false"><span class="educate-icon educate-event icon-wrap sub-icon-mg" aria-hidden="true"></span> <span class="mini-click-non">Reports</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Late Loan Installmet" href="../report/lateLoanInstallment.php"><span class="mini-sub-pro">Late Loan Installment</span></a></li>
                                <li><a title="MOnthly Report" href="../report/monthly Report.php"><span class="mini-sub-pro">Monthly Report</span></a></li>
                                <li><a title="Annual Report" href="../report/annual Report.php"><span class="mini-sub-pro">Annual Report</span></a></li>                               
                            </ul>
                        </li>                        

                        <?php
                            }
                            if ($loginedUser instanceof HeadManager) {
                        ?>
                        <li>
                            <a class="has-arrow" href="mailbox.php" aria-expanded="false"><span class="educate-icon educate-charts icon-wrap"></span> <span class="mini-click-non">Plans</span></a>
                            <ul class="submenu-angle chart-mini-nb-dp" aria-expanded="false">
                                <li><a title="Bar Charts" href="../Plan/savingPlan.php"><span class="mini-sub-pro">Saving Plans</span></a></li>
                                <li><a title="Line Charts" href="../Plan/FDplan.php"><span class="mini-sub-pro">FD Plans</span></a></li>                                
                                <li><a title="Line Charts" href="../Plan/loanPlan.php"><span class="mini-sub-pro">Loan Interest Plans</span></a></li>                                
                            </ul>
                        </li>
                        <!-- Branches sidebar-->
                        <li>
                            <a class="has-arrow" href="all-courses.php" aria-expanded="false"><span class="educate-icon educate-department icon-wrap"></span> <span class="mini-click-non">Branches</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Branches List" href="../Branch/branch.php"><span class="mini-sub-pro">Branches List</span></a></li>
                                <li><a title="Add Branches" href="../Branch/add-branch.php"><span class="mini-sub-pro">Add Branch</span></a></li>
                            </ul>
                        </li>
                        <!-- Branches sidebar-->

                        <li>
                            <a class="has-arrow" href="all-professors.php" aria-expanded="false"><span class="educate-icon educate-professor icon-wrap"></span> <span class="mini-click-non">Managers</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="All Staff" href="../Head_manager/all-manager.php"><span class="mini-sub-pro">All Managers</span></a></li>
                                <li><a title="Add Staff" href="../Head_manager/add-manger.php"><span class="mini-sub-pro">Add Manager</span></a></li>                               
                            </ul>
                        </li>
                        <?php
                            }
                        ?>                        
                    </ul>
                </nav>
            </div>
        </nav>
    </div>
        <!-- End Header menu area -->
    <!-- Start Welcome area -->
    <div class="all-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="index.php"><img class="main-logo" src="../img/logo/logo.png" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-advance-area">
            <div class="header-top-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-top-wraper">
                                <div class="row">
                                    <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                                        <div class="menu-switcher-pro">
                                            <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
													<i class="educate-icon educate-nav"></i>
												</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                                        <div class="header-top-menu tabl-d-n">
                                            <ul class="nav navbar-nav mai-top-nav">
                                                <li class="nav-item"><a href="#" class="nav-link">Home</a>
                                                </li>                                                                                                                    
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                        <div class="header-right-info">
                                            <ul class="nav navbar-nav mai-top-nav header-right-menu">
                                                <!--li class="nav-item dropdown">
                                                    <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="educate-icon educate-message edu-chat-pro" aria-hidden="true"></i><span class="indicator-ms"></span></a>
                                                    <div role="menu" class="author-message-top dropdown-menu animated zoomIn">
                                                        <div class="message-single-top">
                                                            <h1>Message</h1>
                                                        </div>
                                                        <ul class="message-menu">
                                                            <li>
                                                                <a href="#">
                                                                    <div class="message-img">
                                                                        <img src="../img/contact/1.jpg" alt="">
                                                                    </div>
                                                                    <div class="message-content">
                                                                        <span class="message-date">16 Sept</span>
                                                                        <h2>Advanda Cro</h2>
                                                                        <p>Please done this project as soon possible.</p>
                                                                    </div>
                                                                </a>
                                                            </li>                                                                                                                                                                    
                                                        </ul>
                                                        <div class="message-view">
                                                            <a href="#">View All Messages</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="educate-icon educate-bell" aria-hidden="true"></i><span class="indicator-nt"></span></a>
                                                    <div role="menu" class="notification-author dropdown-menu animated zoomIn">
                                                        <div class="notification-single-top">
                                                            <h1>Notifications</h1>
                                                        </div>
                                                        <ul class="notification-menu">
                                                            <li>
                                                                <a href="#">
                                                                    <div class="notification-icon">
                                                                        <i class="educate-icon educate-checked edu-checked-pro admin-check-pro" aria-hidden="true"></i>
                                                                    </div>
                                                                    <div class="notification-content">
                                                                        <span class="notification-date">16 Sept</span>
                                                                        <h2>Advanda Cro</h2>
                                                                        <p>Please done this project as soon possible.</p>
                                                                    </div>
                                                                </a>
                                                            </li>                                                                                                                   
                                                        </ul>
                                                        <div class="notification-view">
                                                            <a href="#">View All Pending Loan</a>
                                                        </div>
                                                    </div>
                                                </li-->
                                                <li class="nav-item">
                                                    <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
															<img src="../<?php echo $loginedUser->getDp()?>" alt="" />
															<span class="admin-name"><?php echo $loginedUser->getFname(); ?></span>
															<i class="fa fa-angle-down edu-icon edu-down-arrow"></i>
														</a>
                                                    <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">                                                                                                                
                                                        <?php
                                                            if($loginedUser instanceof Customer){
                                                        ?>   
                                                        <li><a href="../customer/myProfile.php"><span class="edu-icon edu-user-rounded author-log-ic"></span>My Profile</a>
                                                        </li>                                                     
                                                        <li><a href="../customer/changePass.php"><span class="edu-icon edu-settings author-log-ic"></span>Change Password</a>
                                                        </li>
                                                        <?php
                                                            }elseif ($loginedUser instanceof Staff) {                                                                                                                                              
                                                        ?>
                                                        <li><a href="../employee/myProfile.php"><span class="edu-icon edu-user-rounded author-log-ic"></span>My Profile</a>
                                                        <li><a href="../employee/changePass.php"><span class="edu-icon edu-settings author-log-ic"></span>Change Password</a>
                                                        <?php
                                                            }
                                                        ?>
                                                        <li><a href="../logout.php"><span class="edu-icon edu-locked author-log-ic"></span>Log Out</a>
                                                        </li>
                                                    </ul>
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
            <!-- Mobile Menu start -->
            <div class="mobile-menu-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="mobile-menu">
                                <nav id="dropdown">
                                    <ul class="mobile-menu-nav">                                       
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu end -->