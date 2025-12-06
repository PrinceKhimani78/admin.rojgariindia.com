$('.select_multi').select2({
    minimumResultsForSearch: 20
});

$(function(){
    $.validate({
        modules : 'security',
        validateHiddenInputs : true,
        scrollToTopOnError : false
    });
    
    $('#job_location_country').change(function(){
        var val = this.value;
        $.ajax({
        type: "POST",
        url: "code/ajaxdata.php?getdata=getstate",
        data:'country_id='+val,
        success: function(data){
                $("#job_location_state").html(data);
        }
        });
    });
    $('#job_location_state').change(function(){
        var val = this.value;
        $.ajax({
        type: "POST",
        url: "code/ajaxdata.php?getdata=getcity",
        data:'state_id='+val,
        success: function(data){
                $("#job_location").html(data);
        }
        });
    });
});