$(document).ready(function ($) {

    var es;
    var tableData = [
        { data: ["119736", "FL", "CLAY COUNTY", "498960", "498960", "498960", "498960", "498960"]},
        { data: ["448094", "FL", "CLAY COUNTY", "1322376.3", "1322376.3", "1322376.3", "1322376.3", "1322376.3"]},
        { data: ["206893", "FL", "CLAY COUNTY", "190724.4", "190724.4", "190724.4", "190724.4", "190724.4"]},
        { data: ["333743", "FL", "CLAY COUNTY", "0", "79520.76", "0", "0", "79520.76"]},
        { data: ["172534", "FL", "CLAY COUNTY", "0", "254281.5", "0", "254281.5", "254281.5"]},
        { data: ["785275", "FL", "CLAY COUNTY", "0", "515035.62", "0", "0", "515035.62"]},
        { data: ["995932", "FL", "CLAY COUNTY", "0", "19260000", "0", "0", "19260000"]},
        { data: ["223488", "FL", "CLAY COUNTY", "328500", "328500", "328500", "328500", "328500"]},
        { data: ["433512", "FL", "CLAY COUNTY", "315000", "315000", "315000", "315000", "315000"]},
        { data: ["142071", "FL", "CLAY COUNTY", "705600", "705600", "705600", "705600", "705600"]},
        { data: ["253816", "FL", "CLAY COUNTY", "831498.3", "831498.3", "831498.3", "831498.3", "831498.3"]},
        { data: ["894922", "FL", "CLAY COUNTY", "0", "24059.09", "0", "0", "24059.09"]}
        ];

    var dataTable = $("#data-table");

    /*
    $("#upload").click(function () {
        window.location.href = "main-page.html";
    });
     */

    $("#file").change(function () {
        $("#config-div").css("display", "inline-block");
        //$("#data-table-div").css("display", "block");
        dataTable.empty();
        var trHead = $("<tr></tr>");
        var i = 1;
        while (i < 9) {
            var th = $("<th></th>");
            th.text("Column " + i);
            trHead.append(th);
            i++;
        }
        dataTable.append(trHead);
        $.each(tableData, function (index, value) {
            console.log("tr");
            var tr = $("<tr></tr>");
            $.each(value.data, function (index2, value2) {
                console.log("td");
                var td = $("<td></td>");
                td.text(value2);
                tr.append(td);
            });
            dataTable.append(tr);
        })
    });

    $("#upload-form").submit(function (event) {
        event.preventDefault();
        console.log(this);
        var fileInputElement = document.getElementById("file");
        var formData = new FormData();
        console.log(fileInputElement.files[0]);
        formData.append("csv", fileInputElement.files[0]);
        formData.append("encoding", $("#encoding").val());
        formData.append("separator", $("#separator").val());
        console.log("submiting", formData);
        var uploadDiv = $("#progress-upload");
        $("#modal-background").css("display", "block");
        $.ajax({
            xhr: function()
            {
                uploadDiv.css("display", "block");
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        $("#upload-percentage").text((percentComplete * 100).toFixed(0));
                        console.log(percentComplete);
                    }
                }, false);
                return xhr;
            },
            url: "api/upload.php",
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function ( data ) {
                console.log(data);
                uploadDiv.css("display", "none");
                $("#modal-background").css("display", "none");
                window.location = "main-page.html";
            },
            error: function () {
                uploadDiv.css("display", "none");
                $("#modal-background").css("display", "none");
                alert("Network error");
            }
        });
    });

});