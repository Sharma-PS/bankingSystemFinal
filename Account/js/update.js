function changeStatus(id) {
    var tg = "tg-".concat(id);
    var btn = "st-".concat(id);
    var icn = "ic-".concat(id);

    var val_1 = document.getElementById(tg);
    var val_2 = document.getElementById(btn);
    var val_3 = document.getElementById(icn);


    $(document).ready(function() {

        if (val_2.className === "fa fa-check") {
            val_2.className = "fa fa-ban";
            // val_2.style.backgroundColor = '#CC2020';
            val_3.setAttribute('class', 'fa fa-toggle-off');
            val_1.style.backgroundColor = '#CC2020';;

            // console.log(id);
            $.ajax({
                type : "POST",
                url : "update.php",
                data : {ID : id, status : '0'},
                success: function (html) {
                    console.log(html);
                }
            });

        }else{
            val_2.className = "fa fa-check";
            // val_2.style.backgroundColor = "#40BF36";;
            val_3.setAttribute('class', 'fa fa-toggle-on');
            val_1.style.backgroundColor = "#40BF36";;

            $.ajax({
                type : "POST",
                url : "update.php",
                data : {ID : id, status : '1'}
            });
        }

    });

}