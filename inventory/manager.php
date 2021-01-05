<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles/reset.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Generations | Inventory</title>
</head>
<body>
<input type="hidden" id="store_user" value="<?=$_COOKIE['ggUserName']?>"/>
    <div id="header">
        <img id="gg-logo" src=images/gg-logo.jpg>
        <a class="logout" href="login.php">Log Out</a>
        <p>Gaming Generations Inventory</p>
    </div>
    
    
    <div id="content-pane">
        <div id="left-column" class="column left"></div>
        <div id ="mid-column" class ="column mid">
            <form>
                <input type="button" class="btn btn-outline-primary store_btn" id="btn_ona" value="Onalaska">    
                <input type="button" class="btn btn-outline-primary store_btn" id="btn_sp" value="Stevens Point">    
                <input type="button" class="btn btn-outline-primary store_btn" id="btn_ec" value="Eau Claire">    
                <input type="button" class="btn btn-outline-primary store_btn" id="btn_sheb" value="Sheboygan"> 

                <label for="start_date">Start Date:</label>
                <input id="start_date" type="date" required>

                <label for="end_date">End Date:</label>
                <input id="end_date" type="date" required>

                <input id="btnSubmit" class="btn btn-outline-success" type="submit" value="Submit">
            </form>
        </div>
        <div id="right-column" class="column right"></div>   
    </div>
    
    
    <div id="footer">
        Gaming Generations &copy;2020
    </div>

    <script>
        var leftColumn = document.getElementById('left-column');
        var midColumn = document.getElementById('mid-column');
        var rightColumn = document.getElementById('right-column');

        var selectedStore="";
        var user = document.getElementById('store_user').value;

        leftColumn.style.display = 'none';
        rightColumn.style.display = 'none';
        midColumn.style.width = '90%';
        midColumn.style.margin = 'auto';
    
        var storeBtns = document.querySelectorAll('.store_btn');
        storeBtns.forEach((button) => {
            button.addEventListener('click', function(evt){
                selectedStore = button.value.toLowerCase();
                if(evt.target.classList.contains('btn-outline-primary')){
                    storeBtns.forEach((btn) => {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary')
                    });
                    evt.target.classList.remove('btn-outline-primary');
                    evt.target.classList.add('btn-primary');
                }
                console.log(selectedStore);
            });

            //namespace.TradeInModule({
                // leftColumnContainer: document.getElementById("left-column"),
                // midColumnContainer : document.getElementById("mid-column"),
                // rightColumnContainer: document.getElementById("right-column"),
                // webServiceAddress: "https://localhost/GG/web-services/tradeins/",
                // //webServiceAddress: "https://www.dylanisensee.com/gg/web-services/tradeins/",
                // customer: user
            //});
        });
    </script>

    <script src="js/tradein-module.js"></script>
</body>
</html>