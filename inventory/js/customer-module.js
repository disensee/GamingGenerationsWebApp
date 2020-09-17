var namespace = namespace || {};

namespace.CustomerModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress || "http://localhost/GG/web-services/customers/" //THIS IS REQUIRED!!

    initialize();

    function initialize(){
        leftColumnContainer.innerHTML = "";
        midColumnContainer.innerHTML = "";
        rightColumnContainer.innerHTML = "";

        var leftColumnContainerTempplace = `
        <div id="search-container">
            <p>Search for customer:</p><br>
           <input type="text" id="txtSearchCustomer" placeholder="Enter customer name"><br>
            <input id="btnSearchByProdName" type="button" value="Search"><br>
            <p>Searby by UPC:</p>
            <input type="text" id="txtSearchUpc" placeholder="Enter UPC">
            <input id="btnSearchByUpc" type="button" value="Search">
        </div>
        <div id="list-container">
            <p>Filter:</p>` +
            createConsoleList(consoleArr) +
        `</div>`;
    }
};