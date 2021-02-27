function rate(obj) {
    var arr = obj.id.split('-');
    var id  = arr[1];
    var err = "er-".concat(id);
    var inp = "in-".concat(id);
    var val = "val-".concat(id);
    var pie = "pie-".concat(id);

    document.getElementById(err).style = "visibility:hidden;";
    document.getElementById(err).innerText = "";

    var rate = document.getElementById(inp).value;

    if(rate == ""){
        document.getElementById(err).style = "visibility:visible;";
        document.getElementById(err).innerText = " Please Type the Rate ";

    }
    else if(0 > rate || rate > 100){
        document.getElementById(err).style = "visibility:visible;";
        document.getElementById(err).innerText = " Please Type the Rate Between [0,100] ";
        
    }else{
        document.getElementById(inp).value = "";
        document.getElementById(val).innerHTML = rate.concat(" %");
        document.getElementById(pie).innerHTML = rate.concat("/100");
        var x = document.getElementById(val).value;
        $.ajax({
            type : "POST",
            url : "update.php",
            data : {ID : id, Rate : rate, Plan: arr[0]}
        });

    }
    
}