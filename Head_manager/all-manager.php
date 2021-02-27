<?php
    include("../layout/header.php");
    $_SESSION['W_id'] = NULL;
?>
<script>
    changeTitle("All Managers | Core Bank");
</script>
           
            <div class="breadcome-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="breadcome-list">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="breadcome-heading">
                                            <form role="search" class="sr-input-func">
                                                <input type="text" placeholder="Search..." class="search-int form-control" id='searchKey' onclick="refreshFunction()">
                                                <a href="#" onclick="searchFunction()"><i class="fa fa-search"></i></a>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <ul class="breadcome-menu">
                                            <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">All Professors</span>
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
        <div class="contacts-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                <?php
                    echo $loginedUser->getAllManagers();
                ?>

                </div>
            </div>
        </div>
        

        <?php
            include("../layout/footer.php");
        ?>
        
    <!-- Search JS
		============================================ -->

    <script>
        function searchFunction(){

            var elems = document.getElementsByClassName('search');
            var key = document.getElementById('searchKey').value.toLowerCase();

            
            for(let item of elems) {
                var data_1 = item.getElementsByTagName('p')[0].childNodes[0].data.toLowerCase();
                
                if (data_1.search(key) == -1){
                    
                    var data_2 = item.getElementsByTagName('p')[1].textContent.toLowerCase();
                    
                    if(data_2.search(key)== -1){
                        
                        var data_3 = item.getElementsByClassName('social-media-in')[0].childNodes[0].data.toLowerCase();
                        
                        if(data_3.search(key)== -1){
                            
                            var data_4 = item.getElementsByClassName('managerName')[0].textContent.toLowerCase();
                            
                            if(data_4.search(key)== -1){
                                item.style.display='none';
                            }
                        }
                    }
                }
                

          
            } 
            
        }

        function refreshFunction(){
            var elems = document.getElementsByClassName('search');
            document.getElementById('searchKey').value = '';

            for(let item of elems) {
  
                item.style.display='unset';

          
            } 
        }
        

    </script>

</body>

</html>