<!DOCTYPE html>
<html lang="en">
@include('admin.partials.style')
<!-- Include Toastr CSS from CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<body class=" layout-boxed">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader"> 
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container container-xxl">
        @include('admin.partials.header')
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container " id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('admin.partials.sidebar')
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                @yield('content')

            </div>
        </div>
        <!--  BEGIN FOOTER  -->
        @include('admin.partials.footer')
        <!--  END CONTENT AREA  -->


    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    @include('admin.partials.script')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->



    <!-- Include Toastr JS from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Session-based messages
            @if(session('success'))
            toastr.success("{{ session('success') }}");
            @elseif(session('error'))
            toastr.error("{{ session('error') }}");
            @elseif(session('info'))
            toastr.info("{{ session('info') }}");
            @elseif(session('warning'))
            toastr.warning("{{ session('warning') }}");
            @endif

            // Validation errors
        });
    </script>

</body>

</html>