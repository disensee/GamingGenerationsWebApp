var namespace = namespace || {};

namespace.InventoryModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress || "http://localhost/GG/web-services/products/"; //THIS IS REQUIRED!


    var consoleArr = ["NES", "SNES", "Nintendo 64", "Gamecube", "Wii", "Wii U", "Switch", "Gameboy", "Gameboy Color", "Gameboy Advance", 
    "Nintendo DS", "Nintendo 3DS", "Playstaion", "Playstaion 2", "Playstation 3", "Playstaion 4", "PSP", "PS Vita", 
    "Xbox", "Xbox 360", "Xbox One", "Sega Genesis", "Sega Saturn", "Sega Dreamcast", "Atari"];
    
    var header;
    var footer;
    var productTableListContainer;

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
               `<input type="text" placeholder="Enter product name"><br>
                <input type="submit" value="Search"><br>
                <p>Searby by UPC:</p>
                <input type="text" placeholder="Enter UPC">
                <input type="submit" value="Search">
            </div>
            <div id="list-container">
                <p>Filter:</p>` +
                createConsoleList(consoleArr) +
            `</div>`;

        var midColumnContainerTemplate = `
            <div id="product-table-list">

            </div>`;
        
        var rightColumnContainerTemplate = `
            <div class="info">
                <table class="info-pane">
                    <form>
                        <tr>
                            <td><label for="upc">UPC:</label></td>
                            <td><input type="text" name="upc" id="upc" placeholder="Enter UPC"></td>
                        </tr>
                        <tr>
                            <td><label>Console:</label></td>
                            <td>` + createConsoleSelectBox(consoleArr) + `</td>
                        </tr>
                        <tr>
                            <td><label for="item">Item:</label></td>
                            <td><input type="text" name="item" id="item" placeholder="Scan UPC for item" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="value">Gamestop Value:</label></td>
                            <td><input type="text" name="value" id="value" placeholder="Scan UPC for value"  readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="quantity">Quantity in stock:</label></td>
                            <td><input type="text" name="quantity" readonly="true"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="button" value="Add" id="btnAdd"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea name="item-list" style="width:500px; height:200px;">A list of games that are for trade or sale populates here</textarea>
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

        header = document.querySelector("#header");
        header.innerHTML = `<img src=images/gg-logo.jpg><p>Gaming Generations Inventory<p>`;
        leftColumnContainer.innerHTML = leftColumnContainerTemplate;
        midColumnContainer.innerHTML = midColumnContainerTemplate;
        rightColumnContainer.innerHTML = rightColumnContainerTemplate;
        footer = document.querySelector("#footer");
        footer.innerHTML=`Gaming Generations &copy;2020`;

        productTableListContainer = document.getElementById("product-table-list");

        getAllProducts();
    }

    function generateProductList(products){
        productTableListContainer.innerHTML =`<h4>PRODUCTS</h4>`;
        var html = `<tr>
                        <th>Console</th>
                        <th>Product</th>`;
        
        for(var x = 0; x < products.length; x++){
            html += `<tr productId="${products[x].productId}">
                        <td>
                            ${products[x].consoleName}
                        </td>
                        <td>
                            ${products[x].productName}
                        </td>
                    </tr>`;
        }
        var prodTable = document.createElement("table");
        //prodTable.id = "product-table";

        prodTable.innerHTML = html;
        productTableListContainer.appendChild(prodTable);
        return prodTable;
    }

    function getAllProducts(){
        namespace.ajax.send({
            url: webServiceAddress,
            method: "GET",
            callback: function(response){
                console.log(response);
                var products = JSON.parse(response);
                generateProductList(products);
            }
        });
    }

    function getProductsByConsoleName(){
        namespace.ajax.send({
            url: webServiceAddress + "NES",
            method: "GET",
            callback: function(response){
                console.log(response);
                var products = JSON.parse(response);
                generateProductList(products);
            }
        });
    }

    function createConsoleSelectBox(arr){
        var selectBox = document.createElement("select");
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
            li.innerHTML=`<a class='list-anchor' href='#'>` + c + `</a>`;
            ul.appendChild(li);
        });

        return ul.outerHTML;
    }
};