$(document).ready(function ($) {
    console.log("loaded");

    var selectedColumn = "";
    var allData;
    var firstValues = [];

    getData();

    var typeSelect = $("#type-select");
    var typeContent = $("#type-content");
    var numSelect = $("#sel-mod-num");
    var numContent = $("#modify-numeric-content");
    var dateSelect = $("#sel-mod-date");
    var dateContent = $("#modify-date-content");
    var textSelect = $("#sel-mod-text");
    var textContent = $("#modify-text-content");
    console.log(typeSelect.val());

    var lab;
    var sel;

    switch (typeSelect.val()) {
        case "0":
            console.log("0");
            lab = $("<label for='type-number-sel'>Separator: </label>");
            sel = $("<select id='type-number-sel'></select>");
            sel.append($("<option selected>Comma</option>"));
            sel.append($("<option>Dot</option>"));
            typeContent.append(lab, sel);
            break;
        case "3":
            console.log("3");
            lab = $("<label for='type-number-sel'>Date format: </label>");
            sel = $("<select id='type-number-sel'></select>");
            sel.append($("<option selected>Comma</option>"));
            sel.append($("<option>Dot</option>"));
            typeContent.append(lab, sel);
            break;
    }

    $("#modal-background").click(function () {
        hideAll();
    });

    $(".hide").click(function () {
        hideAll();
    });

    $(".show-confirm").click(function () {
        $("#modal-confirm-delete").css("display", "inline-block");
        $("#modal-background").css("display", "inline-block");
    });

    $("#go-back").click(function () {
        window.location.href = "index.html"
    });

    typeSelect.change(function () {
        switch (typeSelect.val()) {
            case "0":
                typeContent.empty();
                lab = $("<label for='type-number-sel'>Separator: </label>");
                sel = $("<select id='type-number-sel'></select>");
                sel.append($("<option selected>Comma</option>"));
                sel.append($("<option>Dot</option>"));
                typeContent.append(lab, sel);
                break;
            case "1":
                typeContent.empty();
                break;
            case "2":
                typeContent.empty();
                break;
            case "3":
                typeContent.empty();
                console.log("type 3");
                var tab = $("<table></table>");
                var tr1 = $("<tr></tr>");
                var tr2 = $("<tr></tr>");
                var td1 = $("<td></td>");
                var td2 = $("<td></td>");
                var td3 = $("<td></td>");
                var td4 = $("<td></td>");
                lab = $("<label for='numeral-number-sel'>Date format: </label>");
                sel = $("<select id='numeral-number-sel'></select>");
                sel.append($("<option selected>dd.mm.yyyy</option>"));
                sel.append($("<option>dd/mm/yyyy</option>"));
                sel.append($("<option>dd-mm-yyyy</option>"));
                sel.append($("<option>yyyy.mm.dd</option>"));
                sel.append($("<option>yyyy/mm/dd</option>"));
                sel.append($("<option>yyyy-mm-dd</option>"));
                sel.append($("<option>mm.dd.yyyy</option>"));
                sel.append($("<option>mm/dd/yyyy</option>"));
                sel.append($("<option>mm-dd-yyyy</option>"));
                sel.append($("<option>hh:mm:ss</option>"));
                var lab2 = $("<label for='type-number-sel'>Different: </label>");
                var sel2 = $("<input type='text' id='type-number-sel'>");
                td1.append(lab);
                td2.append(sel);
                tr1.append(td1, td2);
                td3.append(lab2);
                td4.append(sel2);
                tr2.append(td3, td4);
                tab.append(tr1, tr2);
                typeContent.append(tab);
                break;
        }
    });

    numSelect.change(function () {
        console.log("It works!");
        switch (numSelect.val()) {
            case "0":
                numContent.empty();
                showNewValues(firstValues[selectedColumn]);
                break;
            case "1":
                numContent.empty();
                lab = $("<label for='numeral-number-sel'>Column: </label>");
                sel = $("<select id='numeral-number-sel'></select>");
                $.each(allData.titles, function (l, val) {
                    if (val.type === "Number") {
                        sel.append($("<option>" + val.title +"</option>"));
                    }
                });
                sel.change(function () {
                    showNewValues(firstValues[selectedColumn]);
                });
                numContent.append(lab, sel);
                showNewValues(firstValues[selectedColumn]);
                break;
            case "2":
                numContent.empty();
                lab = $("<label for='numeral-number-sel'>Column: </label>");
                sel = $("<select id='numeral-number-sel'></select>");
                $.each(allData.titles, function (l, val) {
                    if (val.type === "Number") {
                        sel.append($("<option>" + val.title +"</option>"));
                    }
                });
                sel.change(function () {
                    showNewValues(firstValues[selectedColumn]);
                });
                numContent.append(lab, sel);
                showNewValues(firstValues[selectedColumn]);
                break;
            case "3":
                numContent.empty();
                lab = $("<label for='type-number-sel'>Expression: </label>");
                sel = $("<input type='text' id='type-number-sel'>");
                numContent.append(lab, sel);
                break;
        }
    });

    dateSelect.change(function () {
        console.log("It works!");
        switch (dateSelect.val()) {
            case "0":
                dateContent.empty();
                break;
            case "1":
                dateContent.empty();
                lab = $("<label for='numeral-number-sel'>Column: </label>");
                sel = $("<select id='numeral-number-sel'></select>");
                $.each(allData.titles, function (l, val) {
                    sel.append($("<option>" + val.title +"</option>"));
                });
                dateContent.append(lab, sel);
                break;
        }
    });

    textSelect.change(function () {
        console.log("It works!");
        switch (textSelect.val()) {
            case "0":
                textContent.empty();
                lab = $("<label for='type-number-sel'>Expression: </label>");
                sel = $("<input type='text' id='type-number-sel'>");
                break;
            case "1":
                textContent.empty();
                lab = $("<label for='type-number-sel'>Expression: </label>");
                sel = $("<input type='text' id='type-number-sel'>");
                textContent.append(lab, sel);
                break;
        }
    });

    function hideAll() {
        $("#modal-change-type").css("display", "none");
        $("#modal-modify-numeric").css("display", "none");
        $("#modal-modify-date").css("display", "none");
        $("#modal-modify-text").css("display", "none");
        $("#modal-background").css("display", "none");
        $("#modal-confirm-delete").css("display", "none");
        $("#spinner-modal").css("display", "none");
    }

    function getData() {
        $("#spinner-modal").css("display", "block");
        $("#modal-background").css("display", "block");
        $.ajax({
            url: "api/getData.php",
            processData: false,
            contentType: false,
            type: 'POST',
            success: function ( data ) {
                allData = data;
                console.log( "Data Loaded", data.titles);
                for (var i = 0, len = data.titles.length; i < len; i++) {
                    newLine(i, data);
                }
                hideAll();
            },
            error: function () {
                hideAll();
                alert("Network error");
            }
        });
    }

    function showValues(fromWhat) {
        var textTable = $(".value-table");
        textTable.empty();
        console.log(fromWhat);
        textTable.append($("<tr><th>First 10 values</th></tr>"));
        $.each(fromWhat, function (key, value2) {
            var modifyTableTrText = $("<tr></tr>");
            var modifyTableTdText = $("<td></td>");
            modifyTableTdText.text(value2);
            modifyTableTdText.css("text-align", "center");
            modifyTableTrText.append(modifyTableTdText);
            textTable.append(modifyTableTrText);
        });
    }

    function showNewValues(what) {
        var textTable = $(".value-table-change");
        textTable.empty();
        var fromWhat = $("#numeral-number-sel").val();
        console.log(fromWhat);
        var a = 0;
        switch ($("#sel-mod-num").val()) {
            case "0":
                console.log(parseInt(selectedColumn));
                console.log(allData.rows[selectedColumn]);
                $.each(allData.rows[selectedColumn], function (l, val) {
                    console.log("l", l);
                    a = a + parseInt(l);
                    console.log(a);
                });
                break;
            case "1":
                for (var i in allData.titles) {
                    if(allData.titles[i].title === fromWhat) {
                        console.log(a);
                        a = i;
                    }
                }
                break;
            case "2":
                for (var i in allData.titles) {
                    if(allData.titles[i].title === fromWhat) {
                        a = i;
                        console.log(a);
                    }
                }
                break;
        }
        textTable.append($("<tr><th>First 10 values after change</th></tr>"));
        $.each(what, function (key, value2) {
            console.log(value2, a);
            var modifyTableTrText = $("<tr></tr>");
            var modifyTableTdText = $("<td></td>");
            switch ($("#sel-mod-num").val()) {
                case "0":
                    console.log(value2, a);
                    modifyTableTdText.text((parseInt(value2) / a).toFixed(4));
                    break;
                case "1":
                    console.log(value2, firstValues[a][key]);
                    modifyTableTdText.text(parseInt(value2) + parseInt(firstValues[a][key]));
                    break;
                case "2":
                    console.log(value2, firstValues[a][key]);
                    modifyTableTdText.text(parseInt(value2) - parseInt(firstValues[a][key]));
            }
            modifyTableTdText.css("text-align", "center");
            modifyTableTrText.append(modifyTableTdText);
            textTable.append(modifyTableTrText);
        });
    }

    $("#modal-modify-numeric-save").click(function () {
        var action = $("#sel-mod-num").val();
        var keyToSend = allData.titles[selectedColumn].title;
        var addTo = $("#numeral-number-sel").val();
        switch (action) {
            case "0":
                var all = 0;
                $.each(allData.rows[selectedColumn], function (l, val) {
                    all = all + parseInt(l);
                });
                console.log(all);
                sendActionToModify(0, "toPercent", keyToSend, all);
                break;
            case "1":
                sendActionToModify(0, "addColumn", keyToSend, addTo);
                break;
            case "2":
                sendActionToModify(0, "subtractColumn", keyToSend, addTo);
                break;
        }
        hideAll();
    });

    $("#modal-modify-numeric-new").click(function () {
        var action = $("#sel-mod-num").val();
        var title = $("#modify-new-number").val();
        var addTo = $("#numeral-number-sel").val();
        if (title.trim() === "") {
            alert("Missing column name");
        } else {
            var keyToSend = allData.titles[selectedColumn].title;
            switch (action) {
                case "0":
                    var all = 0;
                    $.each(allData.rows[selectedColumn], function (l, val) {
                        all = all + parseInt(l);
                    });
                    console.log(all);
                    sendActionToModify(1, "toPercentNew", keyToSend, all, title);
                    break;
                case "1":
                    sendActionToModify(1, "addColumnNew", keyToSend, addTo, title);
                    break;
                case "2":
                    sendActionToModify(1, "subtractColumnNew", keyToSend, addTo, title);
                    break;
            }
            hideAll();
        }
    });

    function sendActionToModify(isNew, action, firstColumn, parameter, parameter2) {
        $("#spinner-modal").css("display", "block");
        $("#modal-background").css("display", "block");
        var formData = new FormData();
        formData.append("action", action);
        formData.append("column", firstColumn);
        if (parameter) {
            formData.append("parameter", parameter);
        }
        if (parameter2) {
            formData.append("parameter2", parameter2);
        }
        $.ajax({
            url: "api/modify.php",
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                switch (isNew) {
                    case 0:
                        allData.rows[selectedColumn] = data;
                        changeHistogram();
                        break;
                    case 1:
                        allData.rows.push(data.row);
                        allData.titles.push(data.title);
                        console.log("id: ", data.title.id);
                        newLine(data.title.id, allData);
                        break;
                }
                $("#spinner-modal").css("display", "none");
                $("#modal-background").css("display", "none");
            },
            error: function () {
                $("#spinner-modal").css("display", "none");
                $("#modal-background").css("display", "none");
                alert("Network error");
            }
        });

    }

    function changeHistogram() {
        var div = $("#histogram-" + selectedColumn);
        div.empty();
        console.log(allData.rows);
        var biggest = 0;
        var countOfItems = 0;
        $.each(allData.rows[selectedColumn], function (l, val) {
            var intVal = parseInt(val);
            countOfItems++;
            if (intVal > biggest) {
                biggest = intVal;
            }
        });
        var widthOfItem = 100 / countOfItems;
        firstValues[selectedColumn] = [];
        var m = 0;
        $.each(allData.rows[selectedColumn], function (l, val) {
            var item = $("<span></span>");
            item.addClass("histogram-item");
            var heightPrc = (parseInt(val) / biggest) * 100;
            item.css("height", heightPrc + "%").css("min-height", "1px").css("margin-botton", "0px").css("width", widthOfItem + "%");
            item.prop("title", val + ", " + l);
            div.append(item);
            if (m < 11) {
                var toAdd;
                if (l === "") {
                    toAdd = "\"\" - Empty Value"
                } else {
                    toAdd = l;
                }
                if (firstValues[selectedColumn] == null) {
                    firstValues[selectedColumn] = [];
                }
                firstValues[selectedColumn].push(toAdd);
                m++;
            }
        });
    }

    function newLine(i, data) {
        var newTr = $("<tr></tr>");
        var td1 = $("<td></td>");
        td1.text(data.titles[i].title);
        newTr.append(td1);
        var td2 = $("<td></td>");
        var type = "Text";
        var countOfItems = 0;
        var biggest = 0;
        $.each(data.rows[i], function (l, val) {
            var intVal = parseInt(val);
            countOfItems++;
            if (intVal > biggest) {
                biggest = intVal;
            }
        });
        var widthOfItem = 100 / countOfItems;
        var div = $("<div class='histogram' id='histogram-" + i + "'></div>");
        var m = 0;
        var isNumber = 0;
        var isDate = 0;
        $.each(data.rows[i], function (l, val) {
            var item = $("<span></span>");
            item.addClass("histogram-item");
            var heightPrc = (parseInt(val) / biggest) * 100;
            item.css("height", heightPrc + "%").css("min-height", "1px").css("margin-botton", "0px").css("width", widthOfItem + "%");
            item.prop("title", val + ", " + l);
            div.append(item);
            if (m < 11) {
                var toAdd;
                if(l === "") {
                    toAdd = "\"\" - Empty Value"
                } else {
                    toAdd = l;
                }
                if(firstValues[i] == null) {
                    firstValues[i] = [];
                }
                firstValues[i].push(toAdd);
                try {
                    if (!isNaN(l)) {
                        isNumber++;
                    }
                } catch (err) {
                    console.log(err);
                }
                try {
                    var date = new Date(l);
                    if (date.getFullYear() !== 1970 && !isNaN(date.getFullYear())) {
                        isDate++;
                    }
                } catch (err) {
                    console.log(err);
                }
                m++;

            }
        });
        if (isDate > 5) {
            type = "Date"
        }
        if (isNumber > 5) {
            type = "Number"
        }
        if (Object.keys(data.rows[i]).length < 10) {
            type = "Cathegorical";
        }
        allData.titles[i].type = type;
        var buttonPrep = $("<button class='button-prep change-type4' id='" + type + "-changeType-" + i +"'>" + type +"</button>");
        buttonPrep.click(function (event) {
            var id = event.target.id;
            var idArr = id.split("-");
            showValues(firstValues[idArr[2]]);
            selectedColumn = idArr[2];
            $("#modal-change-type").css("display", "inline-block");
            $("#modal-background").css("display", "inline-block");
        });
        td2.append(buttonPrep);
        newTr.append(td2);
        var td3 = $("<td></td>");
        var unique = 0;
        for(var k in data.rows[i]) {
            if(data.rows[i][k] === 1) {
                unique++;
            }
        }
        td3.text(unique);
        newTr.append(td3);
        var td4 = $("<td style=\"min-width: 300px; max-width: 300px\"></td>");
        td4.append(div);
        newTr.append(td4);
        var td5 = $("<td></td>");
        var button1 = $("<button class='button-prep' id='"+ type + "-modify-" + i +"'>Modify</button>");
        var button2 = $("<button class='button-prep-danger show-confirm'>Delete</button>");
        button1.click(function (event) {
            console.log("clicked");
            var id = event.target.id;
            var idArr = id.split("-");
            selectedColumn = idArr[2];
            showValues(firstValues[idArr[2]]);
            showNewValues(firstValues[idArr[2]]);
            switch (idArr[0]) {
                case "Text":
                    $("#modal-modify-text").css("display", "inline-block");
                    break;
                case "Number":
                    $("#modal-modify-numeric").css("display", "inline-block");
                    break;
                case "Date":
                    $("#modal-modify-date").css("display", "inline-block");
            }
            $("#modal-background").css("display", "inline-block");
        });
        if (type !== "Cathegorical") {
            td5.append(button1);
        }
        td5.append(button2);
        newTr.append(td5);
        $("#info-table").append(newTr);
    }

});