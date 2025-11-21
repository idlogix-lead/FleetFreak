<!-- Modal -->
<div class="modal fade" id="payNowModal" tabindex="-1" aria-labelledby="payNowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="payNowForm" action="{{ route('bulkpayments.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="payNowModalLabel">Confirm Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to pay now?
                    {{-- <input type="hidden" name="order_id" id="orderId"> --}}
                    <input type="hidden" name="order_no" id="orderNo">
                    <input type="hidden" name="order_detail_no" id="orderDetailNo">
                    <input type="hidden" name="customer_name" id="customerName">
                    <input type="hidden" name="customer_id" id="customerId">
                    <input type="hidden" name="agent_id" id="agentId">
                    <input type="hidden" name="driver_name" id="driverName">
                    <input type="hidden" name="amount" id="amount">
                    <input type="hidden" name="status" id="statusId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.pay-now-btn').on('click', function() {
            // var orderId = $(this).data('order-id');
            var orderNo = $(this).data('order-no');
            var orderDetailNo = $(this).data('order-detail-no');
            var customerName = $(this).data('customer-name');
            var driverName = $(this).data('driver-name');
            var amount = $(this).data('amount');
            var customerId = $(this).data('customer-id');
            var agentId = $(this).data('agent-id');
            var statusId = $(this).data('status-id');

            // $('#orderId').val(orderId);
            $('#orderNo').val(orderNo);
            $('#orderDetailNo').val(orderDetailNo);
            $('#customerName').val(customerName);
            $('#driverName').val(driverName);
            $('#amount').val(amount);
            $('#customerId').val(customerId);
            $('#agentId').val(agentId);
            $('#statusId').val(statusId);

            $('#payNowModal').modal('show');
        });
        $('.draft-btn').on('click', function() {
            // var orderId = $(this).data('order-id');
            var orderNo = $(this).data('order-no');
            var orderDetailNo = $(this).data('order-detail-no');
            var customerName = $(this).data('customer-name');
            var driverName = $(this).data('driver-name');
            var amount = $(this).data('amount');
            var customerId = $(this).data('customer-id');
            var agentId = $(this).data('agent-id');
            var statusId = $(this).data('status-id');

            // $('#orderId').val(orderId);
            $('#orderNo').val(orderNo);
            $('#orderDetailNo').val(orderDetailNo);
            $('#customerName').val(customerName);
            $('#driverName').val(driverName);
            $('#amount').val(amount);
            $('#customerId').val(customerId);
            $('#agentId').val(agentId);
            $('#statusId').val(statusId);

            $('#payNowModal').modal('show');
        });
    });
</script>
