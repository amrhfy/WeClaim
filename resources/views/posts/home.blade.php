<x-layout>


    
    @auth
        <span class="font-semibold text-2xl">Debug Purposes Only - Info</span><br>
        <div class="flex flex-col">
            <span>Status: <span class="text-green-800 font-bold">Logged In</span></span> 
            <span>Department: <span class="text-green-800">{{ auth()->user()->department }}</span></span>
            <span>First Name: <span class="text-green-800">{{ auth()->user()->first_name }}</span></span>
            <span>Second Name: <span class="text-green-800">{{ auth()->user()->second_name }}</span></span>
            <span>Email: <span class="text-green-800">{{ auth()->user()->email }}</span></span>
            <span>Role: <span class="text-green-800">{{ auth()->user()->role }}</span></span>
            <span>ID: <span class="text-green-800">{{ auth()->user()->id }}</span></span>

        </div>
    @endauth

    @guest
        <span class="font-semibold">Developer Info</span><br>
        <span>Status: <span class="text-red-800">Not Logged In</span></span>   
    @endguest

</x-layout>