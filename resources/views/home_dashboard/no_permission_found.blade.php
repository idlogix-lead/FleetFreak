<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Permission</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="text-center">
            <h1 class="display-3 text-danger">403</h1>
            <h2 class="mb-3">No Permission Found</h2>
            <p class="text-muted">You do not have permission to access this page.</p>
            {{-- <a href="{{ url('/') }}" class="btn btn-primary mt-3">Go to Dashboard</a> --}}
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
