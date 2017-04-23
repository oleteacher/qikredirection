jQuery(document).ready(function($){
    $('#pg-r-settings #add-entry').click(function(){
        var tr = $('#pg-r-settings #entries').find('tr:first').clone();
        tr.find('input[type=text]').val('').removeAttr('disabled');
        $('#pg-r-settings #entries').append(tr);
        tr.show();
    });
    
    $('#pg-r-settings #entries .remove-entry').live('click', function(){
        $(this).parents('.entry').remove();
    });
});