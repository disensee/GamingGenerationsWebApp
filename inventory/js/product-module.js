var namespace = namespace || {};

namespace.ProductModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress || "https://localhost/GG/web-services/tradeinproducts/" //THIS IS REQUIRED!!
    //Trade in or purchase have to have a value. Need to account for this. 
    var tradeIn = options.tradeIn || null; //REQUIRED TO WORK PROPERLY
    var purchase = options.purchase || null;

    var tiWebServiceAddress = "https://localhost/GG/web-services/tradeins/";
    var prodWebServiceAddress= "https://localhost/GG/web-services/products/";
    
    var consoleArr = ["NES", "Super Nintendo", "Nintendo 64", "Gamecube", "Wii", "Wii U", "Nintendo Switch", "GameBoy", "GameBoy Color", "GameBoy Advance", 
    "Nintendo DS", "Nintendo 3DS", "Playstation", "Playstation 2", "Playstation 3", "Playstation 4", "PSP", "Playstation Vita", 
    "Xbox", "Xbox 360", "Xbox One", "Sega Genesis", "Sega Saturn", "Sega Dreamcast", "Sega Game Gear", "Atari 2600", "Atari 400", "Atari 5200", "Atari 7800", "Atari Lynx", "Atari ST" ];
    
    //var header;
    //var footer;
    var productTableListContainer;
    
    //left column search vars
    var consoleSelectBox;
    var txtSearchProduct;
    var btnSearchByProdName;
    var txtSearchUpc;
    var btnSearchByUpc;

    //left column link list
    var consoleList;

    //mid column table/list
    var productTable;

    //right column text fields
    var txtProductId;
    var txtUpc;
    var txtConsole;
    var txtItem;
    var txtLoosePrice;
    var txtCibPrice;
    var txtGsTradeValue;
    var txtGsPrice;
    var txtQuantity;

    var selProductList;
    var txtTradeInCreditValue;
    var txtTradeInCashValue;

    var selectedProducts = [];

    //right column buttons
    var btnAddToList;
    var btnClearForm;

    var btnRemoveSelected;
    var btnClearAll;
    var btnTradeIn;
    var btnSale;

    var btnAddOneToTradeInValue;
    var btnAddFiveToTradeInValue;
    var btnSubtractOneFromTradeInValue;
    var btnSubtractFiveFromTradeInValue;

    //running total variables
    var totalTradeInCreditValue = 0;
    var totalTradeInCashValue = 0;

    //item value variables
    var itemTradeInCreditValue = 0;
    var itemTradeInCashValue = 0;

    initialize();

    function initialize(){
        console.log(tradeIn);

        leftColumnContainer.style.display = "block";
        leftColumnContainer.innerHTML = "";
        midColumnContainer.innerHTML = "";
        rightColumnContainer.innerHTML = "";

        var leftColumnContainerTemplate = `
            <div id="search-container">
                <p>Search for item:</p><br>
                <p>Search by name:</p>` + 
                createConsoleSelectBox(consoleArr) + 
               `<input type="text" id="txtSearchProduct" placeholder="Enter product name"><br>
                <input id="btnSearchByProdName" type="button" value="Search"><br>
                <p>Searby by UPC:</p>
                <input type="text" id="txtSearchUpc" placeholder="Enter UPC">
                <input id="btnSearchByUpc" type="button" value="Search">
            </div>
            <div id="list-container">
                <p>Filter:</p>` +
                createConsoleList(consoleArr) +
            `</div>`;

        var midColumnContainerTemplate = `
            <div id="mid-table-list">
                <p style="text-align:right;">Search for a product to see results</p>
                <table id="mid-table">
                </table>
            </div>`;
        
        var rightColumnContainerTemplate = `
            <div class="info">
                <table class="info-pane">
                    <form>
                        <tr>
                            <td><label for="productId" hidden="true">Product ID:</label></td>
                            <td><input type="text" name="productId" id="txtProductId" placeholder="Product ID" readonly="true" hidden="true"></td>
                        </tr>
                        <tr>
                            <td><label for="upc">UPC:</label></td>
                            <td><input type="text" name="upc" id="txtUpc" placeholder="UPC" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="console">Console:</label></td>
                            <td><input type="text" name="console" id="txtConsole" placeholder="Console" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="item">Item:</label></td>
                            <td><input type="text" name="item" id="txtItem" placeholder="Item" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="loosePrice">Loose Price:</label></td>
                            <td><input type="text" name="loosePrice" id="txtLoosePrice" placeholder="Loose Price" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="cibPrice">CIB Price:</label></td>
                            <td><input type="text" name="citPrice" id="txtCibPrice" placeholder="CIB Price" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="gsTradeValue">Gamestop Trade Value:</label></td>
                            <td><input type="text" name="gsTradeValue" id="txtGsTradeValue" placeholder="Gamestop Value" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="gsPrice">Gamestop Price:</label></td>
                            <td><input type="text" name="gsPrice" id="txtGsPrice" placeholder="Gamestop Price" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="quantity">Quantity in stock:</label></td>
                            <td><input type="text" name="quantity" id="txtQuantity" readonly="true"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="button" value="Add" id="btnAddToList">
                                <input type="button" value="Clear" id="btnClearForm">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <select id="selProductList" size=15 name="item-list" style="width:500px; height:200px;">
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="button" value="Remove Selected" id="btnRemoveSelected">
                                <input type="button" value="Clear All" id="btnClearAll">
                            </td>
                            <td>
                                <input type="button" value="Trade-In" id="btnTradeIn">
                                <input type="button" value="Sale" id="btnSale">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="item-trade-in-credit-value">Item Trade-In Credit Value:</label></td>
                            <td><input type="text" name="item-trade-in-credit-value" id="txtItemTradeInCreditValue" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="item-trade-in-cash-value">Item Trade-In Cash Value:</label></td>
                            <td><input type="text" name="item-trade-in-cash-value" id="txtItemTradeInCashValue" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="trade-in-credit-value">Total Trade-In Credit Value:</label></td>
                            <td><input type="text" name="trade-in-credit-value" id="txtTradeInCreditValue" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="trade-in-cash-value">Total Trade-In Cash Value:</label></td>
                            <td><input type="text" name="trade-in-cash-value" id="txtTradeInCashValue" readonly="true"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="button" value="Add 1$" id="btnAddOneToTradeInValue">
                                <input type="button" value="Subtract 1$" id="btnSubtractOneFromTradeInValue">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="button" value="Add 5$" id="btnAddFiveToTradeInValue">
                                <input type="button" value="Subtract 5$" id="btnSubtractFiveFromTradeInValue">
                            </td>
                        </tr>
                    </form>
                </table>
            </div>`;

        //inject HTML
        leftColumnContainer.innerHTML = leftColumnContainerTemplate;
        midColumnContainer.innerHTML = midColumnContainerTemplate;
        rightColumnContainer.innerHTML = rightColumnContainerTemplate;
        footer = document.querySelector("#footer");
        footer.innerHTML=`Gaming Generations &copy;2020`;

        productTableListContainer = document.getElementById("mid-table-list");

        //search by product name
        consoleSelectBox = leftColumnContainer.querySelector("#consoleSelectBox");
        txtSearchProduct = leftColumnContainer.querySelector("#txtSearchProduct");
        btnSearchByProdName = leftColumnContainer.querySelector("#btnSearchByProdName").onclick = searchByProductName;

        //search by upc
        txtSearchUpc = leftColumnContainer.querySelector("#txtSearchUpc");
        btnSearchByUpc = leftColumnContainer.querySelector("#btnSearchByUpc").onclick = searchByUpc;

        //console link list
        consoleList = leftColumnContainer.querySelector("#consoleList").onclick = getProductsByConsoleName;

        //mid column product table
        productTable = midColumnContainer.querySelector("#mid-table");

        //right column text fields
        txtProductId = rightColumnContainer.querySelector("#txtProductId");
        txtUpc = rightColumnContainer.querySelector("#txtUpc");
        txtConsole = rightColumnContainer.querySelector("#txtConsole");
        txtItem = rightColumnContainer.querySelector("#txtItem");
        txtLoosePrice = rightColumnContainer.querySelector("#txtLoosePrice");
        txtCibPrice = rightColumnContainer.querySelector("#txtCibPrice");
        txtGsTradeValue = rightColumnContainer.querySelector("#txtGsTradeValue");
        txtGsPrice = rightColumnContainer.querySelector("#txtGsPrice");
        txtQuantity = rightColumnContainer.querySelector("#txtQuantity");
        selProductList = rightColumnContainer.querySelector("#selProductList");

        txtTradeInCreditValue = rightColumnContainer.querySelector("#txtTradeInCreditValue");
        txtTradeInCashValue = rightColumnContainer.querySelector("#txtTradeInCashValue");
        txtItemTradeInCreditValue = rightColumnContainer.querySelector("#txtItemTradeInCreditValue");
        txtItemTradeInCashValue = rightColumnContainer.querySelector("#txtItemTradeInCashValue");

        //right column buttons
        btnAddToList = rightColumnContainer.querySelector("#btnAddToList").onclick = addProductForTransaction;
        btnClearForm = rightColumnContainer.querySelector("#btnClearForm").onclick = clearProductInfoTextBoxes;

        btnRemoveSelected = rightColumnContainer.querySelector("#btnRemoveSelected").onclick = removeSelectedClick;
        btnClearAll = rightColumnContainer.querySelector("#btnClearAll").onclick = function(){
            selectedProducts.length = 0;
            refreshSelectedProducts();

            calculateTradeInValue(selectedProducts);
        };

        if(tradeIn != null){
            btnTradeIn = rightColumnContainer.querySelector("#btnTradeIn").onclick = tradeInQuantityUpdate;
            btnSale = rightColumnContainer.querySelector("#btnSale").style.display = "none";
        }

        if(purchase != null){
            btnSale = rightColumnContainer.querySelector("#btnSale").onclick = saleQuantityUpdate;
            btnTrade = rightColumnContainer.querySelector("#btnTradeIn").style.display = "none";
        }

        btnAddOneToTradeInValue = rightColumnContainer.querySelector("#btnAddOneToTradeInValue");
        btnAddFiveToTradeInValue = rightColumnContainer.querySelector("#btnAddFiveToTradeInValue");
        btnSubtractOneFromTradeInValue = rightColumnContainer.querySelector("#btnSubtractOneFromTradeInValue");
        btnSubtractFiveFromTradeInValue = rightColumnContainer.querySelector("#btnSubtractFiveFromTradeInValue");
        
        //event handlers
        productTable.addEventListener("click", selectProductInList);
        selProductList.addEventListener("change", populateProductFormFromSelectBox);
        txtSearchProduct.addEventListener("keyup", function(event){
            if(event.keyCode == 13){
                event.preventDefault();
                leftColumnContainer.querySelector("#btnSearchByProdName").click();
            }
        });

        txtSearchUpc.addEventListener("keyup", function(event){
            if(event.keyCode == 13){
                event.preventDefault();
                leftColumnContainer.querySelector("#btnSearchByUpc").click();
            }
        });

        btnAddOneToTradeInValue.addEventListener("click", function(event){
            addOneToTradeInValue(totalTradeInCreditValue, totalTradeInCashValue);
        });

        btnAddFiveToTradeInValue.addEventListener("click", function(event){
            addFiveToTradeInValue(totalTradeInCreditValue, totalTradeInCashValue);
        });

        btnSubtractOneFromTradeInValue.addEventListener("click", function(event){
            removeOneFromTradeInValue(totalTradeInCreditValue, totalTradeInCashValue);
        });

        btnSubtractFiveFromTradeInValue.addEventListener("click", function(event){
            removeFiveFromTradeInValue(totalTradeInCreditValue, totalTradeInCashValue);
        });
    }

    function generateProductList(products){
        productTableListContainer.innerHTML =`<h4>PRODUCTS</h4>`;
        var html = `<tr>
                        <th>Console</th>
                        <th>Product</th>
                    </tr>`;
        
        if(Array.isArray(products)){
            for(var x = 0; x < products.length; x++){
                html += `<tr class="mid-table-row" productId="${products[x].productId}">
                            <td class="mid-table-cell">
                                ${products[x].consoleName}
                            </td>
                            <td class="mid-table-cell">
                                ${products[x].productName}
                            </td>
                        </tr>`;
            }
        }else{
            html += `<tr class="product-table-row" productId="${products.productId}">
                            <td class="product-table-cell">
                                ${products.consoleName}
                            </td>
                            <td class="product-table-cell">
                                ${products.productName}
                            </td>
                        </tr>`;
        }
        
        productTable.innerHTML = html;
        productTableListContainer.appendChild(productTable);
        return productTable;
    }

    function selectProductInList(evt){
        var target = evt.target
        if(target.classList.contains("mid-table-cell")){
            var selectedProductId = target.closest("tr").getAttribute("productId");
        }

        namespace.ajax.send({
            url: prodWebServiceAddress + selectedProductId,
            method: "GET",
            callback: function(response){
                //console.log(response);
                var product = JSON.parse(response);
                populateProductForm(product);
            }
        });
    }

    function populateProductForm(product){
        txtProductId.value = product.productId;
        txtUpc.value = product.upc;
        txtConsole.value = product.consoleName;
        txtItem.value = product.productName;
        txtLoosePrice.value = Math.floor(product.loosePrice).toFixed(2);
        txtCibPrice.value = Math.floor(product.cibPrice).toFixed(2);
        txtGsTradeValue.value = Math.floor(product.gamestopTradeValue).toFixed(2);
        txtGsPrice.value = Math.floor(product.gamestopPrice).toFixed(2);
        txtQuantity.value = product.quantity;
    }

    function createProductFromForm(){
        var product = {
            productId: txtProductId.value,
            productName: txtItem.value,
            consoleName: txtConsole.value,
            loosePrice: txtLoosePrice.value,
            cibPrice: txtCibPrice.value,
            gamestopTradeValue: txtGsTradeValue.value,
            gamestopPrice: txtGsPrice.value,
            upc: txtUpc.value,
            quantity: txtQuantity.value
        };

        return product;
    }

    function getAllProducts(){
        namespace.ajax.send({
            url: prodWebServiceAddress,
            method: "GET",
            callback: function(response){
                //console.log(response);
                var products = JSON.parse(response);
                generateProductList(products);
            }
        });
    }

    function tradeInQuantityUpdate(){
        if(selectedProducts.length > 0){
            if(confirm("Are you sure you want to finalize this trade-in?")){
                selectedProducts.forEach((p)=>{
                    p.quantity++;
                    namespace.ajax.send({
                        url: prodWebServiceAddress + p.productId,
                        method: "PUT",
                        headers: {"Content-Type": "application/json", "Accept": "application/json"},
                        requestBody: JSON.stringify(p),
                        callback: function(response){
                            console.log(response);
                        }
                    });
                    selectedProducts.length = 0;
                    refreshSelectedProducts();
                });
            }
        }else{
            alert("Please add product(s) to the transaction list.")
        }
    }

    function saleQuantityUpdate(){
        if(selectedProducts.length > 0){
            if(confirm("Are you sure you want to finalize this sale?")){
                selectedProducts.forEach((p)=>{
                    p.quantity = p.quantity - 1;
                    namespace.ajax.send({
                        url: prodWebServiceAddress + p.productId,
                        method: "PUT",
                        headers: {"Content-Type": "application/json", "Accept": "application/json"},
                        requestBody: JSON.stringify(p),
                        callback: function(response){
                            console.log(response);
                        }
                    });
                    selectedProducts.length = 0;
                    refreshSelectedProducts();
                });
            }
        }else{
            alert("Please add product(s) to the transaction list.")
        }
    }

    function getProductsByConsoleName(evt){
        var target = evt.target
        if(target.classList.contains("list-anchor")){
            var vgConsole = target.closest("a").getAttribute("selectedConsole");
        }

        namespace.ajax.send({
            url: prodWebServiceAddress + vgConsole,
            method: "GET",
            callback: function(response){
                console.log(response);
                var products = JSON.parse(response);
                generateProductList(products);
            }
        });
    }

    function getProductsByProductName(product, vgConsole){
        namespace.ajax.send({
            url: prodWebServiceAddress + vgConsole + "/" + product,
            method: "GET",
            callback: function(response){
                var products = JSON.parse(response);
                generateProductList(products);
            },
            errorCallback: function(response){
                if(response.status == 404){
                    alert("Search yielded zero results.");
                }
            }
        });
    }

    function getProductByUpc(upc){
        namespace.ajax.send({
            url: prodWebServiceAddress + upc,
            method: "GET",
            callback: function(response){
                console.log(response);
                var product = JSON.parse(response);
                generateProductList(product);
            }
        });
    }

    function searchByProductName(){
        if(validateSearchProductName()){
            var consoleName = consoleSelectBox.value;
            var productName = txtSearchProduct.value;

            getProductsByProductName(productName, consoleName);
            clearSearchTextBoxes();
        }
    }

    function searchByUpc(){
        if(validateSearchUpc()){
            var upc = txtSearchUpc.value;
            
            getProductByUpc(upc);
            clearSearchTextBoxes();
        }
    }

    function createConsoleSelectBox(arr){
        var selectBox = document.createElement("select");
        selectBox.setAttribute("id", "consoleSelectBox");
        var opt0 = document.createElement("option");
        
        opt0.value = "-1";
        opt0.text = "Select console:"
        selectBox.add(opt0, null);
        
        
        arr.forEach((c) =>{
            var option = document.createElement("option");
            option.value = c.toLowerCase();
            option.text = c;

            selectBox.add(option);
        });


        return selectBox.outerHTML;
    };

    function createConsoleList(arr){
        var ul = document.createElement("ul");
        arr.forEach((c)=>{
            var li = document.createElement("li");
            li.innerHTML=`<a class='list-anchor' selectedConsole="${c}">` + c + `</a>`;
            ul.appendChild(li);
        });
        
        ul.setAttribute("id", "consoleList");
        return ul.outerHTML;
    }

    function clearSearchTextBoxes(){
        consoleSelectBox.selectedIndex = 0;
        txtSearchProduct.value = "";
        txtSearchUpc.value = "";
    }

    function clearProductInfoTextBoxes(){
        txtProductId.value="";
        txtUpc.value="";
        txtConsole.value="";
        txtItem.value="";
        txtLoosePrice.value="";
        txtCibPrice.value="";
        txtGsTradeValue.value="";
        txtGsPrice.value="";
        txtQuantity.value="";
    }

    function refreshSelectedProducts(){
        selProductList.innerHTML = "";

        selectedProducts.forEach((p)=>{
            selProductList.innerHTML += `<option value="${p.productId}">${p.productName} - ${p.consoleName}</option>`
        });
    }

    function addProductForTransaction(){
        if(txtProductId.value == ""){
            alert("Please select a product to add to the pending transaction list.")
        }else{
            var product = createProductFromForm();
            selectedProducts.push(product);
            
            clearProductInfoTextBoxes();
            refreshSelectedProducts();
            calculateTradeInValue(selectedProducts);
            txtItemTradeInCreditValue.value = "";
            txtItemTradeInCashValue.value = "";
        }
    }

    function removeSelectedProduct(productId){
        for(var i = 0; i < selectedProducts.length; i++){
            if(productId == selectedProducts[i].productId){
                selectedProducts.splice(i, 1);
            }
        }

        refreshSelectedProducts();
        calculateTradeInValue(selectedProducts);
    }

    function removeSelectedClick(){
        if(selProductList.selectedIndex != -1){
            var id = selProductList.value;
            removeSelectedProduct(id);
            txtItemTradeInCreditValue.value = "";
            txtItemTradeInCashValue.value = "";
        }else{
            alert("Please select a product from the pending transaction list.")
        }
    }

    function validateSearchProductName(){
        if(consoleSelectBox.value == -1){
            alert("Please select a console.");
            return false;
        }

        if(txtSearchProduct.value == ""){
            alert("Please enter a product.");
            return false;
        }
        return true;
    }

    function validateSearchUpc(){
        var regExp = /^[0-9]{12}$/;
        if(txtSearchUpc.value == ""){
            alert("Please enter a UPC.");
            return false;
        }

        if(!regExp.test(txtSearchUpc.value)){
            alert("Please enter a valid UPC (12 numerical digits).");
            return false;
        }

        return true;
    }

    function populateProductFormFromSelectBox(){
        for(var i = 0; i < selectedProducts.length; i++){
            if(selectedProducts[i].productId == selProductList.value){
                populateProductForm(selectedProducts[i]);
                calculateTradeInValue(selectedProducts);
            }
        }
    }

    function addOneToTradeInValue(baseValueCredit, baseValueCash){
        //var holdValue = baseValue;
        txtTradeInCreditValue.value = (parseFloat(txtTradeInCreditValue.value) + (1)).toFixed(2);
        txtTradeInCashValue.value = (parseFloat(txtTradeInCashValue.value) + (1)).toFixed(2);
    }

    function addFiveToTradeInValue(baseValueCredit, baseValueCash){
        //var holdValue = baseValue;
        txtTradeInCreditValue.value = (parseFloat(txtTradeInCreditValue.value) + (5)).toFixed(2);
        txtTradeInCashValue.value = (parseFloat(txtTradeInCashValue.value) + (5)).toFixed(2);
    }

    function removeOneFromTradeInValue(baseValueCredit, baseValueCash){
        //var holdValue = baseValue;
        txtTradeInCreditValue.value = (parseFloat(txtTradeInCreditValue.value) - (1)).toFixed(2);
        txtTradeInCashValue.value = (parseFloat(txtTradeInCashValue.value) - (1)).toFixed(2);
    }

    function removeFiveFromTradeInValue(baseValueCredit, baseValueCash){
        //var holdValue = baseValue;
        txtTradeInCreditValue.value = (parseFloat(txtTradeInCreditValue.value) - (5)).toFixed(2);
        txtTradeInCashValue.value = (parseFloat(txtTradeInCashValue.value) - (5)).toFixed(2);
    }

    //TODO: Talk to jake and find out how we want to do this.
    function calculateTradeInValue(products){
        totalTradeInCreditValue = 0;
        totalTradeInCashValue = 0;
        itemTradeInCreditValue;
        itemTradeInCashValue;
        for(var i = 0; i < products.length; i++){
            var p = products[i];
            var consoleInName = p.productName.toLowerCase().includes("console");
            var systemInName = p.productName.toLowerCase().includes("system");
            if(!consoleInName && !systemInName){ 
                //GAME PRICING
                if(p.consoleName.toLowerCase() == "nes" || p.consoleName.toLowerCase() == "super nintendo" || p.consoleName.toLowerCase() == "nintendo 64"
                  || p.consoleName.toLowerCase() == "sega saturn" || p.consoleName.toLowerCase().includes("atari")){
                    totalTradeInCreditValue += Math.floor(p.loosePrice * 0.4);
                    totalTradeInCashValue += Math.floor(p.loosePrice * 0.3);

                    if(Math.floor(p.loosePrice * 4) < 1){
                        totalTradeInCreditValue += 0.1;
                    }

                    if(selProductList.value == p.productId){
                        itemTradeInCreditValue = Math.floor(p.loosePrice * 0.4);
                        itemTradeInCashValue = Math.floor(p.loosePrice * 0.3);

                        if(itemTradeInCreditValue < 1){
                            itemTradeInCreditValue = 0.1;
                        }
                    }
                }
                
                if(p.consoleName.toLowerCase() == "gamecube" || p.consoleName.toLowerCase() == "playstation" || p.consoleName.toLowerCase() == "playstation 2" 
                || p.consoleName.toLowerCase() == "playstation 3" || p.consoleName.toLowerCase() == "psp" || p.consoleName.toLowerCase() == "playstation vita"
                || p.consoleName.toLowerCase() == "gameboy" || p.consoleName.toLowerCase() == "gameboy advance" || p.consoleName.toLowerCase() == "gameboy color"
                || p.consoleName.toLowerCase() == "sega game gear" || p.consoleName.toLowerCase() == "sega genesis" || p.consoleName.toLowerCase() == "xbox"
                || p.consoleName.toLowerCase() == "xbox 360" || p.consoleName.toLowerCase() == "nintendo ds" || p.consoleName.toLowerCase() == "sega dreamcast"
                || p.consoleName.toLowerCase() == "wii"){
                    totalTradeInCreditValue += Math.floor(p.loosePrice * 0.25);
                    totalTradeInCashValue += Math.floor(p.loosePrice * 0.1);

                    if(Math.floor(p.loosePrice * 0.25) < 1){
                        totalTradeInCreditValue += 0.1;
                    }

                    if(selProductList.value == p.productId){
                        itemTradeInCreditValue = Math.floor(p.loosePrice * 0.25);
                        itemTradeInCashValue = Math.floor(p.loosePrice * 0.1);

                        if(itemTradeInCreditValue < 1){
                            itemTradeInCreditValue = 0.1;
                        }
                    }
                }
                
                if(p.consoleName.toLowerCase() == "xbox one" || p.consoleName.toLowerCase() == "playstation 4" || p.consoleName.toLowerCase() == "wii u"
                || p.consoleName.toLowerCase() == "nintendo switch" || p.consoleName.toLowerCase() == "nintendo 3ds"){
                    totalTradeInCreditValue += Math.floor(p.loosePrice * 0.5);
                    totalTradeInCashValue += Math.floor(p.loosePrice * 0.3);
                    
                    if(Math.floor(p.loosePrice * 0.5) < 1){
                        totalTradeInCreditValue += 0.1;
                    }

                    if(selProductList.value == p.productId){
                        itemTradeInCreditValue = Math.floor(p.loosePrice * 0.5);
                        itemTradeInCashValue = Math.floor(p.loosePrice * 0.3);

                        if(itemTradeInCreditValue < 1){
                            itemTradeInCreditValue = 0.1;
                        }
                    }
                }
            }else if(p.productName.toLowerCase().includes("console") || p.productName.toLowerCase().includes("system")){
                //SYSTEM PRICING
                if(p.consoleName.toLowerCase() == "nes" || p.consoleName.toLowerCase() == "super nintendo" || p.consoleName.toLowerCase() == "nintendo 64"
                  || p.consoleName.toLowerCase() == "sega saturn" || p.consoleName.toLowerCase().includes("atari") || p.consoleName.toLowerCase() == "xbox one"
                  || p.consoleName.toLowerCase() == "playstation 4" || p.consoleName.toLowerCase() == "wii u" || p.consoleName.toLowerCase() == "nintendo switch"
                  || p.consoleName.toLowerCase() == "nintendo 3ds" ){
                    totalTradeInCreditValue += Math.floor(p.loosePrice * 0.5);
                    totalTradeInCashValue += Math.floor(p.loosePrice * 0.4);
                    if(selProductList.value == p.productId){
                        itemTradeInCreditValue = Math.floor(p.loosePrice * 0.5);
                        itemTradeInCashValue = Math.floor(p.loosePrice * 0.4);
                    }
                }

                if(p.consoleName.toLowerCase() == "gamecube" || p.consoleName.toLowerCase() == "playstation" || p.consoleName.toLowerCase() == "playstation 2" 
                || p.consoleName.toLowerCase() == "playstation 3" || p.consoleName.toLowerCase() == "psp" || p.consoleName.toLowerCase() == "playstation vita"
                || p.consoleName.toLowerCase() == "gameboy" || p.consoleName.toLowerCase() == "gameboy advance" || p.consoleName.toLowerCase() == "gameboy color"
                || p.consoleName.toLowerCase() == "sega game gear" || p.consoleName.toLowerCase() == "sega genesis" || p.consoleName.toLowerCase() == "xbox"
                || p.consoleName.toLowerCase() == "xbox 360" || p.consoleName.toLowerCase() == "nintendo ds" || p.consoleName.toLowerCase() == "sega dreamcast"
                || p.consoleName.toLowerCase() == "wii"){
                    totalTradeInCreditValue += Math.floor(p.loosePrice * 0.3);
                    totalTradeInCashValue += Math.floor(p.loosePrice * 0.2);
                    if(selProductList.value == p.productId){
                        itemTradeInCreditValue = Math.floor(p.loosePrice * 0.3);
                        itemTradeInCashValue = Math.floor(p.loosePrice * 0.2);
                    }
                }
            }else{
                totalTradeInCreditValue += Math.floor(p.loosePrice * 0.4);
                totalTradeInCashValue += Math.floor(p.loosePrice * 0.3);
                if(selProductList.value == p.productId){
                    itemTradeInCreditValue = Math.floor(p.loosePrice * 0.4);
                    itemTradeInCashValue = Math.floor(p.loosePrice * 0.3);
                }
            }
        }

        txtTradeInCreditValue.value = totalTradeInCreditValue.toFixed(2);
        txtTradeInCashValue.value = totalTradeInCashValue.toFixed(2);
        txtItemTradeInCreditValue.value = itemTradeInCreditValue.toFixed(2);
        txtItemTradeInCashValue.value = itemTradeInCashValue.toFixed(2);
    }
}