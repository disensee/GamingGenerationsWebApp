var namespace = namespace || {};

namespace.TradeInModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress || "https://localhost/GG/web-services/tradeins/" //THIS IS REQUIRED!!

    var tipWebServiceAddress = "https://localhost/GG/web-services/tradeinproducts/";
    //var tipWebServiceAddress = "https://www.dylanisensee.com/gg/web-services/tradeinproducts/";

    var customer = options.customer; //REQUIRED TO WORK PROPERLY
    var user = document.getElementById('store_user').value;

    if(customer === user){
        var selectedStore = options.selectedStore;
        var sDate = options.sDate;
        var eDate = options.eDate;
    }

    var tradeInProducts = [];
    
    //mid column vars
    var tradeInTableListContainer;
    var tradeInTable;

    var tradeInTableRow;

    var ascendArrow;
    var descendArrow;

    var btnBack;
    var btnNewTradeIn;

    //right column vars
    var txtSerialNumber;
    var txtRetailPrice;
    var txtCashValue;
    var txtCreditValue;

    var selProductList;
    var taComments;

    initialize();

    function initialize(){
        leftColumnContainer.innerHTML = "";
        midColumnContainer.innerHTML = "";
        rightColumnContainer.innerHTML = "";

        leftColumnContainer.style.display = 'none';
        midColumnContainer.style.width = '61%';
        rightColumnContainer.style.display = 'block';

        
            
        if(customer === user){
            var midColumnContainerTemplate =`
            <div id="mid-table-list">
                <table id="mid-table">
                </table>
            </div>
            
            <div class="refresh">
                <button class="btn btn-outline-primary btn=sm" id="btnBackMan">Back</button>
            </div>`;
        }else {
            var midColumnContainerTemplate =`
            <div id="mid-table-list">
                <table id="mid-table">
                </table>
            </div>
            
            <div class="mid-button-container">
                <button class="btn btn-outline-primary btn=sm" id="btnBack">Back To Customers</button>
                <button class="btn btn-outline-primary btn=sm" id="btnNewTradeIn">New Trade In</button>
            </div>`;
        }

        var rightColumnContainerTemplate=`
            <div class="info">
                <form>
                    <table class="info-pane" style="width:95%">
                        <tr>
                            <td><label for="txtSerialNumber">Serial Number:</label></td>
                            <td><input type="text" name="txtSerialNumber" id="txtSerialNumber" placeholder="Serial Number"></td>
                        </tr>
                        <tr>
                            <td><label for="txtRetailPrice">Retail Price:</label></td>
                            <td><input type="text" name="txtRetailPrice" id="txtRetailPrice" placeholder="Retail Price"></td>
                        </tr>
                        <tr>
                            <td><label for="txtcashValue">Cash Value:</label></td>
                            <td><input type="text" name="txtCashValue" id="txtCashValue" placeholder="Cash Value"></td>
                        </tr>
                        <tr>
                            <td><label for="txtCreditValue">Credit Value:</label></td>
                            <td><input type="text" name="txtCreditValue" id="txtCreditValue" placeholder="Credit Value"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <select id="selProductList" size=15 name="item-list" style="width:100%; height:200px;">
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="taComments">Comments:</label></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea id="taComments" readonly="true" style="width:100%; height:100px;">
                                </textarea>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>`;

        console.log(customer);
        //mid column vars
        midColumnContainer.innerHTML = midColumnContainerTemplate;
        rightColumnContainer.innerHTML = rightColumnContainerTemplate;

        tradeInTableListContainer = midColumnContainer.querySelector('#mid-table-list');
        tradeInTable = midColumnContainer.querySelector('#mid-table');

        btnBack = midColumnContainer.querySelector("#btnBack");
        btnNewTradeIn = midColumnContainer.querySelector("#btnNewTradeIn");
        //right column vars
        txtSerialNumber = rightColumnContainer.querySelector("#txtSerialNumber");
        txtRetailPrice = rightColumnContainer.querySelector("#txtRetailPrice");
        txtCashValue = rightColumnContainer.querySelector("#txtCashValue");
        txtCreditValue = rightColumnContainer.querySelector("#txtCreditValue");
        selProductList = rightColumnContainer.querySelector("#selProductList");
        taComments = rightColumnContainer.querySelector('#taComments');

        //eventHandlers
        if(user != customer){
            selProductList.addEventListener("change", populateFormFromSelectBox);
            btnNewTradeIn.addEventListener("click", createNewTradeIn);
            btnBack.addEventListener("click", backToCustomerModule);
        }else {
            document.getElementById('btnBackMan').addEventListener('click', function(){
                location.reload();
            });
        }

        //get customer trade ins if store account is logged in
        if(customer.customerId){
            getTradeInsByCustomerId(customer.customerId);
        }else if(customer === user){
            midColumnContainer.style.margin = '';
            getTradeInsByDate(selectedStore, sDate, eDate);
        }
    }

    function getTradeInsByCustomerId(customerId){
        if(customerId != 0){
            namespace.ajax.send({
                url: webServiceAddress + "customer" + customerId,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    var tradeIns = JSON.parse(response);
                    generateTradeInList(tradeIns);
                },
                errorCallback: function(){
                    tradeInTableListContainer.innerHTML += `<p style="text-align:right;">Customer does not have any trade-ins.</p>`
                }
            });
        }else{
            alert("ERROR: Customer object is undefined. Please refresh and chooose a customer.");
        }
    }

    function getTradeInsByDate(location, startDate, endDate){
        if(location && startDate && endDate){
            //console.log (`STORE: ${location} SDATE: ${startDate} EDATE: ${endDate}`);
            //console.log(location); 
            namespace.ajax.send({
                url: webServiceAddress + `${location}/${startDate}/${endDate}`,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    var tradeIns = JSON.parse(response);
                    generateTradeInList(tradeIns);
                },
                errorCallback: function(){

                }
            });
        }else {
            alert("ERROR: There has been an error in getting the specified trade ins. Please contact your system administrator");
        }
    }

    function sortTradeInsAscending(customerId){
        if(customerId != 0){
            namespace.ajax.send({
                url: webServiceAddress + "customerascending" + customerId,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    var tradeIns = JSON.parse(response);
                    generateTradeInList(tradeIns);
                },
                errorCallback: function(){
                    tradeInTableListContainer.innerHTML += `<p style="text-align:right;">Customer does not have any trade-ins.</p>`
                }
            });
        }else{
            alert("ERROR: Customer object is undefined. Please refresh and chooose a customer.");
        }
    }

    function sortTradeInsDescending(customerId){
        if(customerId != 0){
            namespace.ajax.send({
                url: webServiceAddress + "customerdescending" + customerId,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    var tradeIns = JSON.parse(response);
                    generateTradeInList(tradeIns);
                },
                errorCallback: function(){
                    tradeInTableListContainer.innerHTML += `<p style="text-align:right;">Customer does not have any trade-ins.</p>`
                }
            });
        }else{
            alert("ERROR: Customer object is undefined. Please refresh and chooose a customer.");
        }
    }

    function populateProdsByTradeIn(evt){
        selProductList.innerHTML = "";
        tradeInProducts = [];
        var target = evt.target;
        if(target.classList.contains("mid-table-cell")){
            var selectedTradeInId = target.closest("tr").getAttribute("tradeInId");
        }
        var tips=[];
        if(selectedTradeInId != 0){
            namespace.ajax.send({
                url: tipWebServiceAddress + "product" + selectedTradeInId,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    tips = JSON.parse(response);
                    for(var x = 0; x<tips.length; x++){
                        tradeInProducts.push(tips[x]);
                    }
                    getTradeInComments(selectedTradeInId);
                    populateSelBoxProds();
                }
            });
        }else{
            return false;
        }
    }

    function getTradeInComments(tradeInId){
        namespace.ajax.send({
            url: webServiceAddress + tradeInId,
            method: "GET",
            headers: {"Content-Type": "application/json", "Accept": "application/json"},
            callback: function(response){
                var tradeIn = JSON.parse(response);
                taComments.value = tradeIn.comments;
            }
        });
    }

    function generateTradeInList(tradeIns){
        var html;
        if(user === customer){
            var html = 
                    `<tr>
                        <th>Trade In Date</th>
                        <th>Employee</th>
                        <th>Cash Paid</th>
                        <th>Credit Paid</th>
                        <th>Check Paid</th>
                        <th>Total Paid</th>
                    </tr>`;
        }else{
            tradeInTableListContainer.innerHTML = `<p style="text-align:right;">${customer.customerFirstName} ${customer.customerLastName} - Trade Ins</p>`;

            var html = 
                    `<tr>
                        <th>Trade In Date
                            <div class="icons">
                                <i class="fas fa-angle-up ascend"></i>
                                <i class="fas fa-angle-down descend"></i>
                            </div>
                        </th>
                        <th>Employee</th>
                        <th>Cash Paid</th>
                        <th>Credit Paid</th>
                        <th>Check Paid</th>
                        <th>Total Paid</th>
                     </tr>`;
        }

       
        if(Array.isArray(tradeIns)){
            for(var x = 0; x < tradeIns.length; x++){
                html+= `<tr class="mid-table-row" tradeInId="${tradeIns[x].tradeInId}">
                            <td class="mid-table-cell">
                                ${tradeIns[x].tradeInDateTime}
                            </td>
                            <td class="mid-table-cell">
                                ${tradeIns[x].tradeInEmployee}
                            </td>
                            <td class="mid-table-cell">
                                ${tradeIns[x].cashPaid}
                            </td>
                            <td class="mid-table-cell">
                                ${tradeIns[x].creditPaid}
                            </td>
                            <td class="mid-table-cell">
                                ${tradeIns[x].checkPaid}
                            </td>
                            <td class="mid-table-cell">
                                ${tradeIns[x].totalPaid}
                            </td>
                        </tr>`;
            }
        }else{ 
            html+= `<tr class="mid-table-row" tradeInId="${tradeIns.tradeInId}">
                        <td class="mid-table-cell">
                            ${tradeIns.tradeInDateTime}
                        </td>
                        <td class="mid-table-cell">
                            ${tradeIns.tradeInEmployee}
                        </td>
                        <td class="mid-table-cell">
                            ${tradeIns.cashPaid}
                        </td>
                        <td class="mid-table-cell">
                            ${tradeIns.creditPaid}
                        </td>
                        <td class="mid-table-cell">
                            ${tradeIns.checkPaid}
                        </td>
                        <td class="mid-table-cell">
                            ${tradeIns.totalPaid}
                        </td>
                    </tr>`;
        }

        tradeInTable.innerHTML = html;
        tradeInTableListContainer.appendChild(tradeInTable);

        tradeInTableRow = midColumnContainer.querySelectorAll('.mid-table-row');

        if(user != customer){
            ascendArrow = midColumnContainer.querySelector('.ascend');
            descendArrow = midColumnContainer.querySelector('.descend');

            ascendArrow.addEventListener('click', function(){sortTradeInsAscending(customer.customerId)});
            
            descendArrow.addEventListener('click', function(){sortTradeInsDescending(customer.customerId)});
        }
        
        for(const row of tradeInTableRow) {
            row.addEventListener('click', populateProdsByTradeIn);
        }

        return tradeInTable;
    }

    function populateFormFromSelectBox(){
        for(var i = 0; i < tradeInProducts.length; i++){
            if(tradeInProducts[i].productId == selProductList.value){
                populateTipForm(tradeInProducts[i]);
            }
        }
    }

    function populateTipForm(tradeInProduct){
        txtSerialNumber.value = tradeInProduct.serialNumber;
        txtRetailPrice.value = tradeInProduct.retailPrice;
        txtCashValue.value = tradeInProduct.cashValue;
        txtCreditValue.value = tradeInProduct.creditValue;
    }

    function populateSelBoxProds(){
        selProductList.innerHTML = '';

        tradeInProducts.forEach((p)=>{
            selProductList.innerHTML += `<option value="${p.productId}">${p.consoleName} - ${p.productName}</option>`
        });
    }

    function createNewTradeIn(){
        if(customer.customerId == ""){
            alert("Customer ID number is not stored. Please edit customer information in order to proceed.");
            return false;
        }

        var tradeInToAdd = {
            tradeInId: 0,
            customerId: customer.customerId,
            //tradeInDateTime: new Date(),
            tradeInEmployee: "GG",
            cashPaid: 0.00,
            creditPaid: 0.00,
            checkPaid: 0.00,
            checkNumber: 0,
            totalPaid: 0.00,
            location: user
        };

        namespace.ProductModule({
            leftColumnContainer: document.getElementById("left-column"),
            midColumnContainer : document.getElementById("mid-column"),
            rightColumnContainer: document.getElementById("right-column"),
            webServiceAddress: "https://localhost/GG/web-services/tradeinproducts/",
            //webServiceAddress: "https://www.dylanisensee.com/gg/web-services/tradeinproducts/",
            tradeIn: tradeInToAdd
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


    return createNewTradeIn;
}