<!-- jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}
<script>
    $(document).ready(function () {
        var tableID='{{ $table_id }}';
        console.log(tableID);
        $(tableID).DataTable({
            info: false,
            ordering: true, // Enable sorting
            paging: false,  // Disable pagination
            searching: false // Disable searching
        });
    });
    $(tableID).DataTable({
    ordering: true,
    // columnDefs: [
    //     { orderable: true, targets: [0, 2, 4] }, // Enable sorting for specific columns
    //     { orderable: false, targets: [1, 3] }   // Disable sorting for these columns
    // ]
});
</script>

