var namespace = namespace || {};

namespace.InventoryModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress || "http://localhost/GG/web-services/products/"; //THIS IS REQUIRED!


    var consoleArr = ["NES", "Super Nintendo", "Nintendo 64", "Gamecube", "Wii", "Wii U", "Nintendo Switch", "GameBoy", "GameBoy Color", "GameBoy Advance", 
    "Nintendo DS", "Nintendo 3DS", "Playstation", "Playstation 2", "Playstation 3", "Playstation 4", "PSP", "Playstation Vita", 
    "Xbox", "Xbox 360", "Xbox One", "Sega Genesis", "Sega Saturn", "Sega Dreamcast", "Atari 2600", "Atari 400", "Atari 5200", "Atari 7800", "Atari Lynx", "Atari ST" ];
    
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

    var taProductList;

    //right column buttons
    var btnAddToList;
    var btnTradeIn;
    var btnSale;

    initialize();

    function initialize(){
        //TO DO: AUTHENTICATE USER

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
                <table id="product-table">
                </table>
            </div>`;
        
        var rightColumnContainerTemplate = `
            <div class="info">
                <table class="info-pane">
                    <form>
                        <tr>
                            <td><label for="productId">Product ID:</label></td>
                            <td><input type="text" name="productId" id="txtProductId" placeholder="Product ID" readonly="true"></td>
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
                            <td><input type="button" value="Add" id="btnAdd"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea id="taProductList" name="item-list" style="width:500px; height:200px;">A list of games that are for trade or sale populates here</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                            <input type="button" value="Trade-In" id="btnTradeIn">
                            <input type="button" value="Sale" id="btnSale">
                            </td>
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
        btnSearchByProdName = leftColumnContainer.querySelector("#btnSearchByProdName");

        //search by upc
        txtSearchUpc = leftColumnContainer.querySelector("#txtSearchUpc");
        btnSearchByUpc = leftColumnContainer.querySelector("#btnSearchByUpc");

        //console link list
        consoleList = leftColumnContainer.querySelector("#consoleList");

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
        taProductList = rightColumnContainer.querySelector("#taProductList");
        
        //event handlers
        btnSearchByProdName.addEventListener("click", searchByProductName);
        btnSearchByUpc.addEventListener("click", searchByUpc);

        consoleList.addEventListener("click", getProductsByConsoleName);
        productTable.addEventListener("click", selectProductInList);
        //getProductsByConsoleName("NES");
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
        var consoleName = consoleSelectBox.value;
        var productName = txtSearchProduct.value;

        getProductsByProductName(productName, consoleName);
        clearSearchTextBoxes();
    }

    function searchByUpc(){
        var upc = txtSearchUpc.value;
        
        getProductByUpc(upc);
        clearSearchTextBoxes();
    }

    function createConsoleSelectBox(arr){
        var selectBox = document.createElement("select");
        selectBox.setAttribute("id", "consoleSelectBox");
        var opt0 = document.createElement("option");
        
        opt0.value = "0";
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
};