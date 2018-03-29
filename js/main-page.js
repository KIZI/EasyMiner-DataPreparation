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
                showNewValues(firstValues[selectedColumn], 4);
                typeContent.empty();
                break;
            case "1":
                showNewValues(firstValues[selectedColumn], 4);
                typeContent.empty();
                break;
            case "2":
                showNewValues(firstValues[selectedColumn], 4);
                typeContent.empty();
                break;
            case "3":
                typeSelect.val(3);
                typeContent.empty();
                console.log("type 3");
                var originalValues = ['d.m.Y', 'd/m/Y', 'd-m-Y', 'Y.m.d', 'Y/m/d', 'Y-m-d', 'm.d.Y', 'm/d/Y', 'm-d-Y'];
                lab = $("<label for='numeral-number-sel'>Date format: </label>");
                sel = $("<select id='numeral-number-sel' style='margin-bottom: 15px'></select>");
                sel.append($("<option value='d.m.Y'>D.M.Y</option>"));
                sel.append($("<option value='d/m/Y'>D/M/Y</option>"));
                sel.append($("<option value='d-m-Y'>D-M-Y</option>"));
                sel.append($("<option value='Y.m.d'>Y.M.D</option>"));
                sel.append($("<option value='Y/m/d'>Y/M/D</option>"));
                sel.append($("<option value='Y-m-d'>Y-M-D</option>"));
                sel.append($("<option value='m.d.Y'>M.D.Y</option>"));
                sel.append($("<option value='m/d/Y'>M/D/Y</option>"));
                sel.append($("<option value='m-d-Y'>M-D-Y</option>"));
                var isIn = false;
                $.each(originalValues, function (l, val) {
                    if (val === allData.titles[selectedColumn].type.format) {
                        isIn = true;
                        sel.val(val);
                    }
                });

                var lab2 = $("<label for='type-number-sel' style='margin-top: 10px'>Different: </label>");
                var sel2 = $("<input type='text' id='type-number-sel'>");

                if (!isIn && allData.titles[selectedColumn].type.format) {
                    console.log(allData.titles[selectedColumn].type.format);
                    var format = allData.titles[selectedColumn].type.format;
                    var days = format.replace('d', 'D');
                    var months = days.replace('m', 'M');
                    sel.prop('disabled', true);
                    sel2.val(months);

                } else if (!isIn) {
                    sel.val(originalValues[0]);
                }

                sel.change(function () {
                    showNewValues(firstValues[selectedColumn], 4);
                });

                sel2.keyup(function () {
                    if (sel2.val().trim() !== "") {
                        sel.prop('disabled', true);
                        showNewValues(firstValues[selectedColumn], 4);
                    } else {
                        sel.prop('disabled', false);
                    }
                });

                typeContent.append(lab, sel, lab2, sel2);
                showNewValues(firstValues[selectedColumn], 4);
                break;
        }
        $("#modal-change-type").css("display", "inline-block");
        $("#modal-background").css("display", "inline-block");
    });

    numSelect.change(function () {
        var createNumber = $("#modal-modify-numeric-new");
        switch (numSelect.val()) {
            case "0":
                numContent.empty();
                var labRound = $("<label for='roundSel'>Round precision:</label>");
                var selRound = $("<select id='roundSel'></select>");
                selRound.append($("<option value='0'>0</option>"));
                selRound.append($("<option value='1'>1</option>"));
                selRound.append($("<option value='2'>2</option>"));
                selRound.append($("<option value='3'>3</option>"));
                selRound.append($("<option value='4'>4</option>"));
                selRound.append($("<option value='5'>5</option>"));
                selRound.val("2");
                selRound.change(function () {
                    showNewValues(firstValues[selectedColumn], 1);
                });
                showNewValues(firstValues[selectedColumn], 1);
                break;
            case "1":
                numContent.empty();
                lab = $("<label for='numeral-number-sel'>Column: </label>");
                sel = $("<select id='numeral-number-sel'></select>");
                var i = true;
                $.each(allData.titles, function (l, val) {
                    if (val.type.type === "Numeric") {
                        console.log(val);
                        i = false;
                        sel.append($("<option>" + val.title +"</option>"));
                    }
                });
                if (i) {
                    sel.append($("<option>No other numeric column</option>"));
                    sel.prop('disabled', true);
                    createNumber.prop('disabled', true);
                } else {
                    createNumber.prop('disabled', false);
                }
                sel.change(function () {
                    showNewValues(firstValues[selectedColumn], 1);
                });
                numContent.append(lab, sel);
                showNewValues(firstValues[selectedColumn], 1);
                break;
            case "2":
                numContent.empty();
                lab = $("<label for='numeral-number-sel'>Column: </label>");
                sel = $("<select id='numeral-number-sel'></select>");
                var j = true;
                $.each(allData.titles, function (l, val) {
                    if (val.type.type === "Numeric") {
                        j = false;
                        sel.append($("<option>" + val.title +"</option>"));
                    }
                });
                sel.change(function () {
                    showNewValues(firstValues[selectedColumn], 1);
                });
                numContent.append(lab, sel);
                showNewValues(firstValues[selectedColumn], 1);
                break;
            case "3":
                numContent.empty();
                createNumber.prop('disabled', false);
                lab = $("<label for='type-number-sel'>Expression: </label>");
                sel = $("<input type='text' id='type-number-sel'>");
                numContent.append(lab, sel);
                var expr = $("<select id='expresions'></select>");
                var addExp = $("<option>Add expresion</option>");
                expr.append(addExp);
                var col = $("<select id='columns'</select>");
                var addCol = $("<option>Add column</option>");
                col.append(addCol);

                col.change(function () {
                    sel.val(sel.val() + col.val());
                    col.val("Add column");
                    showNewValues(firstValues[selectedColumn], 1);
                });

                expr.append($("<option>ABS(x)</option>"));
                expr.append($("<option>SIGN(x)</option>"));
                expr.append($("<option>GCD(x, y)</option>"));
                expr.append($("<option>POWER(x, y)</option>"));
                expr.append($("<option>PRODUCT(x, y)</option>"));
                expr.append($("<option>SQRT(x)</option>"));
                expr.append($("<option>QUOTIENT(x, y)</option>"));
                expr.append($("<option>MOD(x, y)</option>"));

                expr.change(function () {
                    sel.val(sel.val() + expr.val());
                    expr.val("Add expresion");
                    showNewValues(firstValues[selectedColumn], 1);
                });

                sel.keyup(function () {
                    showNewValues(firstValues[selectedColumn], 1);
                    var text = $("#newValue1").text();
                    if (!isNaN(text) && text.trim() !== "") {
                        createNumber.prop("disabled", false);
                    } else {
                        createNumber.prop("disabled", true);
                    }
                });

                $.each(allData.titles, function (key, val) {
                    if (val.type.type === "Numeric") {
                        var options = $("<option>" + val.title +"</option>");
                        options.click(function () {
                            sel.val(sel.val() + val.title);
                        });
                        col.append(options);
                    }
                });

                numContent.append(expr);
                numContent.append(col);
                showNewValues(firstValues[selectedColumn], 1);
                break;
        }
    });

    dateSelect.change(function () {
        var createDate = $("#modal-modify-date-new");
        console.log("It works!");
        switch (dateSelect.val()) {
            case "0":
                dateContent.empty();
                showNewValues(firstValues[selectedColumn], 2);
                break;
            case "1":
                dateContent.empty();
                lab = $("<label for='date-number-sel'>Column: </label>");
                sel = $("<select id='date-number-sel'></select>");
                var i = true;
                $.each(allData.titles, function (l, val) {
                    if (val.type.type === "Date") {
                        console.log("val: ", val.type);
                        i = false;
                        sel.append($("<option>" + val.title +"</option>"));
                    }
                });
                if (i) {
                    sel.append($("<option>No other date column</option>"));
                    sel.prop('disabled', true);
                }

                sel.change(function () {
                    showNewValues(firstValues[selectedColumn], 2);
                });
                dateContent.append(lab, sel);
                showNewValues(firstValues[selectedColumn], 2);
                break;
        }
    });

    textSelect.change(function () {
        console.log("It works!");
        switch (textSelect.val()) {
            case "0":
                textContent.empty();
                lab = $("<label for='type-text-sel'>To remove: </label>");
                sel = $("<input type='text' id='type-text-sel'>");
                sel.keyup(function () {
                    console.log("changing");
                    showNewValues(firstValues[selectedColumn], 3);
                });
                textContent.append(lab, sel);
                showNewValues(firstValues[selectedColumn], 3);
                break;
            case "1":
                textContent.empty();
                lab = $("<label for='type-text-sel'>Expression: </label>");
                sel = $("<input type='text' id='type-text-sel'>");
                sel.keyup(function () {
                    console.log("changing");
                    showNewValues(firstValues[selectedColumn], 3);
                });
                textContent.append(lab, sel);
                showNewValues(firstValues[selectedColumn], 3);
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
                if (!data) {
                    hideAll();
                    window.loation = "index.html";
                } else {
                    console.log( "Data Loaded", data.titles);
                    for (var i = 0, len = data.titles.length; i < len; i++) {
                        newLine(i, data);
                    }
                    hideAll();
                }
                console.log(allData);
            },
            error: function () {
                hideAll();
                window.location = "index.html";
            }
        });
    }

    function showValues(fromWhat) {
        var textTable = $(".value-table");
        textTable.empty();

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

    function showNewValues(what, type) {
        var textTable = $(".value-table-change");
        textTable.empty();
        var a = 0;
        var expOk = true;
        var unsupported = "";
        var inputData = $("#type-number-sel").val();
        if (type === 1) {
            var fromWhat = $("#numeral-number-sel").val();
            console.log(fromWhat);
            switch (numSelect.val()) {
                case "0":
                    $.each(allData.rows[selectedColumn], function (l, val) {
                        if(!isNaN(a)) {
                            a = a + parseInt(l);
                        }
                    });
                    break;
                case "1":
                    for (var i in allData.titles) {
                        if(allData.titles[i].title === fromWhat) {
                            a = i;
                        }
                    }
                    break;
                case "2":
                    for (var i in allData.titles) {
                        if(allData.titles[i].title === fromWhat) {
                            a = i;
                        }
                    }
                    break;
                case "3":
                    var text = inputData;
                    textToEval = inputData;
                    var titles = ["ABS", "SIGN", "GCD", "LCM", "POWER", "PRODUCT", "SQRT", "QUOTIENT", "MOD", "(", ")", "+", "-", "*", "/", "%", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ","];
                    $.each(allData.titles, function (key, val) {
                        if (val.type.type === "Numeric") {
                            titles.push(val.title);
                        }
                    });
                    console.log(titles);
                    $.each(titles, function (key, val) {
                        text = text.split(val).join("");
                    });

                    if (text.trim() !== "") {
                        expOk = false;
                        unsupported = text.trim();
                    }

                    console.log(text);
            }
        }
        textTable.append($("<tr><th colspan='2'>First values</th></tr>"));
        $.each(what, function (key, value2) {
            var modifyTableTrText = $("<tr></tr>");
            var modifyTableTdText = $("<td id='newValue" + key + "'></td>");
            var modifyTableTd2Text = $("<td></td>");
            modifyTableTd2Text.text(value2);
            console.log(value2);
            if (type === 1) {
                switch (numSelect.val()) {
                    case "0":
                        modifyTableTdText.text((parseInt(value2) / a).toFixed((parseInt($("#roundSel").val()))));
                        break;
                    case "1":
                        modifyTableTdText.text(parseInt(value2) + parseInt(firstValues[a][key]));
                        break;
                    case "2":
                        modifyTableTdText.text(parseInt(value2) - parseInt(firstValues[a][key]));
                        break;
                    case "3":
                        if (expOk) {
                            var textToEval = inputData;
                            try {
                                $.each(allData.titles, function (key2, val) {
                                    console.log(val.title, firstValues[key2][key]);
                                    textToEval = textToEval.split(val.title).join(firstValues[key2][key]);
                                });
                                console.log(textToEval);
                                modifyTableTdText.text(eval(textToEval));
                            } catch (err) {
                                modifyTableTdText.text("Invalid expression");
                            }
                        } else {
                            modifyTableTdText.text("Unsupported char: " + unsupported);
                        }
                        break;

                }
            } else if (type === 2) {
                switch (dateSelect.val()) {
                    case "0":
                        modifyTableTdText.text(getDay(value2));
                        break;
                    case "1":
                        modifyTableTdText.text(getDifference(value2, key));
                        break;
                }
            } else if (type === 3) {
                var val = $("#type-text-sel").val();
                switch (textSelect.val()) {
                    case "0":
                         modifyTableTdText.text(value2.split(val).join(""));
                        break;
                    case "1":
                        try {
                            if (val !== "") {
                                var re = new RegExp(val);
                                var arr = value2.match(re) || [""];
                                modifyTableTdText.text(arr[0]);
                            } else {
                                modifyTableTdText.text(value2);
                            }
                        } catch (err) {
                            modifyTableTdText.text("Error");
                        }
                        break;
                }
            } else if (type === 4) {
                switch ($("#type-select").val()) {
                    case "0":
                        if (isNaN(value2)) {
                            modifyTableTdText.text("Not a number");
                        } else {
                            modifyTableTdText.text(parseInt(value2));
                        }
                        break;
                    case "2":
                        modifyTableTdText.text("" + value2);
                        break;
                    case "3":
                        var val2 = $("#type-number-sel").val();
                        if (val2.trim() !== "") {
                            modifyTableTdText.text(moment(value2, val2.toUpperCase(), true));
                        } else {
                            modifyTableTdText.text(moment(value2, $("#numeral-number-sel option:selected").text().toUpperCase(), true));
                        }
                        break;
                }
            }
            modifyTableTdText.css("text-align", "center");
            modifyTableTd2Text.css("text-align", "center");
            modifyTableTrText.append(modifyTableTd2Text, modifyTableTdText);
            textTable.append(modifyTableTrText);
        });
    }

    /*
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
    */

    $("#modal-modify-numeric-new").click(function () {
        var action = $("#sel-mod-num").val();
        var title = $("#modify-new-number").val();
        var addTo = $("#numeral-number-sel").val();
        var isIn = false;
        $.each(allData.titles, function (l, value) {
            if (value.title.trim() === title.trim()) {
                isIn = true;
            }
        });
        if (isIn) {
            alert("Column with this title already exist");
        } else if (title.trim() === "") {
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
                    sendActionToModify(1, "toPercentNew", "Numeric", keyToSend, all, title, $("#roundSel").val());
                    hideAll();
                    break;
                case "1":
                    sendActionToModify(1, "addColumnNew", "Numeric", keyToSend, addTo, title);
                    hideAll();
                    break;
                case "2":
                    sendActionToModify(1, "subtractColumnNew", "Numeric", keyToSend, addTo, title);
                    hideAll();
                    break;
                case "3":
                    var text = $("#newValue1").text();
                    if (!isNaN(text) && text.trim() !== "") {
                        sendActionToModify(1, "expressionNew", "Numeric", keyToSend, $("#type-number-sel").val(), title);
                        hideAll();
                    } else {
                        console.log("Neposílám");
                    }
                    break;
            }
        }
    });

    /*
    $("#modal-modify-text-save").click(function () {
        var action = $("#sel-mod-text").val();
        var keyToSend = allData.titles[selectedColumn].title;
        var text = $("#type-text-sel").val();
        switch (action) {
            case "0":
                sendActionToModify(0, "remove", keyToSend, text);
                break;
            case "1":
                var re = new RegExp(text);
                sendActionToModify(0, "regEx", keyToSend, re);
                break;
        }
        hideAll();
    });
    */

    $("#modal-modify-text-new").click(function () {
        var action = $("#sel-mod-text").val();
        var title = $("#modify-new-text").val();
        var text = $("#type-text-sel").val();
        var isIn = false;
        $.each(allData.titles, function (l, value) {
           if (value.title.trim() === title.trim()) {
               isIn = true;
           }
        });
        if (isIn) {
            alert("Column with this title already exist");
        } else if (title.trim() === "") {
            alert("Missing column name");
        } else {
            var keyToSend = allData.titles[selectedColumn].title;
            switch (action) {
                case "0":
                    sendActionToModify(1, "removeNew", "Text", keyToSend, text, title);
                    break;
                case "1":
                    var re = new RegExp(text);
                    sendActionToModify(1, "regExNew", "Text", keyToSend, re, title);
                    break;
            }
            hideAll();
        }
    });

    $("#modal-modify-date-new").click(function () {
        var action = $("#sel-mod-date").val();
        var title = $("#modify-new-date").val();
        var text = $("#type-text-date").val();
        var isIn = false;
        $.each(allData.titles, function (l, value) {
            if (value.title.trim() === title.trim()) {
                isIn = true;
            }
        });
        if (isIn) {
            alert("Column with this title already exist");
        } else if (title.trim() === "") {
            alert("Missing column name");
        } else {
            var keyToSend = allData.titles[selectedColumn].title;
            switch (action) {
                case "0":
                    sendActionToModify(1, "toDaysNew", "Text", keyToSend, title);
                    break;
                case "1":
                    var secondKey = $("#date-number-sel").val();
                    sendActionToModify(1, "diffDateNew", "Text", keyToSend, secondKey, title);
                    break;
            }
            hideAll();
        }
    });

    $("#modal-type-save").click(function () {
        var newType = $("#type-select").val();
        switch (newType) {
            case "0":
                sendActionToModify(3, "changeType", "Numeric", selectedColumn, "Numeric");
                break;
            case "2":
                sendActionToModify(3, "changeType", "Text", selectedColumn, "Text");
                break;
            case "3":
                var format = "";
                var numberSel = $("#type-number-sel").val();
                if (numberSel.trim() !== "") {
                    format = numberSel.split("D").join("d").split("M").join("m");
                } else {
                    format = $("#numeral-number-sel").val();
                }
                sendActionToModify(3, "changeType", "Date", selectedColumn, "Date", format);
        }

    });

    $("#continue").click(function () {
        location.href = 'api/getData.php';
    });

    function sendActionToModify(isNew, action, type, firstColumn, parameter, parameter2, parameter3) {
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
        if (parameter3) {
            formData.append("parameter3", parameter3);
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
                        switch (type) {
                            case "Numeric":
                                allData.types.push({'type': 'Numeric'});
                                break;
                            case "Text":
                                allData.types.push({'type': 'Text'});
                                break;
                            case "Date":
                                allData.types.push({'type': 'Date', 'format': parameter});
                                break;
                        }
                        console.log("id: ", data.title.id);
                        console.log(allData.titles.length - 1, allData);
                        newLine(allData.titles.length - 1, allData);
                        console.log(allData);
                        break;
                    case 2:
                        allData.rows.splice(selectedColumn, 1);
                        allData.titles.splice(selectedColumn, 1);
                        allData.types.splice(selectedColumn, 1);
                        console.log(allData);
                        var infoTable = $("#info-table");
                        infoTable.empty();
                        var th = $("<tr><th>Atribute name</th><th>Data type</th><th>Number of unique values</th><th></th><th></th></tr>");
                        infoTable.append(th);
                        firstValues = [];
                        for (var i = 0, len = allData.titles.length; i < len; i++) {
                            newLine(i, allData);
                        }
                        break;
                    case 3:
                        switch (type) {
                            case "Numeric":
                                allData.types[selectedColumn] = {'type': 'Numeric'};
                                break;
                            case "Text":
                                allData.types[selectedColumn] = {'type': 'Text'};
                                break;
                            case "Date":
                                allData.types[selectedColumn] = {'type': 'Date', 'format': parameter};
                                break;
                        }

                        var infoTable = $("#info-table");
                        infoTable.empty();
                        var th = $("<tr><th>Atribute name</th><th>Data type</th><th>Number of unique values</th><th></th><th></th></tr>");
                        infoTable.append(th);
                        firstValues = [];
                        for (var i = 0, len = allData.titles.length; i < len; i++) {
                            newLine(i, allData);
                        }
                        break;
                }
                hideAll();
            },
            error: function () {
                hideAll();
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
        var newTr = $("<tr id='line-" + i +"'></tr>");
        var td1 = $("<td></td>");
        td1.text(data.titles[i].title);
        newTr.append(td1);
        var td2 = $("<td></td>");
        var type = data.types[i];
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
                m++;

            }
        });

        allData.titles[i].type = type;
        var buttonPrep = $("<button class='button-prep change-type' id='" + type.type + "-changeType-" + i +"'>" + type.type +"</button>");
        buttonPrep.click(function (event) {
            var id = event.target.id;
            var idArr = id.split("-");
            selectedColumn = idArr[2];
            var typeSelect = $("#type-select");
            switch (type.type) {
                case "Numeric":
                    typeSelect.val(0);
                    typeContent.empty();
                    showNewValues(firstValues[idArr[2]], 4);
                    break;
                case "Text":
                    typeSelect.val(2);
                    typeContent.empty();
                    showNewValues(firstValues[idArr[2]], 4);
                    break;
                case "Date":
                    typeSelect.val(3);
                    typeContent.empty();
                    console.log("type 3");
                    var originalValues = ['d.m.Y', 'd/m/Y', 'd-m-Y', 'Y.m.d', 'Y/m/d', 'Y-m-d', 'm.d.Y', 'm/d/Y', 'm-d-Y'];
                    lab = $("<label for='numeral-number-sel'>Date format: </label>");
                    sel = $("<select id='numeral-number-sel' style='margin-bottom: 15px'></select>");
                    sel.append($("<option value='d.m.Y'>D.M.Y</option>"));
                    sel.append($("<option value='d/m/Y'>D/M/Y</option>"));
                    sel.append($("<option value='d-m-Y'>D-M-Y</option>"));
                    sel.append($("<option value='Y.m.d'>Y.M.D</option>"));
                    sel.append($("<option value='Y/m/d'>Y/M/D</option>"));
                    sel.append($("<option value='Y-m-d'>Y-M-D</option>"));
                    sel.append($("<option value='m.d.Y'>M.D.Y</option>"));
                    sel.append($("<option value='m/d/Y'>M/D/Y</option>"));
                    sel.append($("<option value='m-d-Y'>M-D-Y</option>"));
                    var isIn = false;
                    $.each(originalValues, function (l, val) {
                        if (val === allData.titles[idArr[2]].type.format) {
                            isIn = true;
                            sel.val(val);
                        }
                    });

                    var lab2 = $("<label for='type-number-sel' style='margin-top: 10px'>Different: </label>");
                    var sel2 = $("<input type='text' id='type-number-sel'>");

                    if (!isIn && allData.titles[idArr[2]].type.format !== null) {
                        var format = allData.titles[idArr[2]].type.format;
                        var days = format.replace('d', 'D');
                        var months = days.replace('m', 'M');
                        sel.prop('disabled', true);
                        sel2.val(months);

                    } else if (!isIn) {
                        sel.val(originalValues[0]);
                    }

                    sel.change(function () {
                        showNewValues(firstValues[idArr[2]], 4);
                    });

                    sel2.keyup(function () {
                        if (sel2.val().trim() !== "") {
                            sel.prop('disabled', true);
                            showNewValues(firstValues[idArr[2]], 4);
                        } else {
                            sel.prop('disabled', false);
                        }
                    });

                    typeContent.append(lab, sel, lab2, sel2);
                    showNewValues(firstValues[idArr[2]], 4);
                    break;
            }
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
        var button1 = $("<button class='button-prep' id='"+ type.type + "-modify-" + i +"'>Modify</button>");
        var button2 = $("<button class='button-prep-danger show-confirm' id='" + type.type + "-delete-" + i + "'>Delete</button>");
        textContent.empty();
        lab = $("<label for='type-text-sel'>To remove: </label>");
        sel = $("<input type='text' id='type-text-sel'>");
        sel.keyup(function () {
            console.log("changing");
            showNewValues(firstValues[selectedColumn], 3);
        });
        textContent.append(lab, sel);
        showNewValues(firstValues[selectedColumn], 3);
        button1.click(function (event) {
            console.log("clicked");
            var id = event.target.id;
            var idArr = id.split("-");
            selectedColumn = idArr[2];
            switch (idArr[0]) {
                case "Text":
                    textSelect.val("0");
                    textContent.empty();
                    lab = $("<label for='type-text-sel'>To remove: </label>");
                    sel = $("<input type='text' id='type-text-sel'>");
                    sel.keyup(function () {
                        console.log("changing");
                        showNewValues(firstValues[selectedColumn], 3);
                    });
                    textContent.append(lab, sel);
                    // showValues(firstValues[idArr[2]], 3);
                    showNewValues(firstValues[idArr[2]], 3);
                    $("#modify-new-text").val(allData.titles[selectedColumn].title + " - new");
                    $("#modal-modify-text").css("display", "inline-block");
                    break;
                case "Numeric":
                    numSelect.val("0");
                    // showValues(firstValues[idArr[2]], 1);
                    numContent.empty();
                    var labRound = $("<label for='roundSel'>Round precision:</label>");
                    var selRound = $("<select id='roundSel'></select>");
                    selRound.append($("<option value='0'>0</option>"));
                    selRound.append($("<option value='1'>1</option>"));
                    selRound.append($("<option value='2'>2</option>"));
                    selRound.append($("<option value='3'>3</option>"));
                    selRound.append($("<option value='4'>4</option>"));
                    selRound.append($("<option value='5'>5</option>"));
                    selRound.val("2");
                    selRound.change(function () {
                        showNewValues(firstValues[selectedColumn], 1);
                    });
                    numContent.append(labRound, selRound);
                    showNewValues(firstValues[idArr[2]], 1);
                    $("#modify-new-number").val(allData.titles[selectedColumn].title + " - new");
                    $("#modal-modify-numeric").css("display", "inline-block");
                    break;
                case "Date":
                    dateSelect.val("0");
                    dateContent.empty();
                    showNewValues(firstValues[idArr[2]], 2);
                    $("#modify-new-date").val(allData.titles[selectedColumn].title + " - new");
                    $("#modal-modify-date").css("display", "inline-block");
            }
            $("#modal-background").css("display", "inline-block");
        });
        button2.click(function (event) {
            var id = event.target.id;
            var idArr = id.split("-");
            selectedColumn = idArr[2];
            sendActionToModify(2, "delete", selectedColumn);
        });
        td5.append(button1, button2);
        newTr.append(td5);
        $("#info-table").append(newTr);
    }

    function getDay(day) {
        console.log(allData.titles[selectedColumn].type);
        var preDate = moment(day, allData.titles[selectedColumn].type.format.toUpperCase());
        var date = new Date(preDate);
        console.log("date:", date);
        var weekday = new Array(7);
        weekday[0] = "Sunday";
        weekday[1] = "Monday";
        weekday[2] = "Tuesday";
        weekday[3] = "Wednesday";
        weekday[4] = "Thursday";
        weekday[5] = "Friday";
        weekday[6] = "Saturday";
        return weekday[date.getDay()];
    }

    function getDifference(day, k) {
        var preDate = moment(day, allData.titles[selectedColumn].type.format.toUpperCase());
        console.log(preDate);
        var value = $("#date-number-sel").val();
        var date = new Date(preDate);
        var preDate2;
        var date2;
        $.each(allData.titles, function (key, val) {
            if (value === val.title) {
                var keys = Object.keys(allData.rows[key]);
                console.log(keys[k], day);
                preDate2 = moment(keys[k], val.type.format.toUpperCase());
                console.log(preDate2);
                date2 = new Date(preDate2);
            }
        });
        console.log(date, date2);
        console.log(date.getTime(), date2.getTime());
        var seconds = ABS((parseInt(date.getTime()) - parseInt(date2.getTime())) / 1000);

        var years = Math.floor(seconds / (3600*24*365));
        seconds -= years * 3600 * 24 * 365;
        var days = Math.floor(seconds / (3600*24));
        seconds  -= days * 3600 * 24;
        var hrs   = Math.floor(seconds / 3600);
        seconds  -= hrs * 3600;
        var mnts = Math.floor(seconds / 60);
        seconds  -= mnts * 60;
        var toReturn = "";
        if (years != 0) {
            toReturn = toReturn + years + " years ";
        }
        if (days != 0) {
            toReturn = toReturn + days + " days ";
        }
        if (hrs != 0) {
            toReturn = toReturn + hrs + " Hrs ";
        }
        if (mnts != 0) {
            toReturn = toReturn + mnts + " Minutes ";
        }
        if (seconds != 0) {
            toReturn = toReturn + seconds + " Seconds";
        }
        return toReturn;
    }

    function ABS(x) {
        if (x > 0) {
            return x;
        } else {
            return -x;
        }
    }
    
    function SIGN(x) {
        var toReturn = 0;
        if (x === 0) {
            toReturn = 0;
        } else if (x < 0) {
            toReturn = -1;
        } else if (x > 0) {
            toReturn = 1;
        }
        
        return toReturn;
    }
    
    function GCD(a, b) {
        return (!b)?a:GCD(b,a%b);
    }

    function LCM(a, b) {
        return (a * b) / GCD(a, b);
    }

    function POWER(x, y) {
        return Math.pow(x, y);
    }

    function PRODUCT(x, y) {
        return x * y;
    }

    function SQRT(x) {
        return Math.pow(x, 2);
    }

    function QUOTIENT(x, y) {
        return x / y;
    }

    function MOD(x, y) {
        return x % y;
    }

});