var namespace = namespace || {};

namespace.CustomerModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress || "http://localhost/GG/web-services/customers/" //THIS IS REQUIRED!!

    //left column search vars
    var txtSearchCustomer;
    var btnSearchByCustomerName;

    var vSearch;

    //mid column data vars
    var customerTableListContainer;
    var customerTable;

    //right column vars
    var txtCustomerId;
    var txtCustomerFirstName;
    var txtCustomerLastName;
    var txtCustomerIdNumber;
    var txtCustomerEmail;
    var txtCustomerPhone;

    var btnAdd;
    var btnSaveCustomer;
    var btnClear;
    var btnTradeIn;
    var btnPurchase;

    var vFirstName;
    var vLastName;
    var vIdNumber;
    var vEmail;
    var vPhone;

    var customerForTransaction;

    initialize();

    function initialize(){
        leftColumnContainer.innerHTML = "";
        midColumnContainer.innerHTML = "";
        rightColumnContainer.innerHTML = "";

        var leftColumnContainerTemplate = `
            <div id="search-container">
                <p>Search for customer:</p><br>
                <span class="validation" id="vSearch" style="display: block;"></span>
                <input type="text" id="txtSearchCustomer" placeholder="Enter customer name"><br>
                <input id="btnSearchByCustomerName" type="button" value="Search"><br>
            </div>`;

        var midColumnContainerTemplate =`
            <div id="mid-table-list">
                <p style="text-align:right;">Search for a customer to see results</p>
                <table id="mid-table">
                </table>
            </div>`;

        var rightColumnContainerTemplate=`
            <div class="info">
                <table class="info-pane">
                    <form>
                        <tr>
                            <td><label for="customerId">Customer ID:</label></td>
                            <td><input type="text" name="customerId" id="txtCustomerId" placeholder="Customer ID" readonly="true" value="0"></td>
                        </tr>
                        <tr>
                            <td><label for="customerFirstName">First Name:</label></td>
                            <td><input type="text" name="customerFirstName" id="txtCustomerFirstName" placeholder="First Name"></td>
                            <td><span class="validation" id="vFirstName"></span></td>
                        </tr>
                        <tr>
                            <td><label for="customerLastName">Last Name:</label></td>
                            <td><input type="text" name="customerLastName" id="txtCustomerLastName" placeholder="Last Name"></td>
                            <td><span class="validation" id="vLastName"></span></td>
                        </tr>
                        <tr>
                            <td><label for="customerIdNumber">ID Number:</label></td>
                            <td><input type="text" name="customerIdNumber" id="txtCustomerIdNumber" placeholder="ID #"></td>
                            <td><span class="validation" id="vIdNumber"></span></td>
                        </tr>
                        <tr>
                            <td><label for="customerEmail">Email Address:</label></td>
                            <td><input type="text" name="customerEmail" id="txtCustomerEmail" placeholder="Email Address"></td>
                            <td><span class="validation" id="vEmail"></span></td>
                        </tr>
                        <tr>
                            <td><label for="customerPhone">Phone Number:</label></td>
                            <td><input type="text" name="customerPhone" id="txtCustomerPhone" placeholder="Phone Number"></td>
                            <td><span class="validation" id="vPhone"></span></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" id="btnAdd">Add</button>
                                <button class="btn btn-outline-success btn-sm" id="btnSaveCustomer">Save</button>
                                <button class="btn btn-outline-danger btn-sm" id="btnClear">Clear</button>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button class="btn btn-outline-dark btn-sm" id="btnTradeIn">Trade In</button>
                                <button class="btn btn-outline-dark btn-sm" id="btnPurchase">Purchase</button>
                            </td>
                        </tr>
                    </form>
                </table>
            </div>`;

        //inject HTML
        leftColumnContainer.innerHTML = leftColumnContainerTemplate;
        midColumnContainer.innerHTML = midColumnContainerTemplate;
        rightColumnContainer.innerHTML = rightColumnContainerTemplate;

        //search for customer
        txtSearchCustomer = leftColumnContainer.querySelector('#txtSearchCustomer');
        btnSearchByCustomerName = leftColumnContainer.querySelector('#btnSearchByCustomerName').onclick = searchByCustomerName;

        vSearch = leftColumnContainer.querySelector('#vSearch');

        //mid column vars
        customerTableListContainer = midColumnContainer.querySelector('#mid-table-list');
        customerTable = midColumnContainer.querySelector('#mid-table');

        //right column vars
        txtCustomerId = rightColumnContainer.querySelector('#txtCustomerId');
        txtCustomerFirstName = rightColumnContainer.querySelector('#txtCustomerFirstName');
        txtCustomerLastName = rightColumnContainer.querySelector('#txtCustomerLastName');
        txtCustomerIdNumber = rightColumnContainer.querySelector('#txtCustomerIdNumber');
        txtCustomerEmail = rightColumnContainer.querySelector('#txtCustomerEmail');
        txtCustomerPhone = rightColumnContainer.querySelector('#txtCustomerPhone');

        btnAdd = rightColumnContainer.querySelector('#btnAdd');
        btnSaveCustomer = rightColumnContainer.querySelector('#btnSaveCustomer');
        btnClear = rightColumnContainer.querySelector('#btnClear').onclick = clearForm;

        btnTradeIn = rightColumnContainer.querySelector('#btnTradeIn');
        btnPurchase = rightColumnContainer.querySelector('#btnPurchase');
        
        vFirstName = rightColumnContainer.querySelector('#vFirstName');
        vLastName = rightColumnContainer.querySelector('#vLastName');
        vIdNumber = rightColumnContainer.querySelector('#vIdNumber');
        vEmail = rightColumnContainer.querySelector('#vEmail');
        vPhone = rightColumnContainer.querySelector('#vPhone');

        //eventHandlers
        customerTable.addEventListener("click", selectCustomerInList);


        txtSearchCustomer.addEventListener("keyup", function(event){
            if(event.keyCode == 13){
                event.preventDefault();
                leftColumnContainer.querySelector('#btnSearchByCustomerName').click();
            }
        });

        btnSaveCustomer.addEventListener("click", editCustomer);
        btnAdd.addEventListener("click", addCustomer);

        // btnTradeIn.addEventListener("click", namespace.TradeInModule{
        //     leftColumnContainer: document.getElementById("left-column"),
        //     midColumnContainer : document.getElementById("mid-column"),
        //     rightColumnContainer: document.getElementById("right-column"),
        //     webServiceAddress: "https://localhost/GG/web-services/tradeins/"
        //     //webServiceAddress: "https://www.dylanisensee.com/gg/web-services/tradeins/"
        // });

        btnTradeIn.addEventListener("click", rturn)
        //display props
        btnTradeIn.style.display = 'none';
        btnPurchase.style.display = 'none';
    }

    function searchByCustomerName(customerName){
        if(validateSearchBox()){
            vSearch.innerHTML = '';
            customerName = txtSearchCustomer.value;

            namespace.ajax.send({
                url: webServiceAddress + customerName,
                method: "GET",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                callback: function(response){
                    var customers = JSON.parse(response);
                    generateCustomerList(customers);
                },
                errorCallback: function(responseStatus, responseText){
                    if(responseStatus == 404){
                        alert(responseText);
                    }
                }
            });

            txtSearchCustomer.value = "";
        }else{
            return false;
        }
    }

    function getCustomerByCustomerId(id){
        namespace.ajax.send({
            url: webServiceAddress + id,
            method:"GET",
            headers: {"Content-Type": "application/json", "Accept": "application/json"},
            callback: function(response){
                var customer = JSON.parse(response);
                generateCustomerList(customer);
            }
        });
    }

    function addCustomer(){
        if(txtCustomerId.value != 0){
            alert("Customer is already selected. Please use the 'Save' button to update a customer.");
            return false;
        }

        clearValidationMessages();
        if(validateCustomer()){
            var customerToSave = createCustomerFromForm();
        }else{
            return false;
        }

        if(customerToSave.customerId == 0){
            namespace.ajax.send({
                url: webServiceAddress,
                method: "POST",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                requestBody: JSON.stringify(customerToSave),
                callback: function(response){
                    var customer = JSON.parse(response);
                    populateCustomerForm(customer);
                    alert("Customer has been added!");
                    getCustomerByCustomerId(customer.customerId);
                }
            });
        }else{
            return false;
        }
    }

    function editCustomer(){
        if(txtCustomerId.value == 0){
            alert("Please select an existing customer.");
            return false;
        }

        clearValidationMessages();
        if(validateCustomer()){
            var customerToEdit = createCustomerFromForm();
        }else{
            return false;
        }

        if(customerToEdit.customerId > 0){
            namespace.ajax.send({
                url: webServiceAddress + customerToEdit.customerId,
                method: "PUT",
                headers: {"Content-Type": "application/json", "Accept": "application/json"},
                requestBody: JSON.stringify(customerToEdit),
                callback: function(response){
                    var editedCustomer = JSON.parse(response);
                    populateCustomerForm(editedCustomer);
                    alert("Changes saved!");
                    generateCustomerList(editedCustomer);
                }
            });
        }else{
            return false;
        }
    }

    function generateCustomerList(customers){
        customerTableListContainer.innerHTML =`<h4>CUSTOMERS</h4>`;
        var html = `<tr>
                        <th>Customer Name</th>
                        <th>Customer Phone</th>
                        <th>Customer Email</th>
                    </tr>`;
        
        if(Array.isArray(customers)){
            for(var x = 0; x < customers.length; x++){
                html += `<tr class="mid-table-row" customerId="${customers[x].customerId}">
                            <td class="mid-table-cell">
                                ${customers[x].customerFirstName} ${customers[x].customerLastName}
                            </td>
                            <td class="mid-table-cell">
                                (${customers[x].customerPhone.substring(0,3)}) ${customers[x].customerPhone.substring(3,6)}-${customers[x].customerPhone.substring(6,customers[x].customerPhone.length)}
                            </td>
                            <td class="mid-table-cell">
                                ${customers[x].customerEmail}
                            </td>
                        </tr>`;
            }
        }else{
            html += `<tr class="mid-table-row" customerId="${customers.customerId}">
                            <td class="mid-table-cell">
                                ${customers.customerFirstName} ${customers.customerLastName}
                            </td>
                            <td class="mid-table-cell">
                                (${customers.customerPhone.substring(0,3)}) ${customers.customerPhone.substring(3,6)}-${customers.customerPhone.substring(6,customers.customerPhone.length)}
                            </td>
                            <td class="mid-table-cell">
                                ${customers.customerEmail}
                            </td>
                        </tr>`;
        }
        
        customerTable.innerHTML = html;
        customerTableListContainer.appendChild(customerTable);
        return customerTable;
    }

    function selectCustomerInList(evt){
        var target = evt.target;
        if(target.classList.contains("mid-table-cell")){
            var selectedCustomerId = target.closest("tr").getAttribute("customerId");
        }

        namespace.ajax.send({
            url: webServiceAddress + selectedCustomerId,
            method: "GET",
            callback: function(response){
                var customer = JSON.parse(response);
                populateCustomerForm(customer);
            }
        });
    }

    function populateCustomerForm(customer){
        clearValidationMessages();
        txtCustomerId.value = customer.customerId;
        txtCustomerFirstName.value = customer.customerFirstName;
        txtCustomerLastName.value = customer.customerLastName;
        txtCustomerIdNumber.value = customer.customerIdNumber;
        txtCustomerEmail.value = customer.customerEmail;
        txtCustomerPhone.value = customer.customerPhone;

        var customer = createCustomerFromForm();
        if(customer != null){
            btnTradeIn.style.display = "inline";
            btnPurchase.style.display = "inline";
        }
    }

    function clearForm(){
        txtCustomerId.value = '0';
        txtCustomerFirstName.value = '';
        txtCustomerLastName.value = '';
        txtCustomerIdNumber.value = '';
        txtCustomerEmail.value = '';
        txtCustomerPhone.value = '';
    }

    function createCustomerFromForm(){
        var customer = {
            customerId: txtCustomerId.value,
            customerFirstName: jsUcFirst(txtCustomerFirstName.value),
            customerLastName: jsUcFirst(txtCustomerLastName.value),
            customerIdNumber: txtCustomerIdNumber.value,
            customerEmail: txtCustomerEmail.value,
            customerPhone: txtCustomerPhone.value
        };

        return customer;
    }

    function validateSearchBox(){
        if(txtSearchCustomer.value == ''){
            vSearch.innerHTML = "Please enter a customer name."
            return false;
        }

        return true;
    }

    function validateEmail(email){
        var regExp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regExp.test(email);
    }
    
    function validatePhone(phone){
        var regExp = /^([0-9]){10}$/;
        return regExp.test(phone);
    }

    function validateCustomer(){
        var isValid = true;

        if(txtCustomerFirstName.value == ''){
            vFirstName.innerHTML = "Please enter a first name.";
            isValid = false;
        }

        if(txtCustomerLastName.value == ''){
            vLastName.innerHTML = "Please enter a last name.";
            isValid = false;
        }

        if(txtCustomerEmail.value == ''){
            vEmail.innerHTML = "Please enter an email address.";
            isValid = false;
        }

        if(txtCustomerEmail.value != ''){
            if(!validateEmail(txtCustomerEmail.value)){
                vEmail.innerHTML = "Please enter a valid email address (example@email.com).";
                isValid = false;
            }
        }

        if(txtCustomerPhone.value == ''){
            vPhone.innerHTML = "Please enter a phone number.";
            isValid = false;
        }
        

        if(txtCustomerPhone.value != ''){
            if(!validatePhone(txtCustomerPhone.value)){
                vPhone.innerHTML = "Please enter a valid phone number. Do not include dashes, spaces, or parantheses."
                isValid = false;
            }
        }

        if(!isValid){
            return false;
        }

        if(txtCustomerIdNumber.value == ''){
            var idConfirm = confirm("Are you sure you with to continue without customer's ID number? An ID number will be required for trade-ins.");
            if(!idConfirm){
                isValid = false;
            }
        }

        return isValid;
    }

    function jsUcFirst(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function clearValidationMessages(){
        vFirstName.innerHTML='';
        vLastName.innerHTML='';
        vIdNumber.innerHTML='';
        vEmail.innerHTML='';
        vPhone.innerHTML='';
    }

    function rturn(){
        customerForTransaction = createCustomerFromForm();
        namespace.TradeInModule({
            	leftColumnContainer: document.getElementById("left-column"),
            	midColumnContainer : document.getElementById("mid-column"),
            	rightColumnContainer: document.getElementById("right-column"),
                webServiceAddress: "https://localhost/GG/web-services/tradeins/",
                customer: customerForTransaction
            	//webServiceAddress: "https://www.dylanisensee.com/gg/web-services/tradeins/"
            });
    }

    return rturn;

};