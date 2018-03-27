$(document).ready(function ($) {

    var dataTable = $("#data-table");
    var done = true;

    /*
    $("#upload").click(function () {
        window.location.href = "main-page.html";
    });
     */

    $("#file").change(function () {
        $("#config-div").css("display", "inline-block");
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
        done = false;
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
                        if (percentComplete === 1) {
                            uploadDiv.append($("<h2>Processed <span id='rowsCompleted'>0</span> rows</h2>"));
                            if (!done) {
                                console.log("Pouštím");
                                startTask();
                            }
                        }
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
                done = true;
            },
            error: function () {
                uploadDiv.css("display", "none");
                $("#modal-background").css("display", "none");
                done = true;
                alert("Network error");
            }
        });
    });

    function startTask() {
        $.ajax({
            url: "api/upload-progress.php",
            type: 'GET',
            success: function (data) {
                console.log(data);
                $("#rowsCompleted").text(data);
                setTimeout(function () {
                    if (!done) {
                        startTask();
                    }
                }, 1000)
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

});