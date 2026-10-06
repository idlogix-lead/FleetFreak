<!-- Modal -->
<div class="modal fade partner_modal" style='min-width:600px;' id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <form action="/test" method="POST" id='customer_form'>
        <input type="hidden" name="business_partner_id" id='business_partner_cust_id'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                      
                        $form_type = 'customer';
                    @endphp

                    @include('customer.form' )
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit"  class="btn btn-outline-primary" >Save</div>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
        
  
    $(document).ready(function(){

        $("#customer_form").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            let data = form.serializeArray();
            let arr_data = flattenArray(data);

            ffsQuiet($.post(get_host()+'/customer/modal/create', {
                
                        "_token":"{{csrf_token()}}",
                        // "_method":"POST",
                        "data": JSON.stringify(arr_data),
                        //console.log(data);
                    })).then(function(data){
                        console.log(data);
                        $('#customer_partner_id').empty();
                        $('#customer_partner_id').append('<option value="'+data.id+'" selected>'+data.name+'</option>');
                        $('#whatsapp_no').val(data.whatsapp_no);
                        $('#passport').val(data.passport);
                        $('#phone_no').val(data.phone_no);
                        $('#cnic').val(data.cnic);
                        $('#address1').val(data.address1);
                        $('#email').val(data.email);
                        $('.partner_modal').modal('hide');
                        toastr.options = {"positionClass": "toast-top-right",}
                        toastr.success("customer created Successfully", 'Success');

                    }).fail(function(xhr){
                        toastr.options = {"positionClass": "toast-top-right",}
                        toastr.error(xhr.responseJSON.error, 'Error');

                    })
                
            
            

            console.log(arr_data,e);
        });
        

        
    }); 
    function flattenArray(serializedArray) {
        return serializedArray.reduce((acc, curr) => {
            acc[curr.name] = curr.value;
            return acc;
        }, {});
    }

    
</script>