var namespace = namespace || {};

namespace.InventoryModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress || "http://localhost/GG/web-services/products/"; //THIS IS REQUIRED!


    var consoleArr = ["NES", "Super Nintendo", "Nintendo 64", "Gamecube", "Wii", "Wii U", "Nintendo Switch", "GameBoy", "GameBoy Color", "GameBoy Advance", 
    "Nintendo DS", "Nintendo 3DS", "Playstation", "Playstation 2", "Playstation 3", "Playstation 4", "PSP", "Playstation Vita", 
    "Xbox", "Xbox 360", "Xbox One", "Sega Genesis", "Sega Saturn", "Sega Dreamcast", "Sega Game Gear", "Atari 2600", "Atari 400", "Atari 5200", "Atari 7800", "Atari Lynx", "Atari ST" ];
    
    var header;
    var footer;
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

    //running total variables
    var totalTradeInCreditValue = 0;
    var totalTradeInCashValue = 0;

    initialize();

    function initialize(){
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
            <div id="product-table-list">
                <p style="text-align:right;">Search for a product to see results</p>
                <table id="product-table">
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
                            <td><label for"trade-in-credit-value">Trade-In Credit Value:</label></td>
                            <td><input type="text" name="trade-in-credit-value" id="txtTradeInCreditValue" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for"trade-in-cash-value">Trade-In Cash Value:</label></td>
                            <td><input type="text" name="trade-in-cash-value" id="txtTradeInCashValue" readonly="true"></td>
                        </tr>
                    </form>
                </table>
            </div>`;

        //inject HTML
        header = document.querySelector("#header");
        header.innerHTML = `<img src=images/gg-logo.jpg><p>Gaming Generations Inventory<p>`;
        leftColumnContainer.innerHTML = leftColumnContainerTemplate;
        midColumnContainer.innerHTML = midColumnContainerTemplate;
        rightColumnContainer.innerHTML = rightColumnContainerTemplate;
        footer = document.querySelector("#footer");
        footer.innerHTML=`Gaming Generations &copy;2020`;

        productTableListContainer = document.getElementById("product-table-list");

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
        productTable = midColumnContainer.querySelector("#product-table");

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

        //right column buttons
        btnAddToList = rightColumnContainer.querySelector("#btnAddToList").onclick = addProductForTransaction;
        btnClearForm = rightColumnContainer.querySelector("#btnClearForm").onclick = clearProductInfoTextBoxes;

        btnRemoveSelected = rightColumnContainer.querySelector("#btnRemoveSelected").onclick = removeSelectedClick;
        btnClearAll = rightColumnContainer.querySelector("#btnClearAll").onclick = function(){
            selectedProducts.length = 0;
            refreshSelectedProducts();

            calculateTradeInValue();
        };

        btnTradeIn = rightColumnContainer.querySelector("#btnTradeIn").onclick = tradeInQuantityUpdate;
        btnSale = rightColumnContainer.querySelector("#btnSale").onclick = saleQuantityUpdate;
        
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
    }

    function generateProductList(products){
        productTableListContainer.innerHTML =`<h4>PRODUCTS</h4>`;
        var html = `<tr>
                        <th>Console</th>
                        <th>Product</th>
                    </tr>`;
        
        if(Array.isArray(products)){
            for(var x = 0; x < products.length; x++){
                html += `<tr class="product-table-row" productId="${products[x].productId}">
                            <td class="product-table-cell">
                                ${products[x].consoleName}
                            </td>
                            <td class="product-table-cell">
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
        if(target.classList.contains("product-table-cell")){
            var selectedProductId = target.closest("tr").getAttribute("productId");
        }

        namespace.ajax.send({
            url: webServiceAddress + selectedProductId,
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
        txtLoosePrice.value = product.loosePrice;
        txtCibPrice.value = product.cibPrice;
        txtGsTradeValue.value = product.gamestopTradePrice;
        txtGsPrice.value = product.gamestopPrice;
        txtQuantity.value = product.quantity;
    }

    function createProductFromForm(){
        var product = {
            productId: txtProductId.value,
            productName: txtItem.value,
            consoleName: txtConsole.value,
            loosePrice: txtLoosePrice.value,
            cibPrice: txtCibPrice.value,
            gamestopTradePrice: txtGsTradeValue.value,
            gamestopPrice: txtGsPrice.value,
            upc: txtUpc.value,
            quantity: txtQuantity.value
        };

        return product;
    }

    function getAllProducts(){
        namespace.ajax.send({
            url: webServiceAddress,
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
            selectedProducts.forEach((p)=>{
                p.quantity++;
                namespace.ajax.send({
                    url: webServiceAddress + p.productId,
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
        }else{
            alert("Please add product(s) to the transaction list.")
        }
    }

    function saleQuantityUpdate(){
        if(selectedProducts.length > 0){
            selectedProducts.forEach((p)=>{
                p.quantity = p.quantity - 1;
                namespace.ajax.send({
                    url: webServiceAddress + p.productId,
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
            url: webServiceAddress + vgConsole,
            method: "GET",
            callback: function(response){
                //console.log(response);
                var products = JSON.parse(response);
                generateProductList(products);
            }
        });
    }

    function getProductsByProductName(product, vgConsole){
        namespace.ajax.send({
            url: webServiceAddress + vgConsole + "/" + product,
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
            url: webServiceAddress + upc,
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
            calculateTradeInValue();
        }
    }

    function removeSelectedProduct(productId){
        for(var i = 0; i < selectedProducts.length; i++){
            if(productId == selectedProducts[i].productId){
                selectedProducts.splice(i, 1);
            }
        }

        refreshSelectedProducts();
        calculateTradeInValue();
    }

    function removeSelectedClick(){
        if(selProductList.selectedIndex != -1){
            var id = selProductList.value;
            removeSelectedProduct(id);
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
            }
        }
    }

    //TODO: Talk to jake and find out how we want to do this.
    function calculateTradeInValue(){
        totalTradeInCreditValue = 0;
        totalTradeInCashValue = 0;
        selectedProducts.forEach((p)=>{
            var consoleInName = p.productName.toLowerCase().includes("console");
            var systemInName = p.productName.toLowerCase().includes("system");
            if(!consoleInName && !systemInName){ 
                //GAME PRICING
                if(p.consoleName.toLowerCase() == "nes" || p.consoleName.toLowerCase() == "super nintendo" || p.consoleName.toLowerCase() == "nintendo 64"
                  || p.consoleName.toLowerCase() == "sega saturn" || p.consoleName.toLowerCase().includes("atari")){
                    totalTradeInCreditValue += p.loosePrice * 0.4;
                    totalTradeInCashValue += p.loosePrice * 0.3;
                }
                
                if(p.consoleName.toLowerCase() == "gamecube" || p.consoleName.toLowerCase() == "playstation" || p.consoleName.toLowerCase() == "playstation 2" 
                || p.consoleName.toLowerCase() == "playstation 3" || p.consoleName.toLowerCase() == "psp" || p.consoleName.toLowerCase() == "playstation vita"
                || p.consoleName.toLowerCase() == "gameboy" || p.consoleName.toLowerCase() == "gameboy advance" || p.consoleName.toLowerCase() == "gameboy color"
                || p.consoleName.toLowerCase() == "sega game gear" || p.consoleName.toLowerCase() == "sega genesis" || p.consoleName.toLowerCase() == "xbox"
                || p.consoleName.toLowerCase() == "xbox 360" || p.consoleName.toLowerCase() == "nintendo ds" || p.consoleName.toLowerCase() == "sega dreamcast"
                || p.consoleName.toLowerCase() == "wii"){
                    totalTradeInCreditValue += p.loosePrice * 0.25;
                    totalTradeInCashValue += p.loosePrice * 0.1;
                }
                
                if(p.consoleName.toLowerCase() == "xbox one" || p.consoleName.toLowerCase() == "playstation 4" || p.consoleName.toLowerCase() == "wii u"
                || p.consoleName.toLowerCase() == "nintendo switch" || p.consoleName.toLowerCase() == "nintendo 3ds"){
                    totalTradeInCreditValue += p.loosePrice * 0.5;
                    totalTradeInCashValue += p.loosePrice * 0.3;
                }
            }else if(p.productName.toLowerCase().includes("console") || p.productName.toLowerCase().includes("system")){
                //SYSTEM PRICING
                if(p.consoleName.toLowerCase() == "nes" || p.consoleName.toLowerCase() == "super nintendo" || p.consoleName.toLowerCase() == "nintendo 64"
                  || p.consoleName.toLowerCase() == "sega saturn" || p.consoleName.toLowerCase().includes("atari") || p.consoleName.toLowerCase() == "xbox one"
                  || p.consoleName.toLowerCase() == "playstation 4" || p.consoleName.toLowerCase() == "wii u" || p.consoleName.toLowerCase() == "nintendo switch"
                  || p.consoleName.toLowerCase() == "nintendo 3ds" ){
                    totalTradeInCreditValue += p.loosePrice * 0.5;
                    totalTradeInCashValue += p.loosePrice * 0.4;
                }

                if(p.consoleName.toLowerCase() == "gamecube" || p.consoleName.toLowerCase() == "playstation" || p.consoleName.toLowerCase() == "playstation 2" 
                || p.consoleName.toLowerCase() == "playstation 3" || p.consoleName.toLowerCase() == "psp" || p.consoleName.toLowerCase() == "playstation vita"
                || p.consoleName.toLowerCase() == "gameboy" || p.consoleName.toLowerCase() == "gameboy advance" || p.consoleName.toLowerCase() == "gameboy color"
                || p.consoleName.toLowerCase() == "sega game gear" || p.consoleName.toLowerCase() == "sega genesis" || p.consoleName.toLowerCase() == "xbox"
                || p.consoleName.toLowerCase() == "xbox 360" || p.consoleName.toLowerCase() == "nintendo ds" || p.consoleName.toLowerCase() == "sega dreamcast"
                || p.consoleName.toLowerCase() == "wii"){
                    totalTradeInCreditValue += p.loosePrice * 0.3;
                    totalTradeInCashValue += p.loosePrice * 0.2;
                }
            }else{
                totalTradeInCreditValue += p.loosePrice * 0.4;
                totalTradeInCashValue += p.loosePrice * 0.3;
            }
        });

        txtTradeInCreditValue.value = totalTradeInCreditValue.toFixed(2);
        txtTradeInCashValue.value = totalTradeInCashValue.toFixed(2);
    }
};