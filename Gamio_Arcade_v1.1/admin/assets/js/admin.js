$(document).ready(function() {
    //$('#item_desc').trumbowyg();
    //$("#item_desc_2 ").trumbowyg();

    $("#uploadFile").change(function() {
        cm_readFile(this, 'preview_FS');
        $("#cm_hidden_doc_file").show();
    });

    var maxField = 10; //Input fields increment limitation
    var addButton = $('#add_input'); //Add button selector
    var wrapper = $('#features_list'); //Input field wrapper
    //var fieldHTML = '<div><input type="text" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button"><img src="remove-icon.png"/></a></div>'; //New input field html 
    var fieldHTML = '<div id="feature_item" class="input-group mb-3"><input type="text" class="form-control " name="fields[]" placeholder="Field..."><div class="input-group-append"><button class="btn btn-outline-secondary" type="button" id="remove_button"><em class="icon ni ni-cross"></em></button></div></div>';
    var x = 1; //Initial field counter is 1

    //Once add button is clicked
    $(addButton).click(function() {
        //Check maximum number of input fields
        if (x < maxField) {
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });

    //Once remove button is clicked
    $(wrapper).on('click', '#remove_button', function(e) {
        e.preventDefault();
        $(this).closest('#feature_item').remove(); //Remove field html
        x--; //Decrement field counter
    });

    var maxField3 = 10; //Input fields increment limitation
    var addButton3 = $('#add_input3'); //Add button selector
    var wrapper3 = $('#fields_list'); //Input field wrapper
    //var fieldHTML = '<div><input type="text" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button"><img src="remove-icon.png"/></a></div>'; //New input field html 
    var fieldHTML3 = '<div id="field_item" class="row mb-2"><div class="col-md-5"><input type="text" class="form-control" name="fields[]" placeholder="Data name..."></div><div class="col-md-5"><input type="text" class="form-control" name="values[]" placeholder="Data value..."></div><div class="col-md-2"><button class="btn btn-outline-secondary btn-block" type="button" id="button-addon2"><em class="icon ni ni-cross"></em></button></div></div>';
    var x3 = 1; //Initial field counter is 1

    //Once add button is clicked
    $(addButton3).click(function() {
        //Check maximum number of input fields
        if (x3 < maxField3) {
            x3++; //Increment field counter
            $(wrapper3).append(fieldHTML3); //Add field html
        }
    });

    //Once remove button is clicked
    $(wrapper3).on('click', '#remove_button', function(e) {
        e.preventDefault();
        $(this).closest('#field_item').remove(); //Remove field html
        x3--; //Decrement field counter
    });

    $("#select_tb_image").on("change", function() {
        $("#sd_select_image_box").removeClass("hidden_box");
        $("#sd_select_icon_box").addClass("hidden_box");
    });

    $("#select_tb_icon").on("change", function() {
        $("#sd_select_image_box").addClass("hidden_box");
        $("#sd_select_icon_box").removeClass("hidden_box");
    });

    $("#admin_accept_file ").on("click", function() {
        $.AcceptFile($(this).data("id"), $(this).data("type"));
    });

    $("#admin_reject_file ").on("click", function() {
        $.RejectFile($(this).data("id"), $(this).data("type"));
    });

    $("#item_f_image").on("click", function() {
        $("#item_f_image_open").click();
    });

});

$(function() {
    "use strict";
    $.AcceptFile = function(id, type) {
        var url = $("#wurl").val();
        var adm_f = $("#wadmin").val();
        var data_url = url + "/" + adm_f + "/requests/items.php?a=accept&id=" + id + "&type=" + type;
        $.ajax({
            type: "GET",
            url: data_url,
            dataType: "json",
            success: function(data) {
                if (data.status == "success") {
                    $.AdmNoti("Success!", data.msg, "success");
                    if (data.content !== "") {
                        if (data.removeold == "1") {
                            $(data.target).html(data.content);
                            $(data.removetarget).remove();
                        } else {
                            var content = "#item_" + type + "_content";
                            $(content).html(data.content);
                        }
                    }
                } else {
                    $.AdmNoti("Error!", data.msg, "error");
                }
            },
            error: function(request, status, error) {
                console.log(request.responseText);
            }
        });
    };
});

$(function() {
    "use strict";
    $.RejectFile = function(id, type) {
        var url = $("#wurl").val();
        var adm_f = $("#wadmin").val();
        var data_url = url + "/" + adm_f + "/requests/items.php?a=reject&id=" + id + "&type=" + type;
        $.ajax({
            type: "GET",
            url: data_url,
            dataType: "json",
            success: function(data) {
                if (data.status == "success") {
                    $.AdmNoti("Success!", data.msg, "success");
                } else {
                    $.AdmNoti("Error!", data.msg, "error");
                }
            },
            error: function(request, status, error) {
                console.log(request.responseText);
            }
        });
    };
});

function cm_readFile(input, element) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#' + element).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

$(function() {
    "use strict";
    $.AdmNoti = function(title, message, cl) {
        Swal.fire(title, message, cl);
    };
});