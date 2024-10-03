@include('components.header')


    
    <div class="container flex">
        
        <!-----! SIDEBAR FIXED TO LEFT !---->
        <div class="basis-1/6">
           @include('components.sidebar')
        </div>


        <!-----! CONTENT TO RIGHT !---->
        <div class="basis-5/6 p-20">
            {{ $slot }}
        </div>
    </div>

@include('components.footer')
