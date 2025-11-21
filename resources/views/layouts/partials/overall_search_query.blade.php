{{-- <style>
  .input-group{
        /* margin-left:170%; */
        }
    .search-barss .form-control {
    font-size: 13px; /* Smaller font size */
    padding: 0.375rem 0.75rem; /* Smaller padding */
    }

    .search-barss .btn {
        padding: 0.375rem 0.75rem; /* Match input padding */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-barss .btn svg {
        width: 16px; /* Size of the icon */
        height: 16px;
    }
</style> --}}

{{-- <form action="{{ route($link) }}" method="GET">
    <div class="row">
        <div class="col-md-12">
            <div class="input-group input-group-sm search-barss">
                <input type="text" class="form-control" placeholder="Search by name..." name="query" value="{{ request()->input('query') }}">
                <button class="btn btn-success" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
            </div>
        </div>
    </div>
</form> --}}
<style>
    .form-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .search-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-container input[type="text"] {
        width: 200px;
        padding: 5px 10px;
        /* border: 1px solid #ccc; */
        border-radius: 4px;
    }

    select#perPage {
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #ebf2f5;
        height: 35px;
    }

    i.bx-search {
        font-size: 18px;
        color: #555;
    }
</style>
<script>
   $route=window.location.pathname;
   console.log($route);
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('.form-container');
        if (form) {
            form.action = route; // Set the action attribute to the current route
        }
    });
</script>
<!-- Right-side buttons container -->
<div style="display: flex; gap: 5px; margin-right:2%;">

    {{-- <form action="{{route($search_link)}}" method="GET" class="search-container">
        <i class='bx bx-search'></i> <!-- Search Icon -->
        <input type="text" placeholder="Search" name="query" value="{{ request()->input('query') }}">
    </form> --}}

    <form  method="GET" class="form-container">
        <!-- Left Side: Search -->
        <div class="search-container">
            <i class='bx bx-search'></i>
            <input
                type="text"
                placeholder="Search"
                name="query"
                value="{{ request()->input('query') }}"
            />
        </div>

        <!-- Right Side: Per Page Dropdown -->
        <select name="perPage" id="perPage" onchange="this.form.submit()">
            <option value="10" {{ request()->input('perPage') == 10 ? 'selected' : '' }}>10</option>
            <option value="50" {{ request()->input('perPage') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request()->input('perPage') == 100 ? 'selected' : '' }}>100</option>
            <option value="500" {{ request()->input('perPage') == 500 ? 'selected' : '' }}>500</option>
            <option value="1000" {{ request()->input('perPage') == 1000 ? 'selected' : '' }}>1000</option>
        </select>
    </form>
    @if(($export_link != 'export.accounts') && ($export_link != 'export.gl-journals') && ($export_link != 'export.receipts'))
    
    <a href="" class="btn btn-sm btn-grey" id="import" title="import data">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b6c3c9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
    </a>
    @endif
   @if(($export_link != 'export.accounts') && ($export_link != 'export.gl-journals') && ($export_link != 'export.receipts'))
        <a href="{{ isset($export_link) ? route($export_link) : '#' }}" class="btn btn-sm btn-grey" id="export" title="export data" >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b6c3c9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="17 8 12 3 7 8"></polyline>
            <line x1="12" y1="3" x2="12" y2="15"></line></svg>
        </a>
     @endif
    @if(($search_link !='order.receipts'))

    <button type="button" class="btn btn-sm btn-grey" id="filterBtn" data-bs-toggle="offcanvas" data-bs-target="#filterModal" aria-controls="filterModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b6c3c9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
        </svg>
    </button>
    @endif

    {{-- @if (($create_link != 'users.create')  &&  ($create_link != 'pending_orders.create')) --}}
    @if(($create_link != 'pending_orders.create') && ($create_link != 'receipts.create'))

        <a href="{{ route($create_link) }}" class="btn btn-outline-primary btn-sm float-right"  data-placement="left">
            <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
        </a>
    @endif

</div>
