var namespace = namespace || {};

namespace.PurchaseModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress || "https://localhost/GG/web-services/purchases/" //THIS IS REQUIRED!!

    var ppWebServiceAddress = "https://localhost/GG/web-services/productpurchases/";
    //var ppWebServiceAddress = "https://www.dylanisensee.com/gg/web-services/tradeinproducts/";

    var user = document.getElementById('store_user').value;
    var customer = options.customer; //REQUIRED TO WORK PROPERLY

    var purchaseProducts = [];
    
    //mid column vars
    var purchaseTableListContainer;
    var purchaseTable;
    var purchaseTableRow;

    var ascendArrow;
    var descendArrow;

    var btnBack;
    var btnNewPurchase;

    //right column vars
    var txtSerialNumber;

    var selProductList;


    initialize();
    
    
    function initialize(){
        leftColumnContainer.innerHTML = "";
        midColumnContainer.innerHTML = "";
        rightColumnContainer.innerHTML = "";

        leftColumnContainer.style.display = 'none';
        midColumnContainer.style.width = '61%';

        var midColumnContainerTemplate =`
            <div id="mid-table-list">
                <table id="mid-table">
                </table>
            </div>
            <div class="mid-button-container">
                <button class="btn btn-outline-primary btn=sm" id="btnBack">Back To Customers</button>
                <button class="btn btn-outline-primary btn=sm" id="btnNewPurchase">New Purchase</button>
            </div>`;

        var rightColumnContainerTemplate=`
            <div class="info">
                <form>
                    <table class="info-pane" style="width:95%">
                        <tr>
                            <td><label for="txtSerialNumber">Serial Number:</label></td>
                            <td><input type="text" name="txtSerialNumber" id="txtSerialNumber" placeholder="Serial Number"></td>
                        </tr>
                        
                        <tr>
                            <td colspan="2">
                                <select id="selProductList" size=15 name="item-list" style="width:100%; height:200px;">
                                </select>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>`;

        console.log(customer);
        //mid column vars
        midColumnContainer.innerHTML = midColumnContainerTemplate;
        rightColumnContainer.innerHTML = rightColumnContainerTemplate;

        purchaseTableListContainer = midColumnContainer.querySelector('#mid-table-list');
        purchaseTable = midColumnContainer.querySelector('#mid-table');
        

        btnBack = midColumnContainer.querySelector("#btnBack");
        btnNewPurchase = midColumnContainer.querySelector("#btnNewPurchase");
        //right column vars
        txtSerialNumber = rightColumnContainer.querySelector("#txtSerialNumber");
        selProductList = rightColumnContainer.querySelector("#selProductList");

        //eventHandlers
        //purchaseTable.addEventListener("click", populateProdsByPurchase);
        selProductList.addEventListener("change", populateFormFromSelectBox);
        btnNewPurchase.addEventListener("click", createNewPurchase);
        btnBack.addEventListener("click", backToCustomerModule);

        //load customer purchases
        getPurchasesByCustomerId(customer.customerId);
    }

    function getPurchasesByCustomerId(customerId){
        if(customerId != 0){
            namespace.ajax.send({
                url: webServiceAddress + "customer" + customerId,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    var purchases = JSON.parse(response);
                    generatePurchaseList(purchases);
                },
                errorCallback: function(){
                    purchaseTableListContainer.innerHTML += `<p style="text-align:right;">Customer does not have any purchases.</p>`
                }
            });
        }else{
            alert("ERROR: Customer object is undefined. Please refresh and chooose a customer.");
        }
    }

    function sortPurchasesAscending(customerId){
        if(customerId != 0){
            namespace.ajax.send({
                url: webServiceAddress + "customerascending" + customerId,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    var purchases = JSON.parse(response);
                    generatePurchaseList(purchases);
                },
                errorCallback: function(){
                    purchaseTableListContainer.innerHTML += `<p style="text-align:right;">Customer does not have any purchases.</p>`
                }
            });
        }else{
            alert("ERROR: Customer object is undefined. Please refresh and chooose a customer.");
        }
    }

    function sortPurchasesDescending(customerId){
        if(customerId != 0){
            namespace.ajax.send({
                url: webServiceAddress + "customerdescending" + customerId,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    var purchases = JSON.parse(response);
                    generatePurchaseList(purchases);
                },
                errorCallback: function(){
                    purchaseTableListContainer.innerHTML += `<p style="text-align:right;">Customer does not have any purchases.</p>`
                }
            });
        }else{
            alert("ERROR: Customer object is undefined. Please refresh and chooose a customer.");
        }
    }

    function populateProdsByPurchase(evt){
        selProductList.innerHTML = "";
        purchaseProducts = [];
        var target = evt.target;
        if(target.classList.contains("mid-table-cell")){
            var selectedPurchaseId = target.closest("tr").getAttribute("purchaseId");
        }
        var pps=[];
        if(selectedPurchaseId != 0){
            namespace.ajax.send({
                url: ppWebServiceAddress + "product" + selectedPurchaseId,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    pps = JSON.parse(response);
                    for(var x = 0; x<pps.length; x++){
                        purchaseProducts.push(pps[x]);
                    }

                    populateSelBoxProds();
                }
            });
        }else{
            return false;
        }
    }

    function generatePurchaseList(purchases){
        purchaseTableListContainer.innerHTML = `<p style="text-align:right;">${customer.customerFirstName} ${customer.customerLastName} - Purchases</p>`;
        var html = `<tr>
                        <th>Purchase Date
                            <div class="icons">
                                <i class="fas fa-angle-up ascend"></i>
                                <i class="fas fa-angle-down descend"></i>
                            </div>
                        </th>
                        <th>Employee</th>
                        <th>Cash Received</th>
                        <th>Credit Received</th>
                        <th>Store Credit Received</th>
                        <th>Total Purchase Price</th>
                    </tr>`;
        if(Array.isArray(purchases)){
            for(var x = 0; x < purchases.length; x++){
                html+= `<tr class="mid-table-row" purchaseId="${purchases[x].purchaseId}">
                            <td class="mid-table-cell">
                                ${purchases[x].purchaseDateTime}
                            </td>
                            <td class="mid-table-cell">
                                ${purchases[x].purchaseEmployee}
                            </td>
                            <td class="mid-table-cell">
                                ${purchases[x].cashReceived}
                            </td>
                            <td class="mid-table-cell">
                                ${purchases[x].creditReceived}
                            </td>
                            <td class="mid-table-cell">
                                ${purchases[x].storeCreditReceived}
                            </td>
                            <td class="mid-table-cell">
                                ${purchases[x].totalPurchasePrice}
                            </td>
                        </tr>`;
            }
        }else{ 
            html+= `<tr class="mid-table-row" purchaseId="${purchases.purchaseId}">
                        <td class="mid-table-cell">
                            ${purchases.purchaseDateTime}
                        </td>
                        <td class="mid-table-cell">
                            ${purchases.purchaseEmployee}
                        </td>
                        <td class="mid-table-cell">
                            ${purchases.cashReceived}
                        </td>
                        <td class="mid-table-cell">
                            ${purchases.creditReceived}
                        </td>
                        <td class="mid-table-cell">
                            ${purchases.storeCreditReceived}
                        </td>
                        <td class="mid-table-cell">
                            ${purchases.totalPurchasePrice}
                        </td>
                    </tr>`;
        }

        purchaseTable.innerHTML = html;
        purchaseTableListContainer.appendChild(purchaseTable);

        purchaseTableRow = midColumnContainer.querySelectorAll('.mid-table-row');
        ascendArrow = midColumnContainer.querySelector('.ascend');
        descendArrow = midColumnContainer.querySelector('.descend');
        
        ascendArrow.addEventListener('click', function(){sortPurchasesAscending(customer.customerId)});
        descendArrow.addEventListener('click', function(){sortPurchasesDescending(customer.customerId)});


        for(const row of purchaseTableRow) {
            row.addEventListener('click', populateProdsByPurchase);
        }

        return purchaseTable;
    }

    function populateFormFromSelectBox(){
        txtSerialNumber.value = "";

        for(var i = 0; i < purchaseProducts.length; i++){
            if(purchaseProducts[i].productId == selProductList.value){
                populatePpForm(purchaseProducts[i]);
            }
        }
    }

    function populatePpForm(purchaseProduct){
        txtSerialNumber.value = purchaseProduct.serialNumber;
    }

    function populateSelBoxProds(){
        selProductList.innerHTML = '';

        purchaseProducts.forEach((p)=>{
            selProductList.innerHTML += `<option value="${p.productId}">${p.consoleName} - ${p.productName}</option>`
        });
    }

    function createNewPurchase(){
        var purchaseToAdd = {
            purchaseId: 0,
            customerId: customer.customerId,
            //purchaseDateTime: new Date(),
            purchaseEmployee: "GG",
            cashReceived: 0.00,
            creditReceived: 0.00,
            storeCreditReceived: 0.00,
            totalPurchasePrice: 0.00,
            location: user
        };

        namespace.ProductModule({
            leftColumnContainer: document.getElementById("left-column"),
            midColumnContainer : document.getElementById("mid-column"),
            rightColumnContainer: document.getElementById("right-column"),
            webServiceAddress: "https://localhost/GG/web-services/productpurchases/",
            //webServiceAddress: "https://www.dylanisensee.com/gg/web-services/productpurchases/",
            purchase: purchaseToAdd
        });
    }

    function backToCustomerModule(){
        customer = {};

        namespace.CustomerModule({
            leftColumnContainer: document.getElementById("left-column"),
		    midColumnContainer : document.getElementById("mid-column"),
		    rightColumnContainer: document.getElementById("right-column"),
		    webServiceAddress: "https://localhost/GG/web-services/customers/"
		    //webServiceAddress: "https://www.dylanisensee.com/gg/web-services/customers/"
        });
    }


    return createNewPurchase;
}