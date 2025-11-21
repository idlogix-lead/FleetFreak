<!-- Modal -->
<div class="modal fade" id="payNowModal" tabindex="-1" aria-labelledby="payNowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             
            @if(isset($payment_header))
            <form id="payNowForm" action="{{ route('pending_rental_invoices.update',$payment_header->id) }}" method="POST">
            @else
            <form id="payNowForm" action="{{ route('pending_rental_invoices.store') }}" method="POST">
            @endif
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="payNowModalLabel">Confirm Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to complete ride?
                    <input type="hidden" name="customer" id="modalCustomer">
                    <input type="hidden" name="date" id="modalDate">
                    <input type="hidden" name="description" id="modalDescription">
                    <input type="hidden" name="total_amount" id="modalTotalAmount">
                    <div id="hidden-fields-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
