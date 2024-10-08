@include('components.header')

    <div>
        <!-----! NAVBAR !---->
        <div class="flex py-10 px-10">
           @include('components.navbar')
        </div>


        <!-----! CONTENT !---->
        <div class="flex py-10 px-10">
            {{ $slot }}
        </div>
    </div>

@include('components.footer')
