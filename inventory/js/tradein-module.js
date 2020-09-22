var namespace = namespace || {};

namespace.TradeInModule = function(options){
    var leftColumnContainer = options.leftColumnContainer || null;
    var midColumnContainer = options.midColumnContainer || null
    var rightColumnContainer = options.rightColumnContainer || null;
    var callback = options.callback;
    var webServiceAddress = options.webServiceAddress || "http://localhost/GG/web-services/tradeins/" //THIS IS REQUIRED!!
    var customer = options.customer || null;

    initialize();

    function initialize(){
        leftColumnContainer.innerHTML = "";
        midColumnContainer.innerHTML = "";
        rightColumnContainer.innerHTML = "";

        var midColumnContainerTemplate =`
            <div id="mid-table-list">
                <p style="text-align:right;">Search for a customer to see results</p>
                <table id="mid-table">
                </table>
            </div>`;

        console.log(customer);
    }

    return initialize;
}