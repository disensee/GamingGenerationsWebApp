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
            <div class="d-flex justify-content-center">
                <div class='btn-group center'>
                    <input type="button" class="btn btn-outline-primary store_btn" id="btn_ona" value="Onalaska">    
                    <input type="button" class="btn btn-outline-primary store_btn" id="btn_sp" value="Stevens Point">    
                    <input type="button" class="btn btn-outline-primary store_btn" id="btn_ec" value="Eau Claire">    
                    <input type="button" class="btn btn-outline-primary store_btn" id="btn_sheb" value="Sheboygan"> 
                </div>
            </div>

            <div class='validation' id='v_selstore'></div>

            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input id="start_date" class="form-control" type="date" required />

                <label for="end_date">End Date:</label>
                <input id="end_date" class="form-control" type="date" required />
            </div>
            
            <div class = validation id='v_date'></div>

            <input id="btn_submit"class="btn btn-outline-success form-control" type="button" value="Submit">
        </div>
        <div id="right-column" class="column right"></div>   
    </div>
    
    
    <div id="footer"></div>

    <script src="js/ajax.js"></script>
    <script src="js/main.js"></script>
    <script>
        var currentYear = new Date().getFullYear();
        document.getElementById('footer').innerHTML = `Dylan Isensee &copy;${currentYear}`;
    </script>
    <script>
        var leftColumn = document.getElementById('left-column');
        var midColumn = document.getElementById('mid-column');
        var rightColumn = document.getElementById('right-column');

        var user = document.getElementById('store_user').value;

        var selectedStore;
        var vStore = document.getElementById('v_selstore');

        var sDate = document.getElementById('start_date');
        var eDate = document.getElementById('end_date');
        var vDate = document.getElementById('v_date');

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        if(dd<10){
                dd='0'+dd
            } 
            if(mm<10){
                mm='0'+mm
            } 

        today = yyyy+'-'+mm+'-'+dd;
        sDate.setAttribute("max", today);
        eDate.setAttribute("max", today);

        var btnSubmit = document.getElementById('btn_submit'); 

        leftColumn.style.display = 'none';
        rightColumn.style.display = 'none';
        midColumn.style.width = '50%';
        midColumn.style.margin = 'auto';
    
        var storeBtns = document.querySelectorAll('.store_btn');
        storeBtns.forEach((button) => {
            button.addEventListener('click', function(evt){
                selectedStore = button.value.replace(/\s/g, '').toLowerCase();
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

            btnSubmit.removeEventListener('click', function(){});
            btnSubmit.addEventListener('click', function(){
                if(validateFilters()){
                    namespace.TradeInModule({
                        leftColumnContainer: document.getElementById("left-column"),
                        midColumnContainer : document.getElementById("mid-column"),
                        rightColumnContainer: document.getElementById("right-column"),
                        //webServiceAddress: "https://localhost/GG/web-services/tradeins/",
                        webServiceAddress: "https://www.dylanisensee.com/gg/web-services/tradeins/",
                        customer: user,
                        selectedStore: selectedStore,
                        sDate: sDate.value,
                        eDate: eDate.value
                    });
                }
            });
        });

        function validateFilters(){
            vStore.innerHTML = '';
            vDate.innerHTML = '';

            if(!selectedStore){
                vStore.innerHTML = "Please select a store";
                return false;
            }

            if(!sDate.checkValidity()){
                sDate.reportValidity();
                return false;
            }

            if(!eDate.checkValidity()){
                eDate.reportValidity();
                return false;
            }

            if(sDate.value > eDate.value){
                vDate.innerHTML = 'End date must be the same date or occur after the selected start date';
                return false;
            }

            return true;
        }
    </script>

    <script src="js/tradein-module.js"></script>
</body>
</html>