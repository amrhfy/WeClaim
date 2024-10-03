<nav class="flex flex-col gap-14 items-center justify-start h-screen bg-gray-200 p-8">

    <div class="w-full selection:flex flex-col font-normal text-red-800">
        <span>WeClaims</span>
        <span>Dev Version v0.1</span>
    </div>

    <div class="w-full flex flex-col gap-4 font-normal">
        
        <a href="{{ route('home') }}">Home Page</a>

        <a href="{{ route('login') }}">Login Page</a>

        @auth
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>

        </form>

        <a href="{{ route('claims') }}">Claims</a>
        @endauth

    </div>

</nav>