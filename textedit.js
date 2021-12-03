 document.write("<input class='editor-button' type='button' value='B' onclick='setBold()' />");
    document.write("<input class='editor-button' type='button' value='I' onclick='setItal()' />");
    document.write("<input class='editor-button' type='button' value='</>' onclick='setCode()' />");
     document.write("<input style='width:100px;' class='editor-button' type='button' value='Заголовок' onclick='setH()' />");
    document.write("<br />");
    document.write("<iframe style='overflow-y:auto;overflow-x:hidden;' frameborder='no' src='#' id='frameId' name='frameId'>ff</iframe>");
    var isGecko = navigator.userAgent.toLowerCase().indexOf("gecko") != -1;
    var iframe = (isGecko) ? document.getElementById("frameId") : frames["frameId"];
    var iWin = (isGecko) ? iframe.contentWindow : iframe.window;
    var iDoc = (isGecko) ? iframe.contentDocument : iframe.document;
    iHTML = "<html><link rel='stylesheet' href='css/editor.css'><head></head><body style='font-size:18px;width:90%;background-color: white;'></body></html>";
    iDoc.open();
    iDoc.write(iHTML);
    iDoc.close();
    iDoc.designMode = "on";
    iDoc.body.innerHTML = document.getElementById("content").value;
    function setBold() {
      iWin.focus();
      iWin.document.execCommand("bold", null, "");
    }
    function setCode() {
      iWin.focus();
      var sel =    iDoc.getSelection();
      iWin.document.execCommand("insertText", null, "/code" + sel + "/code");
    }
    function setItal() {
      iWin.focus();
      iWin.document.execCommand("italic", null, "");
    }
    function setH() {
      iWin.focus();
      iWin.document.execCommand("formatBlock", null, "<h5>");
    }
    function save() {
      document.getElementById("content").value = iDoc.body.innerHTML;
      return true;
    }