<x-layout>
    @auth


    @endauth

    @guest
        <!-- Redirectmy user to login page -->
        <script>window.location.href = "{{ route('login') }}";</script>   
    @endguest

</x-layout>


