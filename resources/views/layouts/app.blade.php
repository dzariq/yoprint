<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        <!-- Include Bootstrap 3 CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    </head>
    <body>
        <header>
            <!-- Navigation menu or site header content -->
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/">Yoprint</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="navbar">
                        <ul class="nav navbar-nav">
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <main class="container">
            <!-- Content from child views will be placed here -->
            @yield('content')
        </main>

        <footer class="text-center">
            <!-- Footer content -->
            &copy; {{ date('Y') }} Yoprint!
        </footer>

        <!-- Include Bootstrap 3 JavaScript -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script>
    function refreshAjaxContent() {
        $.ajax({
            url: 'update_table', // Replace with your AJAX endpoint
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log(response)
                $('#ajax-content').html(response);
            },
            error: function (error) {
                console.error('AJAX request failed:', error);
            }
        });
    }

    // Call the refreshAjaxContent function to load initial data
    refreshAjaxContent();

    // Optionally, set a timer to periodically refresh the content
    setInterval(refreshAjaxContent, 5000); // Refresh every 5 seconds (adjust as needed)
        </script>
    </body>
</html>
