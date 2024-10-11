@include('components.header')

    <div class="flex h-screen">
        <!-----! Navigation Sidebar !---->
        <div class="navbar-container">
           @include('components.navbar')
        </div>


        <!-----! Content Here !---->
        <div class="content-container">
            {{ $slot }}
        </div>
    </div>

@include('components.footer')
