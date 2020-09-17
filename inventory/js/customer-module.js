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

    initialize();

    function initialize(){
        leftColumnContainer.innerHTML = "";
        midColumnContainer.innerHTML = "";
        rightColumnContainer.innerHTML = "";

        rightColumnContainer.style.display = 'none';

        leftColumnContainer.style.width = '33%';
        midColumnContainer.style.width = '66%';
        rightColumnContainer.style.width = '0%';

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

        //inject HTML
        leftColumnContainer.innerHTML = leftColumnContainerTemplate;
        midColumnContainer.innerHTML = midColumnContainerTemplate;

        //search for customer
        txtSearchCustomer = leftColumnContainer.querySelector('#txtSearchCustomer');
        btnSearchByCustomerName = leftColumnContainer.querySelector('#btnSearchByCustomerName').onclick = searchByCustomerName;

        //mid column vars
        customerTableListContainer = midColumnContainer.querySelector('#mid-table-list');
        customerTable = midColumnContainer.querySelector('#mid-table');
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
            errorCallback: function(response){
                if(response.status == 404){
                    alert("Search yielded zero results.");
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
};