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

    var btnSaveCustomer;
    var btnClear;
    var btnEdit;

    initialize();

    function initialize(){
        leftColumnContainer.innerHTML = "";
        midColumnContainer.innerHTML = "";
        rightColumnContainer.innerHTML = "";

        var leftColumnContainerTemplate = `
            <div id="search-container">
                <p>Search for customer:</p><br>
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
                            <td><span class="validation" id="vFirstName"></span></td>
                        </tr>
                        <tr>
                            <td><label for="customerFirstName">First Name:</label></td>
                            <td><input type="text" name="customerFirstName" id="txtCustomerFirstName" placeholder="First Name" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="customerLastName">Last Name:</label></td>
                            <td><input type="text" name="customerLastName" id="txtCustomerLastName" placeholder="Last Name" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="customerIdNumber">ID Number:</label></td>
                            <td><input type="text" name="customerIdNumber" id="txtCustomerIdNumber" placeholder="ID #" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="customerEmail">Email Address:</label></td>
                            <td><input type="text" name="customerEmail" id="txtCustomerEmail" placeholder="Email Address" readonly="true"></td>
                        </tr>
                        <tr>
                            <td><label for="customerPhone">Phone Number:</label></td>
                            <td><input type="text" name="customerPhone" id="txtCustomerPhone" placeholder="Phone Number" readonly="true"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" id="btnAdd">Add</button>
                                <button class="btn btn-outline-dark btn-sm" id="btnEdit">Edit</button>
                                <button class="btn btn-outline-success btn-sm" id="btnSaveCustomer">Save</button>
                                <button class="btn btn-outline-dark btn-sm" id="btnClear">Clear</button>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button
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

        //mid column vars
        customerTableListContainer = midColumnContainer.querySelector('#mid-table-list');
        customerTable = midColumnContainer.querySelector('#mid-table');

        //rigth column vars
        txtCustomerId = rightColumnContainer.querySelector('#txtCustomerId');
        txtCustomerFirstName = rightColumnContainer.querySelector('#txtCustomerFirstName');
        txtCustomerLastName = rightColumnContainer.querySelector('#txtCustomerLastName');
        txtCustomerIdNumber = rightColumnContainer.querySelector('#txtCustomerIdNumber');
        txtCustomerEmail = rightColumnContainer.querySelector('#txtCustomerEmail');
        txtCustomerPhone = rightColumnContainer.querySelector('#txtCustomerPhone');

        btnSaveCustomer = rightColumnContainer.querySelector('#btnSaveCustomer');
        btnClear = rightColumnContainer.querySelector('#btnClear').onclick = clearForm;
        btnEdit = rightColumnContainer.querySelector('#btnEdit');

        //eventHandlers
        customerTable.addEventListener("click", selectCustomerInList);


        txtSearchCustomer.addEventListener("keyup", function(event){
            if(event.keyCode == 13){
                event.preventDefault();
                leftColumnContainer.querySelector('#btnSearchByCustomerName').click();
            }
        });

        btnEdit.addEventListener("click", editCustomer);
    }

    function searchByCustomerName(customerName){
        customerName = txtSearchCustomer.value;

        namespace.ajax.send({
            url: webServiceAddress + customerName,
            method: "GET",
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
                                ${customers[x].customerPhone}
                            </td>
                            <td class="mid-table-cell">
                                ${customers[x].customerEmail}
                            </td>
                        </tr>`;
            }
        }else{
            html += `<tr class="mid-table-row" customerId="${customers.customerId}">
                            <td class="mid-table-cell">
                                ${customers[x].customerFirstName} ${customers[x].customerLastName}
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
        txtCustomerId.value = customer.customerId;
        txtCustomerFirstName.value = customer.customerFirstName;
        txtCustomerLastName.value = customer.customerLastName;
        txtCustomerIdNumber.value = customer.customerIdNumber;
        txtCustomerEmail.value = customer.customerEmail;
        txtCustomerPhone.value = customer.customerPhone;
    }

    function clearForm(){
        txtCustomerId.value = '0';
        txtCustomerFirstName.value = '';
        txtCustomerLastName.value = '';
        txtCustomerIdNumber.value = '';
        txtCustomerEmail.value = '';
        txtCustomerPhone.value = '';
    }

    function editCustomer(){

        if(txtCustomerId.value != 0){
            txtCustomerFirstName.focus();

            txtCustomerFirstName.readOnly = false;
            txtCustomerLastName.readOnly = false;
            txtCustomerIdNumber.readOnly = false;
            txtCustomerEmail.readOnly = false;
            txtCustomerPhone.readOnly = false;
        }else{
            alert("Please select a customer")
        }
    }

    function createCustomerFromForm(){
        var customer = {
            customerId: txtCustomerId.value,
            customerFirstName: txtCustomerFirstName.value,
            customerLastName: txtCustomerLastName.value,
            customerIdNumber: txtCustomerIdNumber.value,
            customerEmail: txtCustomerEmail.value,
            customerPhone: txtCustomerPhone.value
        };

        return customer;
    }

    function validateCustomer(){
        if(txtCustomerFirstName.value == ''){

        }
    }

};