// js before document.ready

// js after document.ready
$(document).ready(function() {

    // set focus on .focused
    $(".focused").focus();

    // no click
    $(document).off('click', '.no_click').on('click', '.no_click', function(e) {
        e.stopPropagation();
    });

    //.href_table tr click
    $(document).off("click", ".active-table tbody tr").on("click", ".active-table tbody tr", function() {
        window.location = $(this).attr('href');
        return false;
    });

    // pagination
    $(document).off("click", ".pagination li a").on("click", ".pagination li a", function(e) {

        //---first of all will prevent the default work of the pagination-------
        e.preventDefault();
        var state = {};
        //----then will get the url of the link---------------------------
        var url = $(this).attr('href');
        //------here will get the page number from the link-----------------------            
        var page = url.substring(url.lastIndexOf("?") + 6);
        //-----if there is no variable with this name then will take default "1"
        state['page'] = page ? page : "1";
        //----will push the new page number on the querystring(fragment)----
        $.bbq.pushState(state);

    });
    
    $(document).off("click", ".back_btn").on("click", ".back_btn", function(e) {
        e.preventDefault();
        window.history.back();
    });
    
    
    // date picker
    $('.input-datepicker').datepicker({
        defaultDate: new Date(),
        format: 'dd-mm-yyyy',
        todayHighlight: true,            
        clearBtn: true,
    });
    
})