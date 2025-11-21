<div id="loading-cover">
    <div class="spinner"></div>
</div>
<style>
    /* Full-page cover */
    #loading-cover {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        /* display: grid;  */
        /* place-items: center; */
        display: none;
        /* display:grid; */
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    /* Centered spinner */
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Spinner animation */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<script>
    // Show the loader
    function loader() {
        // $('#loading-cover').fadeIn(); // Show the loading cover
        $('#loading-cover').css('display','grid'); // Show the loading cover
    }

    // Hide the loader
    function endloader() {
        // $('#loading-cover').fadeOut(); // Hide the loading cover
        $('#loading-cover').css('display','none'); // Show the loading cover
    }


</script>
