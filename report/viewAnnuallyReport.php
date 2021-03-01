
<?php 
   ob_start();
   session_start();
   require "../loginCheck.php";
   use Classess\Auth\Manager;
use Includes\Report\AnnualReport;

   if(!($loginedUser instanceof Manager)){
        header("location:../error/404.php");
    }
    
    $report = $loginedUser->getAnnualReport($_GET["id"]);
    if(!($report instanceof AnnualReport)){
      header("location:../error/403.php");
    }
   
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Annual Report | Core Bank</title>
  <link rel="stylesheet" href="../css/styleInvoice.css">
   <script src="../js/scriptInvoice.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <style type="text/css" media="print">
   .dontprint
   { display: none; }
   </style>
</head>
<body>

<div class="wrapper" id="invoice">
   <div class="container">
      <p class="thanks"> Your Annual Report is Ready!</p>

      <button class="cta"> Print Report</button>
      <div class="receipt" >
         <div class="receipt__message">
            <h2 class="receipt__title">Annual Report!</h2>
            <p class="receipt__text">
               Your Report <strong># <?php ?></strong> has been successfully Generated.
               Please check all the data so that everything is correct!
               <span id="reciept">
            </p>
            <a class="btn dontprint" href="annual Report.php">View All Annual Report</a>
         </div>

         <div class="product">
            <figure class="product__image">
               <img src="../img/report/images.png" alt="Train Images">
            </figure>
            <div>
               <h3 class="product__name">Report Id - <?php echo $report->getId();?> / Branch <?php echo $report->getBranchCode()?></h3>
               <p class="product__quantity">Year <?php echo $report->getYear()?></p>
               <p class="product__quantity">Active Customers:-<?php echo $report->getActivCus()?></p>
               <p class="product__quantity">Active Employees:-<?php echo $report->getActEmp()?></p>
               <p class="product__quantity">
                  Date - <?php echo $report->getGeneratedOn()?>
               </p>
            </div>

         </div>

         <div class="price">
            <div class="price__pricing">
               <p class="price__princingTitle">
                  Total Deposit
               </p>
               <p class="price__princingNumber">
                  <?php echo $report->getTotalDeposit()?>
               </p>
            </div>         
            <div class="price__total">
               <p class="price__totalTitle">
                  Total Deposit Amount
               </p>
               <p class="price__totalNumber">
                  R.s <?php echo $report->getTotDepAmo()?>
               </p>
            </div>
         </div>

         <div class="price">
            <div class="price__pricing">
               <p class="price__princingTitle">
                  Total WithDrawals
               </p>
               <p class="price__princingNumber">
                  <?php echo $report->getTotW()?>
               </p>
            </div>         
            <div class="price__total">
               <p class="price__totalTitle">
                  Total WithDrawals
               </p>
               <p class="price__totalNumber">
                  R.s <?php echo $report->getTotWA()?>
               </p>
            </div>
         </div>

         <div class="price">
            <div class="price__pricing">
               <p class="price__princingTitle">
                  Total Transaction
               </p>
               <p class="price__princingNumber">
                  <?php echo $report->getTotTra()?>
               </p>
            </div>         
            <div class="price__total">
               <p class="price__totalTitle">
                  Total Transaction Amount
               </p>
               <p class="price__totalNumber">
                  R.s <?php echo $report->getTotTraAmo()?>
               </p>
            </div>
         </div>

         <div class="price">
            <div class="price__pricing">
               <p class="price__princingTitle">
                  Total Active FD
               </p>
               <p class="price__princingNumber">
                  <?php echo $report->getActFd()?>
               </p>
            </div>         
            <div class="price__total">
               <p class="price__totalTitle">
                  Total Active FD Amount
               </p>
               <p class="price__totalNumber">
                  R.s <?php echo $report->getActFDamo()?>
               </p>
            </div>
         </div>

         <div class="price">
            <div class="price__pricing">
               <p class="price__princingTitle">
                  Total Active Loan
               </p>
               <p class="price__princingNumber">
                  <?php echo $report->getActLoan()?>
               </p>
            </div>         
            <div class="price__total">
               <p class="price__totalTitle">
                  Total
               </p>
               <p class="price__totalNumber">
                  R.s <?php echo $report->getActLoanAmo()?>
               </p>
            </div>
         </div>

         <div class="price">                    
            <div class="price__total">
               <p class="price__totalTitle">
                  No Of Pending Loan Installments
               </p>
               <p class="price__totalNumber">
                  <?php echo $report->getPenIns()?>
               </p>
            </div>
         </div>

         <div class="info">
            <h4 class="info__infoTitle">Manager Data</h4>
            <div class="info__addressContent">
               <div class="info__address">
                  <h5 class="info__addressTitle">Manager details</h5>
                  <p class="info__addressText">
                     Manager ID : <?php echo $loginedUser->getID()?><br>
                     Name: <?php echo $loginedUser->getFname()?><br>
                     Email: <?php echo $loginedUser->getMail()?><br>
                     Contact Number: <?php echo $loginedUser->getmobileNo(); ?>
                  </p>
               </div>
            </div>
            <p style="text-align:center">Checked By</p><br>

            
               <div class="receipt__message">
                  <button class="btn dontprint" onclick="window.print()" id="ignorePDF">Print</button>
               </div>
         </div>
      </div>
   </div>
</div>
</body>
</html>
