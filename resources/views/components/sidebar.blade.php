<nav class="flex flex-col gap-14 items-center justify-start h-screen bg-gray-200 p-8">

    <div class="flex flex-col items-center justify-center font-normal font-semibold text-red-800">
        <span>WeClaims</span>
        <span>Dev Version v0.1</span>
    </div>

    <div class="flex flex-col gap-4 items-center justify-center font-normal">

        @php
            $links = [
                ['title' => 'Register', 'link' => 'null'],
                ['title' => 'Login', 'link' => route('login')],
            ];   
        @endphp

        @foreach ($links as $link)
            <span class="transition-all cursor-pointer flex justify-center items-center font-semibold py-2 px-6 w-full bg-gray-700 text-white rounded hover:bg-gray-900">
                <a href="{{ $link['link'] }}">{{ $link['title'] }}</a>
            </span>
        @endforeach
    </div>

</nav>