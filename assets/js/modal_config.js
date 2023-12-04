/*
 *  Document   : modal_config.js 
 *  Description: for generating modal on click of a button
 */

/*
 * @params
 *      element     => element from where modal is generated.
 *      role        => type of modal [alert,confirm,form,information,warning],
 *      data        => data for ajax,
 *      index       => index of modal generated.
 *      header      => header you want to show on modal.
 *      callback    => callback function called after modal is hide.
 *      on_confirm  => called when user clicks the ok button on click of ok in confirm box
 *      view_path   => load a view from the path specified.
 *      body        => direct append body to modal-body.
 *      form_submit => function how to submit the form.
 */
function generate_modal(data) {
    var element = data.element,
            role = data.role,
            postData = data.data ? data.data : {},
            index = data.index,
            header = data.header,
            callback = data.callback ? data.callback : false,
            innercallback = data.innercallback ? data.innercallback : false,
            on_confirm = data.on_confirm,
            view_path = data.view_path,
            body = data.body,
            form_submit = data.form_submit,
            modal_id = element ? element.attr("id") + "_modal" : "my_modal",
            modal_class = data.modal_class ? data.modal_class:'modal-lg';
    
    var buttons = ''

    if (role == 'alert') {
        buttons = '<button type="button" class="btn btn-danger btn-sm" id="alert_ok" data-dismiss="modal">OK</button>';
    }
    else if (role == 'confirm') {
        buttons = '<button type="button" class="btn btn-primary btn-sm" id="confirm_ok">OK</button>' +
                '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="confirm_cancel">Close</button>';
    }
    else if (role == 'form') {
        buttons = '';
    }
    else if (role == 'information') {
        buttons = '<button type="button" class="btn btn-primary btn-sm" id="information_ok" data-dismiss="modal">OK</button>';
    }
    else if (role == 'warning') {
        buttons = '<button type="button" class="btn btn-warning btn-sm" id="warning_ok" data-dismiss="modal">OK</button>';
    }
    else {
        buttons = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="close">Close</button>';
    }

    var html = '<div class="modal fade" id="' + modal_id + '" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
            '<div class="modal-dialog '+ modal_class +'">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
            '<h4 class="modal-title" id="myModalLabel">' + data.header + '</h4>' +
            '</div>' +
            '<div class="modal-body">' +
            '</div>' +
            '<div class="modal-footer">' +
            buttons +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    $("body").prepend(html);

    var modal_ele = $("#" + modal_id);

    modal_ele.css({"zIndex": parseInt($("#" + modal_id).css('zIndex')) + parseInt(index)});

    if (view_path) {
        $.ajax({
            url: site_url(view_path),
            type: 'post',
            data:postData,
            beforeSend: function() {
                modal_ele.find(".modal-body").html('<div class="text-center"><i class="fa fa-circle-o-notch fa-spin fa-3x text-primary"></i></div>');
            },
            success: function(result) {
                modal_ele.find(".modal-body").html(result);
                if (innercallback) {
                    innercallback();
                }
                // set focus
                modal_ele.find(".modal-body .focused").focus();
            }
        });
    }
    else if (body) {
        modal_ele.find(".modal-body").html(body);
    }
    else {
        modal_ele.find(".modal-body").html("<p>Cant find any data to display!</p>");
    }

    modal_ele.modal('show');

    $(modal_ele).on('shown.bs.modal', function() {
        if (body) {
            setTimeout(function() {
                modal_ele.find(".modal-footer button:first").focus();
                modal_ele.find(".modal-body .focused").focus();
            }, 300);
        } else {
            setTimeout(function() {
                modal_ele.find(".modal-footer button:first").focus();
                modal_ele.find(".modal-body .focused").focus();
            }, 300);
        }
    });
    $(modal_ele).on('hidden.bs.modal', function() {
        modal_ele.remove();
        if (callback)
            callback();
    });
    $(document).off("click", "#confirm_ok").on("click", "#confirm_ok", function() {
        modal_ele.modal('hide');
        on_confirm(element);
    });
    $(document).off("keyup", modal_ele).off("keyup", modal_ele, function(e) {
        if (e.keyCode == 27)
            $(this).modal('hide');
    });
}


function site_url(path) {
    path = typeof path !== 'undefined' ? path : '';

    if (window.location.host == 'localhost' || window.location.host == 'sheth-pc')
        var site_url = (path != '') ? path : "";
    else
        var site_url = (path != '') ? path : '';

    return site_url;
}