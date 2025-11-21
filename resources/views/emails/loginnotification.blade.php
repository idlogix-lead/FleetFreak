<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Notification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card mt-5">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title">Login Notification</h5>
                    </div>
                    <div class="card-body">
                        <p>Hello, <strong>{{ $user->name }}</strong>,</p>
                        <p>You have successfully logged in.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
