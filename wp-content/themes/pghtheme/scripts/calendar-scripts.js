    

    function prevMonthSwap(prevMonthVal,prevYearVal) {
        jQuery("#calendar-container").html("<h1>LOADING</h1>").show();
        jQuery.post(ajaxurl, {action: 'my_special_action', month: prevMonthVal, year: prevYearVal}, function(data){
            jQuery("#calendar-container").html(data).show();
        });
        return false;
    }

    function nextMonthSwap(nextMonthVal,nextYearVal) {
        jQuery("#calendar-container").html("<h1>LOADING</h1>").show();
        jQuery.post(ajaxurl, {action: 'my_special_action', month: nextMonthVal, year: nextYearVal}, function(data){
            jQuery("#calendar-container").html(data).show();
        });
        return false;
    }

    // Attach the link event handlers
    jQuery(document).ready(function($) {
        $('#prev-link').click(function(){ return prevMonthSwap(<?php echo $prevMonthVal; ?>,<?php echo $prevYearVal; ?>)});
        $('#next-link').click(function(){ return nextMonthSwap(<?php echo $nextMonthVal; ?>,<?php echo $nextYearVal; ?>)});
    });