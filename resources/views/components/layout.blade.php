@include('components.header')


    
    <div class="container flex">
        
        <!-----! SIDEBAR FIXED TO LEFT !---->
        <div class="relative basis-2/6">
           @include('components.sidebar')
        </div>


        <!-----! CONTENT TO RIGHT !---->
        <div class="flex justify-start items-start basis-5/6 py-10">
            {{ $slot }}
        </div>
    </div>

@include('components.footer')
