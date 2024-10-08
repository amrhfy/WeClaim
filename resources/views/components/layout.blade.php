@include('components.header')


    
    <div>
        
        <!-----! NAVBAR LEFT !---->
        <div>
           @include('components.navbar')
        </div>


        <!-----! CONTENT TO RIGHT !---->
        <div class="py-10 px-10">
            {{ $slot }}
        </div>
    </div>

@include('components.footer')
