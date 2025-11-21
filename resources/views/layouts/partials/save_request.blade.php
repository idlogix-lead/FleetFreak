<script>
$('#btn_submit_req').click(function(e){
    // if(req_item_count <= 0){
    msgboxbox.show('Your Request has been submited','info', null);
    //     e.preventDefault();
    //     e.stopPropagation();
    // }
    $('#req_status').val('pending');
    console.log($('#req_status').val());
    // validate_all(e);
    // total_expense();
});
$('#btn_save').click(function(e){
    console.log('enter btn save');

    console.log($('#req_status').val());

    // if(req_item_count <= 0){
    msgboxbox.show('Your Info has been saved to draft','info', null);

    //     e.preventDefault();
    //     e.stopPropagation();
    // }
    $('#req_status').val('draft');
    // validate_all(e);
    // total_expense();
});
</script>