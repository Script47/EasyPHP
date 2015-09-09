var WriteConfiguration = {
    doGoogleSearch : function (hashAlgo) 
    {
        document.getElementById("hashURL").innerHTML = "<a href='https://www.google.co.uk/search?q=" + hashAlgo + "' target='_blank'>Google " + hashAlgo + "</a>"
    }
};