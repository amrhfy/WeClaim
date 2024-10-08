<div class="h-auto w-full flex flex-row items-center justify-center bg-gray-200 py-5 px-10 *:font-normal">

    <div class="w-full flex flex-row justify-around gap-4">
        
        <span class="text-red-700" >Dev v1.0</span>

        <a href="{{ route('home') }}">Home Page</a>

        <a href="{{ route('login') }}">Login Page</a>

        @auth
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>

        </form>

        <a href="{{ route('claims-dashboard') }}">Claims</a>
        @endauth

    </div>

</div>