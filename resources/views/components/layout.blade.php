@include('components.header')

    <div class="flex">
        <!-----! Navigation Sidebar !---->
        <div class="navbar-container">
           @include('components.navbar')
        </div>


        <!-----! Content Here !---->
        <div class="content">
            {{ $slot }}
        </div>
    </div>

@include('components.footer')
