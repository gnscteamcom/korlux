$('#approve-btn').click(function(e){
    e.preventDefault();
    
    $('#form-revise').attr('action', $(this).attr('data-url'));
    $('#form-revise').submit();
});

$('#reject-btn').click(function(e){
    e.preventDefault();
    
    $('#form-revise').attr('action', $(this).attr('data-url'));
    $('#form-revise').submit();
});