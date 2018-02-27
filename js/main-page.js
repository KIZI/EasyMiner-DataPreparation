$(document).ready(function ($) {
    console.log("loaded");

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

    var iNum = 1;

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


    $("#change-type-1").click(function () {
        $("#modal-change-type").css("display", "inline-block");
        $("#modal-background").css("display", "inline-block");
    });

    $("#modify-1").click(function () {
        $("#modal-modify-numeric").css("display", "inline-block");
        $("#modal-background").css("display", "inline-block");
    });

    $("#change-type-2").click(function () {
        $("#modal-change-type").css("display", "inline-block");
        $("#modal-background").css("display", "inline-block");
    });

    $("#change-type-3").click(function () {
        $("#modal-change-type").css("display", "inline-block");
        $("#modal-background").css("display", "inline-block");
    });

    $("#modify-3").click(function () {
        $("#modal-modify-date").css("display", "inline-block");
        $("#modal-background").css("display", "inline-block");
    });

    $(".change-type-4").click(function () {
        $("#modal-change-type").css("display", "inline-block");
        $("#modal-background").css("display", "inline-block");
    });

    $("#modify-4").click(function () {
        $("#modal-modify-text").css("display", "inline-block");
        $("#modal-background").css("display", "inline-block");
    });

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
                break;
            case "1":
                numContent.empty();
                lab = $("<label for='numeral-number-sel'>Column: </label>");
                sel = $("<select id='numeral-number-sel'></select>");
                sel.append($("<option selected>Sloupec 2</option>"));
                sel.append($("<option>Sloupec 3</option>"));
                sel.append($("<option>Sloupec 4</option>"));
                numContent.append(lab, sel);
                break;
            case "2":
                numContent.empty();
                lab = $("<label for='numeral-number-sel'>Column: </label>");
                sel = $("<select id='numeral-number-sel'></select>");
                sel.append($("<option selected>Sloupec 2</option>"));
                sel.append($("<option>Sloupec 3</option>"));
                sel.append($("<option>Sloupec 4</option>"));
                numContent.append(lab, sel);
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
                sel.append($("<option selected>Sloupec 2</option>"));
                sel.append($("<option>Sloupec 3</option>"));
                sel.append($("<option>Sloupec 4</option>"));
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
    }

    function getData() {
        $.post( "api/getData.php", "")
            .done(function( data ) {
                console.log( "Data Loaded", data.titles);
                var firstValues = [];
                for (var i = 0, len = data.titles.length; i < len; i++) {
                    var newTr = $("<tr></tr>");
                    var td1 = $("<td></td>");
                    td1.text(data.titles[i].title);
                    newTr.append(td1);
                    var td2 = $("<td></td>");
                    var buttonPrep = $("<button class='button-prep change-type4'>Text</button>");
                    buttonPrep.click(function () {
                        $("#modal-change-type").css("display", "inline-block");
                        $("#modal-background").css("display", "inline-block");
                    });
                    td2.append(buttonPrep);
                    newTr.append(td2);
                    var td3 = $("<td></td>");
                    var unique = 0;
                    for(var k in data.rows[i]) {
                        if(data.rows[i][k] == 1) {
                            unique++;
                        }
                    }
                    td3.text(unique);
                    newTr.append(td3);
                    var td4 = $("<td style=\"min-width: 300px; max-width: 300px\"></td>");
                    var div = $("<div class='histogram'></div>");
                    td4.append(div);
                    var biggest = 0;
                    var countOfItems = 0;
                    $.each(data.rows[i], function (l, val) {
                        var intVal = parseInt(val);
                        countOfItems++;
                        if (intVal > biggest) {
                            biggest = intVal;
                        }
                    });
                    var widthOfItem = 100 / countOfItems;
                    $m = 0;
                    $.each(data.rows[i], function (l, val) {
                        var item = $("<span></span>");
                        item.addClass("histogram-item");
                        var heightPrc = (parseInt(val) / biggest) * 100;
                        item.css("height", heightPrc + "%").css("min-height", "1px").css("margin-botton", "0px").css("width", widthOfItem + "%");
                        item.prop("title", val + ", " + l);
                        div.append(item);
                        if ($m < 11) {
                            var toAdd;
                            if(l == "") {
                                toAdd = "\"\" - Empty Value"
                            } else {
                                toAdd = l;
                            }
                            if(firstValues[i] == null) {
                                firstValues[i] = [];
                            }
                            firstValues[i].push(toAdd);
                            $m++;
                        }
                    });
                    newTr.append(td4);
                    var td5 = $("<td></td>");
                    var button1 = $("<button class='button-prep' id='"+ i +"'>Modify</button>");
                    var button2 = $("<button class='button-prep-danger show-confirm'>Delete</button>");
                    button1.click(function (event) {
                        console.log("clicked");
                        var id = event.target.id;
                        console.log("id", id, firstValues[id]);
                        var textTable = $("#modify-text-table");
                        textTable.empty();
                        textTable.append($("<tr><th>First 10 values</th></tr>"));
                        $.each(firstValues[id], function (key, value2) {
                            var modifyTableTrText = $("<tr></tr>");
                            var modifyTableTdText = $("<td></td>");
                            modifyTableTdText.text(value2);
                            modifyTableTdText.css("text-align", "center");
                            modifyTableTrText.append(modifyTableTdText);
                            textTable.append(modifyTableTrText);
                        });
                        $("#modal-modify-text").css("display", "inline-block");
                        $("#modal-background").css("display", "inline-block");
                    });
                    td5.append(button1, button2);
                    newTr.append(td5);
                    $("#info-table").append(newTr);
                }
            });
    }

});