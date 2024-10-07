@include('components.header')


    
    <div class="container flex">
        
        <!-----! SIDEBAR FIXED TO LEFT !---->
        <div class="basis-2/12 flex">
           @include('components.sidebar')
        </div>


        <!-----! CONTENT TO RIGHT !---->
        <div class="basis-10/12 flex justify-center items-center py-10 px-10">
            {{ $slot }}
        </div>
    </div>

@include('components.footer')
