<!DOCTYPE html>
<html>
<head>
    <title>Print Content</title>
</head>
<body>
    <div>{!! $preview !!}</div>
    <script>
        // Delay printing slightly to ensure the content is fully loaded
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //         // window.close();
        //     }, 500); // Adjust delay as needed
        // };
    </script>
</body>
</html>
