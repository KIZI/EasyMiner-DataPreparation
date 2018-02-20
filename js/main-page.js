$(document).ready(function ($) {
    console.log("loaded");

    var histogramItems = [
        {
            value: 60,
            text: "Value 1",
            percent: 60
        },
        {
            value: 30,
            text: "Value 2",
            percent: 50
        },
        {
            value: 24,
            text: "Value 3",
            percent: 50
        },
        {
            value: 16,
            text: "Value 4",
            percent: 40
        },
        {
            value: 10,
            text: "Value 5",
            percent: 20
        },
        {
            value: 8,
            text: "Value 6",
            percent: 20
        },
        {
            value: 8,
            text: "Value 7",
            percent: 20
        },
        {
            value: 8,
            text: "Value 8",
            percent: 20
        },
        {
            value: 8,
            text: "Value 9",
            percent: 15
        },
        {
            value: 8,
            text: "Value 10",
            percent: 14
        },
        {
            value: 4,
            text: "Value 11",
            percent: 13
        },
        {
            value: 4,
            text: "Value 12",
            percent: 10
        },
        {
            value: 4,
            text: "Value 13",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        },
        {
            value: 4,
            text: "Value 14",
            percent: 10
        }
    ];

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

    while (iNum<11) {
        var modifyTableTr = $("<tr></tr>");
        var modifyTableTd = $("<td></td>");
        modifyTableTd.text(iNum);
        modifyTableTd.css("text-align", "center");
        modifyTableTr.append(modifyTableTd);
        $("#modify-numeric-table").append(modifyTableTr);
        var modifyTableTrDate = $("<tr></tr>");
        var modifyTableTdDate = $("<td></td>");
        modifyTableTdDate.text(iNum + ".1.2018");
        modifyTableTdDate.css("text-align", "center");
        modifyTableTrDate.append(modifyTableTdDate);
        $("#modify-date-table").append(modifyTableTrDate);
        var modifyTableTrText = $("<tr></tr>");
        var modifyTableTdText = $("<td></td>");
        modifyTableTdText.text("Text " + iNum);
        modifyTableTdText.css("text-align", "center");
        modifyTableTrText.append(modifyTableTdText);
        $("#modify-text-table").append(modifyTableTrText);
        iNum++;
    }

    $.each(histogramItems, function (i, val) {
        var item = $("<span></span>");
        item.addClass("histogram-item");
        item.css("height", val.percent + "%").css("height", "-=4px");
        item.prop("title", val.value + ", " + val.text);
        $(".histogram").append(item);
    });


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

    $("#change-type-4").click(function () {
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

});