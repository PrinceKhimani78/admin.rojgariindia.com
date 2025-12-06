$('.select_multi').select2({
    minimumResultsForSearch: 20
});
$('#location_search').select2({
  language: { errorLoading:function(){ return "No result found"; }},
  minimumInputLength: 1,
  ajax: {
    placeholder: "Search City",
    url: 'code/ajaxdata.php?get_city_search=true',
    dataType: 'json',
    delay: 50,
    processResults: function (data) {
      return {
        results: data
      };
    },
    cache: true
  }
});
function selectionClicked(selection,elemname) {
    //document.getElementsByName(elemname)[0].disabled = false;
    element = document.getElementsByName(elemname)[0];
    for(var i=1;i<element.length;i++)
    {
      if (document.getElementsByName(elemname)[0].options[i].disabled == true) {
        document.getElementsByName(elemname)[0].options[i].removeAttribute('disabled');
        document.getElementsByName(elemname)[0].options[i].removeAttribute('selected');
      }
    }
    for(i=0;i<=selection.value;i++)
    {
      j=i+1;
      document.getElementsByName(elemname)[0][i].disabled = true;
      document.getElementsByName(elemname)[0][j].setAttribute('selected', 'selected');
      document.getElementsByName(elemname)[0][i].removeAttribute('selected', 'selected');
    }
}
$(function(){
    stylemenu();
    $(window).scroll(function () {
        stylemenu();
    });
    $('.mtogglebutton').click(function(){
      $('.navigation').toggleClass('activenav');
      $('.mtogglebutton .fa').toggleClass('fa-times fa-bars');
      $('.overlay').toggleClass('filloverlay');
      $('body').toggleClass('noyscroll');
    });
    $('.overlay').click(function(){
      $('.mtogglebutton').click();
    });
    
    $('#country').change(function(){
        var val = this.value;
        $.ajax({
        type: "POST",
        url: "code/ajaxdata.php?getdata=getstate",
        data:'country_id='+val,
        success: function(data){
                $("#state").html(data);
                $("#state").selectpicker('refresh');
        }
        });
    });
    $('#state').change(function(){
        var val = this.value;
        $.ajax({
        type: "POST",
        url: "code/ajaxdata.php?getdata=getcity",
        data:'state_id='+val,
        success: function(data){
                $("#location").html(data);
                $("#location").selectpicker('refresh');
        }
        });
    });
    $('.resume_update').click(function(){
      $(this).hide();
      $(this).next('.form-group').show();
      $(this).next('.form-group').find('input[type="file"]').attr('data-validation','required');
    });
});
function stylemenu() {
    if ($(this).scrollTop() > 0){
    $('body').addClass("floatmenu");
  } else {
    $('body').removeClass("floatmenu");
  }
}