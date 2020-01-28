var currentSupplierID = -1;
function watchSupplierIDChange1(sessionCookie)
{			
    if (currentSupplierID == -1)
    {    	    	currentSupplierID = document.getElementById('supplierID').value * 1;    	//alert(currentSupplierID);    }
    
    if (currentSupplierID != document.getElementById('supplierID').value * 1 && document.getElementById('supplierID').value * 1 != -1 && document.getElementById('supplierID').value != '')
    {    	    	//alert(currentSupplierID);
    	    	currentSupplierID = document.getElementById('supplierID').value * 1;    	document.getElementById('departmentSelect').disabled = true;
        document.getElementById('departmentSelect').length = 3;        ContactDepartments_populate(currentSupplierID, sessionCookie);
        SupplierContacts_populate_bySupplierID(currentSupplierID, 'reportsTo', 'ajaxIndicatorReportsTo', sessionCookie);
    }
    setTimeout("watchSupplierIDChange1('"+sessionCookie+"');", 600);
}

function ContactDepartments_populate(supplierID, sessionCookie)
{
    if (supplierID == '' || !stringIsNumeric(supplierID))
    {
        return;
    }

    var http = AJAX_getXMLHttpObject();

    /* Build HTTP POST data. */
    var POSTData = '&supplierID=' + urlEncode(companyID);
    //alert(POSTData);
    /* Anonymous callback function triggered when HTTP response is received. */
    var callBack = function ()
    {
        if (http.readyState != 4)
        {
            return;
        }

        if (!http.responseXML)
        {
            var errorMessage = "An error occurred while receiving a response from the server.\n\n"
                             + http.responseText;
            alert(errorMessage);
            return;
        }

        /* Return if we have any errors. */
        var errorCodeNode    = http.responseXML.getElementsByTagName('errorcode').item(0);
        var errorMessageNode = http.responseXML.getElementsByTagName('errormessage').item(0);
        if (!errorCodeNode.firstChild || errorCodeNode.firstChild.nodeValue != '0')
        {
            if (errorCodeNode.firstChild.nodeValue != '-2')
            {
                var errorMessage = "An error occurred while receiving a response from the server.\n\n"
                                 + errorMessageNode.firstChild.nodeValue;
                alert(errorMessage);
            }

            document.getElementById('address').value  = '';
            document.getElementById('city').value  = '';
            document.getElementById('state').value = '';
            document.getElementById('zip').value = '';
            return;
        }

        var addressNode     = http.responseXML.getElementsByTagName('address').item(0);
        var cityNode        = http.responseXML.getElementsByTagName('city').item(0);
        var stateNode       = http.responseXML.getElementsByTagName('state').item(0);
        var zipNode         = http.responseXML.getElementsByTagName('zip').item(0);
        var departmentsNode = http.responseXML.getElementsByTagName('departments').item(0);

        if (document.getElementById('address'))
        {
            if (addressNode.firstChild)
            {
                document.getElementById('address').value = addressNode.firstChild.nodeValue;
            }
            else
            {
                document.getElementById('address').value = '';
            }
        }

        if (cityNode.firstChild)
        {
            document.getElementById('city').value = cityNode.firstChild.nodeValue;
        }
        else
        {
            document.getElementById('city').value = '';
        }

        if (stateNode.firstChild)
        {
            document.getElementById('state').value = stateNode.firstChild.nodeValue;
        }
        else
        {
            document.getElementById('state').value = '';
        }


        if (document.getElementById('zip'))
        {
            if (zipNode.firstChild)
            {
                document.getElementById('zip').value = zipNode.firstChild.nodeValue;
            }
            else
            {
                document.getElementById('zip').value = '';
            }
        }

        if (departmentsNode.firstChild)
        {
            document.getElementById('departmentsCSV').value = departmentsNode.firstChild.nodeValue;
        }
        else
        {
            document.getElementById('departmentsCSV').value = '';
        }
        
        listEditorUpdateSelectFromCSV('departmentSelect', 'departmentsCSV', false);
        document.getElementById('departmentSelect').disabled = false;
    }

    AJAX_callCATSFunction(
        http,
        'getCompanyLocationAndDepartments',
        POSTData,
        callBack,
        0,
        sessionCookie,
        false,
        false
    );
}

