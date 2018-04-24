$(document).ready(function ($) {

    var dataTable = $("#data-table");
    var done = true;

    /*
    $("#upload").click(function () {
        window.location.href = "main-page.html";
    });
     */

    if ($("#file").val() != null && $("#file").val() != "") {
        $("#config-div").css("display", "inline-block");
    }

    $("#file").change(function () {
        $("#config-div").css("display", "inline-block");
    });

    $(".hide").click(function () {
        $("#alert-window").css("display", "none");
        $("#modal-background").css("display", "none");
    });

    $("#upload-form").submit(function (event) {
        event.preventDefault();
        console.log(this);
        var fileInputElement = document.getElementById("file");
        var formData = new FormData();
        formData.append("csv", fileInputElement.files[0]);
        formData.append("encoding", $("#encoding").val());
        formData.append("separator", $("#separator").val());
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
                        $("#spinner-modal").css("display", "block");
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
                $("#spinner-modal").css("display", "none");
                console.log(data);
                uploadDiv.css("display", "none");
                window.location = "main-page.html";
            },
            error: function () {
                $("#spinner-modal").css("display", "none");
                uploadDiv.css("display", "none");
                $("#modal-background-background").css("display", "block");
                var alert = $("#alert-content");
                alert.empty();
                alert.append($("<p>Network error</p>"));
                $("#alert-window").css("display", "block");
            }
        });
    });

});