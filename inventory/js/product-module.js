var namespace = namespace || {};

namespace.ProductModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress;// || "https://localhost/GG/web-services/tradeinproducts/" //THIS IS REQUIRED!!
    
    //Trade in or purchase have to have a value. Need to account for this. 
    var tradeIn = options.tradeIn || null;
    var purchase = options.purchase || null;

    var purchaseWebServiceAddress = "https://localhost/GG/web-services/purchases/";
    var tiWebServiceAddress = "https://localhost/GG/web-services/tradeins/";
    var prodWebServiceAddress= "https://localhost/GG/web-services/products/";
    
    //var purchaseWebServiceAddress= "https://www.dylanisensee.com/gg/web-services/purchases/";
    //var tiWebServiceAddress = "https://www.dylanisensee.com/gg/web-services/tradeins/";
    //var prodWebServiceAddress= "https://www.dylanisensee.com/gg/web-services/products/";
    
    var consoleArr = ["NES", "Super Nintendo", "Nintendo 64", "Gamecube", "Wii", "Wii U", "Nintendo Switch", "GameBoy", "GameBoy Color", "GameBoy Advance", 
    "Nintendo DS", "Nintendo 3DS", "Playstation", "Playstation 2", "Playstation 3", "Playstation 4", "PSP", "Playstation Vita", 
    "Xbox", "Xbox 360", "Xbox One", "Sega Genesis", "Sega Saturn", "Sega Dreamcast", "Sega Game Gear", "Atari 2600", "Atari 400", "Atari 5200", "Atari 7800", "Atari Lynx", "Atari ST" ];
    
    var completedTradeIn;
    var productTableListContainer;
    

    var selectedProducts = [];
    var tradeInProducts = [];
    var purchaseProducts = [];
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
    var txtOnaQuantity;
    var txtEcQuantity;
    var txtSpQuantity;
    var txtShebQuantity;
    var txtSerialNumber;

    var selProductList;
    var txtTradeInCreditValue;
    var txtTradeInCashValue;

    var txtTotalPaid;
    var txtCheckNumber;
    var txtEmployee;

    var taComments;

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

    //right column radio buttons
    var rbStoreCredit;
    var rbCash;
    var rbCheck;
    var rbCredit;

    var rbGroup;

    //running total variables
    var totalTradeInCreditValue = 0;
    var totalTradeInCashValue = 0;

    //item value variables
    var itemTradeInCreditValue = 0;
    var itemTradeInCashValue = 0;
    var productCreditValue = 0;
    var productCashValue = 0;

    initialize();

    function initialize(){
        console.log("TRADE IN: " + tradeIn);
        console.log("PURCHASE: " + purchase);

        leftColumnContainer.style.display = "block";
        leftColumnContainer.innerHTML = "";
        midColumnContainer.innerHTML = "";
        rightColumnContainer.innerHTML = "";

        var leftColumnContainerTemplate = `
            <div id="search-container">
                <p>Search for item:</p>
                <p>Search by name:</p>` + 
                createConsoleSelectBox(consoleArr) + 
               `<input type="text" id="txtSearchProduct" placeholder="Enter product name"><br>
                <button class="btn btn-outline-primary btn-sm" id="btnSearchByProdName">Search</button><br>
                <p>Searby by UPC:</p>
                <input type="text" id="txtSearchUpc" placeholder="Enter UPC">
                <button class="btn btn-outline-primary btn-sm" id="btnSearchByUpc">Search</button>
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
                            <td><label for="onaQuantity">Ona Quantity in stock:</label></td>
                            <td><input type="text" name="onaQuantity" id="txtOnaQuantity" placeholder="Onalaska Stock" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="ecQuantity">EC Quantity in stock:</label></td>
                            <td><input type="text" name="ecQuantity" id="txtEcQuantity" placeholder="Eau Claire Stock" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="spQuantity">SP Quantity in stock:</label></td>
                            <td><input type="text" name="spQuantity" id="txtSpQuantity" placeholder="Stevents Point Stock" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="shebQuantity">Sheb Quantity in stock:</label></td>
                            <td><input type="text" name="shebQuantity" id="txtShebQuantity" placeholder="Shegoygan Stock" readonly="true"></td>
                        </tr>
                        <tr id="rowSerialNumber">
                            <td><label for="txtSerialNumber">Serial Number:</label></td>
                            <td><input type="text" id="txtSerialNumber" placeholder="Serial Number"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" id="btnAddToList">Add</button>
                                <button class="btn btn-outline-primary btn-sm" id="btnClearForm">Clear Form</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <select id="selProductList" size=15 name="item-list" style="width:100%; height:200px;">
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" id="btnRemoveSelected">Remove Selected</button>
                                <button class="btn btn-outline-danger btn-sm" id="btnClearAll">Clear All</button>
                            </td>
                        </tr>`;

        var rightComlumnTradeInTemplate = `
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
                        <button class="btn btn-outline-dark btn-sm" id="btnAddOneToTradeInValue">Add $1</button>
                        <button class="btn btn-outline-dark btn-sm" id="btnSubtractOneFromTradeInValue">Subtract $1</button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button class="btn btn-outline-dark btn-sm" id="btnAddFiveToTradeInValue">Add $5</button>
                        <button class="btn btn-outline-dark btn-sm" id="btnSubtractFiveFromTradeInValue">Subtract $5</button>
                    </td>
                </tr>
                <tr>
                <tr>
                    <td><label for="txtTotalPaid">TOTAL PAID:</label></td>
                    <td><input type="number" name="trade-in-total-paid" id="txtTotalPaid" style="width:50%;">
                    <input type="number" id="txtCheckNumber" placeholder="Check #" style="width:40%;">
                    <span class="validation vTotalPaid"></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="radio" value ="creditPaid" id="rbStoreCredit" name="tradeInPayment">
                        <label for="rbStoreCredit">Store Credit</label>

                        <input type="radio" value ="cashPaid" id="rbCash" name="tradeInPayment">
                        <label for="rbCash">Cash</label>

                        <input type="radio" value ="checkPaid" id="rbCheck" name="tradeInPayment">
                        <label for="rbCheck">Check</label>
                    </td>
                </tr>
                <tr>
                    <td><label for="txtEmployee">Employee:</label></td>
                    <td><input type="text" id="txtEmployee" placeholder="Employee Initials" style="width:50%;">
                    <span class="validation vEmployee"></span></td>
                </tr>
                <tr>
                    <td><label for="taComments">Comments:</label></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea id="taComments" style="width:100%; height:100px;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn btn-outline-success btn-sm transaction-final" id="btnTradeIn">Trade In</button>
                    </td>
                </tr>
            </table>
        </div>`;

        var rightColumnPurchaseTemplate = `
                <tr>
                    <td><label for="txtTotalPaid">TOTAL PAID:</label></td>
                    <td><input type="number" name="trade-in-total-paid" id="txtTotalPaid" style="width:50%;">
                    <span class="validation vTotalPaid"></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="radio" value ="storeCreditReceived" id="rbStoreCredit" name="purchasePayment">
                        <label for="rbStoreCredit">Store Credit</label>

                        <input type="radio" value ="cashReceived" id="rbCash" name="purchasePayment">
                        <label for="rbCash">Cash</label>

                        <input type="radio" value ="creditReceived" id="rbCredit" name="purchasePayment">
                        <label for="rbCredit">Credit</label>
                    </td>
                </td>
                <tr>
                    <td><label for="txtEmployee">Employee:</label></td>
                    <td><input type="text" id="txtEmployee" placeholder="Employee Initials" style="width:50%;">
                    <span class="validation vEmployee"></span></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn btn-outline-success btn-sm transaction-final" id="btnSale">Sale</button>
                    </td>
                </tr>
            </table>
        </div>`;

        //inject HTML
        leftColumnContainer.innerHTML = leftColumnContainerTemplate;
        midColumnContainer.innerHTML = midColumnContainerTemplate;

        if(tradeIn != null){
            rightColumnContainer.innerHTML = rightColumnContainerTemplate + rightComlumnTradeInTemplate;
        }

        if(purchase != null){
            rightColumnContainer.innerHTML = rightColumnContainerTemplate + rightColumnPurchaseTemplate;
        }

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
        txtOnaQuantity = rightColumnContainer.querySelector("#txtOnaQuantity");
        txtEcQuantity = rightColumnContainer.querySelector("#txtEcQuantity");
        txtSpQuantity = rightColumnContainer.querySelector("#txtSpQuantity");
        txtShebQuantity = rightColumnContainer.querySelector("#txtShebQuantity");
        txtSerialNumber = rightColumnContainer.querySelector("#txtSerialNumber");

        rowSerialNumber = rightColumnContainer.querySelector("#rowSerialNumber");
        rowSerialNumber.style.display = "none";

        selProductList = rightColumnContainer.querySelector("#selProductList");

        rbStoreCredit = rightColumnContainer.querySelector("#rbStoreCredit");
        rbCash = rightColumnContainer.querySelector("#rbCash");
       
        rbGroup = document.querySelectorAll('input[name="tradeInPayment"]');

        txtTotalPaid = rightColumnContainer.querySelector("#txtTotalPaid");
        txtEmployee = rightColumnContainer.querySelector("#txtEmployee");

        //right column buttons
        btnAddToList = rightColumnContainer.querySelector("#btnAddToList").onclick = addProductForTransaction;
        btnClearForm = rightColumnContainer.querySelector("#btnClearForm").onclick = clearProductInfoTextBoxes;

        btnRemoveSelected = rightColumnContainer.querySelector("#btnRemoveSelected").onclick = removeSelectedClick;
        btnClearAll = rightColumnContainer.querySelector("#btnClearAll").onclick = function(){
            selectedProducts.length = 0;
            refreshSelectedProducts();

            calculateTradeInValue(selectedProducts);
        };

        if(tradeIn != null && purchase == null){

            txtTradeInCreditValue = rightColumnContainer.querySelector("#txtTradeInCreditValue");
            txtTradeInCashValue = rightColumnContainer.querySelector("#txtTradeInCashValue");
            txtItemTradeInCreditValue = rightColumnContainer.querySelector("#txtItemTradeInCreditValue");
            txtItemTradeInCashValue = rightColumnContainer.querySelector("#txtItemTradeInCashValue");
           
            txtCheckNumber = rightColumnContainer.querySelector("#txtCheckNumber");
            txtCheckNumber.style.visibility="hidden";

            btnAddOneToTradeInValue = rightColumnContainer.querySelector("#btnAddOneToTradeInValue");
            btnAddFiveToTradeInValue = rightColumnContainer.querySelector("#btnAddFiveToTradeInValue");
            btnSubtractOneFromTradeInValue = rightColumnContainer.querySelector("#btnSubtractOneFromTradeInValue");
            btnSubtractFiveFromTradeInValue = rightColumnContainer.querySelector("#btnSubtractFiveFromTradeInValue");

            rbCheck = rightColumnContainer.querySelector("#rbCheck");

            taComments = rightColumnContainer.querySelector('#taComments');
            

            btnAddOneToTradeInValue.addEventListener("click", function(){
                addOneToTradeInValue(totalTradeInCreditValue, totalTradeInCashValue);
            });
    
            btnAddFiveToTradeInValue.addEventListener("click", function(){
                addFiveToTradeInValue(totalTradeInCreditValue, totalTradeInCashValue);
            });
    
            btnSubtractOneFromTradeInValue.addEventListener("click", function(){
                removeOneFromTradeInValue(totalTradeInCreditValue, totalTradeInCashValue);
            });
    
            btnSubtractFiveFromTradeInValue.addEventListener("click", function(){
                removeFiveFromTradeInValue(totalTradeInCreditValue, totalTradeInCashValue);
            });

            btnTradeIn = rightColumnContainer.querySelector("#btnTradeIn").onclick = tradeInDBInsert;
        }

        if(purchase != null){
            rbCredit = rightColumnContainer.querySelector("#rbCredit");

            btnSale = rightColumnContainer.querySelector("#btnSale").onclick = purchaseDbInsert;
        }

       
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

       

        rbGroup.forEach(radio => radio.addEventListener("change", function(event){
            if(event.target.getAttribute("id") == "rbStoreCredit"){
                rbStoreCredit.checked = true;
                txtCheckNumber.style.visibility = "hidden";
            }else if(event.target.getAttribute("id") == "rbCash"){
                rbCash.checked = true;
                txtCheckNumber.style.visibility = "hidden";
            }else if(event.target.getAttribute("id") == "rbCheck"){
                rbCheck.checked = true;
                txtCheckNumber.style.visibility = "visible";
            }
        }));
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
            html += `<tr class="mid-table-row" productId="${products.productId}">
                            <td class="mid-table-cell">
                                ${products.consoleName}
                            </td>
                            <td class="mid-table-cell">
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
                console.log(response);
                var product = JSON.parse(response);
                populateProductForm(product);
                var consoleInName = product.productName.toLowerCase().includes("console");
                var systemInName = product.productName.toLowerCase().includes("system");

                if(consoleInName || systemInName){
                    rowSerialNumber.style.display = "table-row";
                }else{
                    rowSerialNumber.style.display = "none";
                }
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
        txtOnaQuantity.value = product.onaQuantity;
        txtEcQuantity.value = product.ecQuantity;
        txtSpQuantity.value = product.spQuantity;
        txtShebQuantity.value = product.shebQuantity;
        txtSerialNumber.value = product.serialNumber || "";
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
            onaQuantity: txtOnaQuantity.value,
            ecQuantity: txtEcQuantity.value,
            spQuantity: txtSpQuantity.value,
            shebQuantity: txtShebQuantity.value,
            serialNumber: txtSerialNumber.value
        };

        return product;
    }

    function createTradeInProductFromForm(){
        var values = calculateTradeInValue(createProductFromForm());
        var tradeInProduct = {
            tradeInId: null,
            productId: txtProductId.value,
            retailPrice: txtLoosePrice.value,
            cashValue: values[1],
            creditValue: values[0],
            serialNumber: txtSerialNumber.value 
        };

        return tradeInProduct;
    }

    function createPpFromForm(){
        var purchaseProduct = {
            purchaseId: null,
            productId: txtProductId.value,
            serialNumber: txtSerialNumber.value
        };

        return purchaseProduct;
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

    function tradeInDBInsert(){
        if(selectedProducts.length > 0){
            if(confirm("Are you sure you want to finalize this trade-in?")){
                if(validateTransactionInfo()){
                    var paymentMethod;
                    if(rbCash.checked){
                        paymentMethod = rbCash.value;
                    }else if(rbStoreCredit.checked){
                        paymentMethod = rbStoreCredit.value;
                    }else{
                        paymentMethod = rbCheck.value;
                    }

                    
                    tradeIn.tradeInEmployee = txtEmployee.value.toUpperCase();
                    tradeIn.totalPaid = parseFloat(txtTotalPaid.value).toFixed(2);
                    tradeIn.comments = taComments.value;

                    if(paymentMethod == "cashPaid"){
                        tradeIn.cashPaid = parseFloat(txtTotalPaid.value).toFixed(2);
                    }else if(paymentMethod == "creditPaid"){
                        tradeIn.creditPaid = parseFloat(txtTotalPaid.value).toFixed(2);
                    }else{
                        tradeIn.checkPaid = parseFloat(txtTotalPaid.value).toFixed(2);
                        tradeIn.checkNumber = txtCheckNumber.value;
                    }

                    namespace.ajax.send({
                        url: tiWebServiceAddress,
                        method: "POST",
                        headers: {"Content-Type": "application/json", "Accept": "application/json"},
                        requestBody: JSON.stringify(tradeIn),
                        callback: function(response){
                            completedTradeIn = JSON.parse(response);
                            console.log(completedTradeIn);
                            tradeInProductDBInsert();
                        },
                        errorCallback: function(response){
                            alert("TRADE IN ERROR: \n\r" + response);
                        }
                    });
                }
            }
        }else{
            alert("Please add product(s) to the transaction list.");
        }
    }

    function tradeInProductDBInsert(){
         tradeInProducts.forEach((tip) =>{
            tip.tradeInId = completedTradeIn.tradeInId;
            console.log(tip);

            namespace.ajax.send({
                url: webServiceAddress,
                method: "POST",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                requestBody: JSON.stringify(tip),
                callback: function(response){
                    clearProductInfoTextBoxes();
                    console.log(response);
                    updateTradeInQuantity();
                },
                errorCallback: function(response){
                    alert("ERROR: " + response);
                }
            });
        });
    }

    function updateTradeInQuantity(){
        var user = getUser();
        selectedProducts.forEach((p)=>{
            delete p.serialNumber;

            if(user == "onalaska"){
                p.onaQuantity++;
            }else if(user == "eauClaire"){
                p.ecQuantity++;
            }else if(user == "stevensPoint"){
                p.spQuantity++;
            }else if(user == "sheboygan"){
                p.shebQuantity++;
            }

            namespace.ajax.send({
                url: prodWebServiceAddress + p.productId,
                method: "PUT",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                requestBody: JSON.stringify(p),
                callback: function(response){
                    console.log(response);
                }
            });
            
        });
        selectedProducts.length = 0;
        refreshSelectedProducts();
        txtItemTradeInCreditValue.value = "";
        txtItemTradeInCashValue.value = "";
        txtTotalPaid.value="";
        rbStoreCredit.checked= false;
        rbCash.checked = false;
        rbCheck.checked = false;
        txtEmployee.value="";
        calculateTradeInValue(selectedProducts);

        returnToStart();
    }

    function purchaseDbInsert(){
        if(selectedProducts.length > 0){
            if(confirm("Are you sure you want to finalize this purchase?")){
                if(validateTransactionInfo()){
                    var paymentMethod;
                    if(rbCash.checked){
                        paymentMethod = rbCash.value;
                    }else if(rbStoreCredit.checked){
                        paymentMethod = rbStoreCredit.value;
                    }else{
                        paymentMethod = rbCredit.value;
                    }

                    
                    purchase.purchaseEmployee = txtEmployee.value.toUpperCase();
                    purchase.totalPurchasePrice = parseFloat(txtTotalPaid.value).toFixed(2);

                    if(paymentMethod == "cashReceived"){
                        purchase.cashReceived = parseFloat(txtTotalPaid.value).toFixed(2);
                    }else if(paymentMethod == "creditReceived"){
                        purchase.creditReceived = parseFloat(txtTotalPaid.value).toFixed(2);
                    }else{
                        purchase.storeCreditReceived = parseFloat(txtTotalPaid.value).toFixed(2);
                    }

                    namespace.ajax.send({
                        url: purchaseWebServiceAddress,
                        method: "POST",
                        headers: {"Content-Type": "application/json", "Accept": "application/json"},
                        requestBody: JSON.stringify(purchase),
                        callback: function(response){
                            completedPurchase = JSON.parse(response);
                            console.log(completedPurchase);
                            productPurchaseDbInsert();
                        },
                        errorCallback: function(response){
                            alert("PURCHASE ERROR: \n\r" + response);
                        }
                    });
                }
            }
        }else{
            alert("Please add product(s) to the transaction list.");
        }
    }

    function productPurchaseDbInsert(){
        purchaseProducts.forEach((pp) =>{
            pp.purchaseId = completedPurchase.purchaseId;
            console.log(pp);

            namespace.ajax.send({
                url: webServiceAddress,
                method: "POST",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                requestBody: JSON.stringify(pp),
                callback: function(response){
                    clearProductInfoTextBoxes();
                    console.log(response);
                    updatePurchaseQuantity();
                },
                errorCallback: function(response){
                    alert("ERROR: " + response);
                }
            });
        });
    }

    function updatePurchaseQuantity(){
        var user = getUser();
        selectedProducts.forEach((p)=>{
            delete p.serialNumber;

            if(user == "onalaska"){
                p.onaQuantity--;
            }else if(user == "eauClaire"){
                p.ecQuantity--;
            }else if(user == "stevensPoint"){
                p.spQuantity--;
            }else if(user == "sheboygan"){
                p.shebQuantity--;
            }

            namespace.ajax.send({
                url: prodWebServiceAddress + p.productId,
                method: "PUT",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                requestBody: JSON.stringify(p),
                callback: function(response){
                    console.log(response);
                }
            });
            
        });
        selectedProducts.length = 0;
        refreshSelectedProducts();
        txtTotalPaid.value="";
        rbStoreCredit.checked= false;
        rbCash.checked = false;
        rbCredit.checked = false;
        txtEmployee.value="";
        
        returnToStart();
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
                //console.log(response);
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
                //console.log(response);
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
        txtOnaQuantity.value="";
        txtEcQuantity.value="";
        txtSpQuantity.value="";
        txtShebQuantity.value="";

    }

    function refreshSelectedProducts(){
        selProductList.innerHTML = "";

        selectedProducts.forEach((p)=>{
            selProductList.innerHTML += `<option value="${p.productId}">${p.productName} - ${p.consoleName}</option>`
        });
    }

    function addProductForTransaction(){
        if(txtProductId.value == ""){
            alert("Please select a product to add to the pending transaction list.");
        }else{
            var product = createProductFromForm();
            selectedProducts.push(product);

            if(tradeIn != null && purchase == null){
                var tradeInProduct = createTradeInProductFromForm();
                tradeInProducts.push(tradeInProduct);
            
                //clearProductInfoTextBoxes();
                //refreshSelectedProducts();
                calculateTradeInValue(selectedProducts);
                txtItemTradeInCreditValue.value = "";
                txtItemTradeInCashValue.value = "";
            }else if(purchase != null && tradeIn == null){
                var purchaseProduct = createPpFromForm();
                purchaseProducts.push(purchaseProduct);
            }   
        }
        clearProductInfoTextBoxes();
        refreshSelectedProducts();
        rowSerialNumber.style.display = "none";
    }

    function removeSelectedProduct(productId){
        for(var i = 0; i < selectedProducts.length; i++){
            if(productId == selectedProducts[i].productId){
                selectedProducts.splice(i, 1);
                break;
            }
        }

        refreshSelectedProducts();

        if(tradeIn != null && purchase == null){
            calculateTradeInValue(selectedProducts);
            for(var i = 0; i < tradeInProducts.length; i++){
                if(productId == tradeInProducts[i].productId){
                    tradeInProducts.splice(i, 1);
                    break;
                }
            }
        }else if(purchase != null && tradeIn == null){
            for(var i = 0; i < purchaseProducts.length; i++){
                if(productId == purchaseProducts[i].productId){
                    purchaseProducts.splice(i, 1);
                    break;
                }
            }
        }
    }
    function removeSelectedClick(){
        if(selProductList.selectedIndex != -1){
            var id = selProductList.value;
            removeSelectedProduct(id);

            if(tradeIn != null && purchase == null){
                txtItemTradeInCreditValue.value = "";
                txtItemTradeInCashValue.value = "";
            }
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
                var consoleInName = selectedProducts[i].productName.toLowerCase().includes("console");
                var systemInName = selectedProducts[i].productName.toLowerCase().includes("system");
                
                if(consoleInName || systemInName){
                    rowSerialNumber.style.display = "table-row";
                }else{
                    rowSerialNumber.style.display = "none";
                }
                populateProductForm(selectedProducts[i]);

                if(tradeIn != null && purchase == null){
                    calculateTradeInValue(selectedProducts);
                }
            }
        }
    }

    function validateTransactionInfo(){
        rightColumnContainer.querySelector(".vTotalPaid").innerHTML = "";
        rightColumnContainer.querySelector(".vEmployee").innerHTML = "";

        if(tradeIn != null && purchase == null){
            if(rbStoreCredit.checked == false && rbCash.checked == false && rbCheck.checked == false){
                alert("Please select a method of payment.");
                return false;
            }
        }
        
        if(purchase != null && tradeIn == null){
            if(rbStoreCredit.checked == false && rbCash.checked == false && rbCredit.checked == false){
                alert("Please select a method of payment.");
                return false;
            }
        }


        if(txtTotalPaid.value == ""){
            rightColumnContainer.querySelector(".vTotalPaid").innerHTML = "Please enter a total amount";
            return false;
        }

        if(txtEmployee.value == ""){
            rightColumnContainer.querySelector(".vEmployee").innerHTML = "Please enter employee initials";
            return false;
        }

        if(!/^[a-zA-z]{2,3}$/.test(txtEmployee.value)){
            rightColumnContainer.querySelector(".vEmployee").innerHTML = "Initials must be 3 characters or less";
            return false;
        }

        return true;
    }

    function addOneToTradeInValue(){
        txtTradeInCreditValue.value = (parseFloat(txtTradeInCreditValue.value) + (1)).toFixed(2);
        txtTradeInCashValue.value = (parseFloat(txtTradeInCashValue.value) + (1)).toFixed(2);
    }

    function addFiveToTradeInValue(){
        txtTradeInCreditValue.value = (parseFloat(txtTradeInCreditValue.value) + (5)).toFixed(2);
        txtTradeInCashValue.value = (parseFloat(txtTradeInCashValue.value) + (5)).toFixed(2);
    }

    function removeOneFromTradeInValue(){
        txtTradeInCreditValue.value = (parseFloat(txtTradeInCreditValue.value) - (1)).toFixed(2);
        txtTradeInCashValue.value = (parseFloat(txtTradeInCashValue.value) - (1)).toFixed(2);
    }

    function removeFiveFromTradeInValue(){
        txtTradeInCreditValue.value = (parseFloat(txtTradeInCreditValue.value) - (5)).toFixed(2);
        txtTradeInCashValue.value = (parseFloat(txtTradeInCashValue.value) - (5)).toFixed(2);
    }

    function calculateTradeInValue(prods){
        var products = [];
        if(Array.isArray(prods)){
            products = [...prods];
        }else{
            products.push(prods);
        };
        
        var returnValues = [];
        
        totalTradeInCreditValue = 0;
        totalTradeInCashValue = 0;
        itemTradeInCreditValue = 0;
        itemTradeInCashValue = 0;
        products.forEach((p)=>{
            var consoleInName = p.productName.toLowerCase().includes("console");
            var systemInName = p.productName.toLowerCase().includes("system");
            if(!consoleInName && !systemInName){ 
                //GAME PRICING
                if(p.consoleName.toLowerCase() == "nes" || p.consoleName.toLowerCase() == "super nintendo" || p.consoleName.toLowerCase() == "nintendo 64"
                  || p.consoleName.toLowerCase() == "sega saturn" || p.consoleName.toLowerCase().includes("atari")){
                    totalTradeInCreditValue += Math.floor(p.loosePrice * 0.4);
                    totalTradeInCashValue += Math.floor(p.loosePrice * 0.3);
                    productCreditValue = Math.floor(p.loosePrice * 0.4);
                    productCashValue = Math.floor(p.loosePrice * 0.3);

                    if(productCreditValue < 1){
                        productCreditValue = 0.10;
                        productCashValue = 0.1;
                    }

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
                    productCreditValue = Math.floor(p.loosePrice * 0.25);
                    productCashValue = Math.floor(p.loosePrice * 0.1);

                    if(productCreditValue < 1){
                        productCreditValue = 0.10;
                        productCashValue = 0.1;
                    }

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
                    productCreditValue = Math.floor(p.loosePrice * 0.5);
                    productCashValue = Math.floor(p.loosePrice * 0.3);

                    if(productCreditValue < 1){
                        productCreditValue = 0.10;
                        productCashValue = 0.1;
                    }
                    
                    if(Math.floor(p.loosePrice * 0.5) < 1){
                        totalTradeInCreditValue += 0.1;
                    }

                    if(selProductList.value == p.productId){
                        itemTradeInCreditValue = Math.floor(p.loosePrice * 0.5);
                        itemTradeInCashValue = Math.floor(p.loosePrice * 0.3);

                        if(itemTradeInCreditValue < 1){
                            itemTradeInCreditValue = 0.1;
                        }

                        if(itemTradeInCashValue < 1){
                            itemTradeInCashValue = 0.01;
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
                    productCreditValue = Math.floor(p.loosePrice * 0.5);
                    productCashValue = Math.floor(p.loosePrice * 0.4);

                    if(productCreditValue < 1){
                        productCreditValue = 0.10;
                        productCashValue = 0.1;
                    }

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
                    productCreditValue = Math.floor(p.loosePrice * 0.3);
                    productCashValue = Math.floor(p.loosePrice * 0.2);

                    if(productCreditValue < 1){
                        productCreditValue = 0.10;
                        productCashValue = 0.1;
                    }

                    if(selProductList.value == p.productId){
                        itemTradeInCreditValue = Math.floor(p.loosePrice * 0.3);
                        itemTradeInCashValue = Math.floor(p.loosePrice * 0.2);
                    }
                }
            }else{
                totalTradeInCreditValue += Math.floor(p.loosePrice * 0.4);
                totalTradeInCashValue += Math.floor(p.loosePrice * 0.3);
                productCreditValue = Math.floor(p.loosePrice * 0.4);
                productCashValue = Math.floor(p.loosePrice * 0.3);

                if(productCreditValue < 1){
                    productCreditValue = 0.10;
                    productCashValue = 0.1;
                }

                if(selProductList.value == p.productId){
                    itemTradeInCreditValue = Math.floor(p.loosePrice * 0.4);
                    itemTradeInCashValue = Math.floor(p.loosePrice * 0.3);
                }
            }
        });

        txtTradeInCreditValue.value = totalTradeInCreditValue.toFixed(2);
        txtTradeInCashValue.value = totalTradeInCashValue.toFixed(2);
        txtItemTradeInCreditValue.value = itemTradeInCreditValue.toFixed(2);
        txtItemTradeInCashValue.value = itemTradeInCashValue.toFixed(2);

        returnValues[0] = productCreditValue;
        returnValues[1] = productCashValue;

        return returnValues;
    }

    function getUser(){
        var user;
        var cookies = document.cookie.split(";");

        for(var x = 0; x < cookies.length; x++){
            var name = cookies[x].split("=")[0].trim() || null;
            var value = cookies[x].split("=")[1] || null;
            
            if(name == "ggUserName"){
                user = value;
                break;
            }
        }

        if(name != "ggUserName"){
            user = null;
            alert("Invalid user. Please log out and log in again.");
        }

        return user;
    }

    function returnToStart(){
        tradeIn = null;
        purchase = null;
        selectedProducts.length = 0;
        tradeInProducts.length = 0;

        namespace.CustomerModule({
            leftColumnContainer: document.getElementById("left-column"),
		    midColumnContainer : document.getElementById("mid-column"),
		    rightColumnContainer: document.getElementById("right-column"),
		    //webServiceAddress: "https://localhost/GG/web-services/customers/"
		    //webServiceAddress: "https://www.dylanisensee.com/gg/web-services/customers/"
        });
    }


}