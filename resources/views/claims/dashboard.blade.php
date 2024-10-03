<x-layout>
    @auth

    <span class="group transition-all ease-in-out flex flex-row gap-1 justify-center items-center hover:translate-x-1 hover:text-wgg-gray hover:"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
      </svg>
      <a class="font-normal font-semibold text-wgg-black group-hover:text-wgg-gray" href="{{ route('claims-new') }}">New Claim</a></span>

    @endauth

    @guest
        <!-- Redirectmy user to login page -->
        <script>window.location.href = "{{ route('login') }}";</script>   
    @endguest

</x-layout>


