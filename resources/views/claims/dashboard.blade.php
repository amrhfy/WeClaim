<x-layout>
    @auth
    <main class="flex flex-col gap-4">
        <div class="flex *:font-normal">
            <a class="flex justify-center items-center gap-2 transition-all ease-in-out py-3 px-6 bg-wgg-black-950 text-wgg-white rounded-lg hover:bg-wgg-black-600" href="{{ route('claims-new') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                </svg>
                New Claim
            </a>
        </div>

        <div class="w-full flex flex-col gap-10">

            <div>
                <h1 class="text-2xl font-semibold">Your Claims List</h1>
                <span class="text-red-500">Temporary data going to be dump into table for testing purpose</span>
            </div>

            <div class="*:font-normal flex flex-col gap-4">
                @foreach ($claims as $claim)
                    
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-col gap-3 basis-1/2">
                            <span class="text-base font-semibold font-wgg-black-950">
                                {{ $claim->title }}
                            </span>
                            <span class="text-xs font-wgg-black-600">
                                {{ $claim->description }}
                            </span>
                            <div class="flex gap-2 items-center">
                                <span class="bg-gray-200 font-wgg-white p-2 rounded-lg text-xs">{{ $claim->submitted_at }}</span>
                                <span class="bg-orange-400 text-wgg-white p-2 rounded-lg text-xs">
                                    {{ $claim->claim_company }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <span class="font-semibold text-xl">RM{{ $claim->amount + $claim->toll_amount }}</span>
                            <span class="text-xs">Petrol + Toll</span>
                        </div>

                        <div class="flex justify-center items-center gap-2">
                            
                            <div class="flex *:rounded-lg *:font-semibold *:text-xs *:py-3 *:px-6">
                                @if ($claim->status == 'submitted')
                                    <div class="bg-orange-200 w-2/10 flex flex-col justify-center items-center text-wgg-black">
                                        <span class="text-base">Submitted</span>
                                        <span class="text-xs font-normal">Waiting for Admin</span>
                                    </div>
                                @elseif ($claim->status == 'under_review')
                                    <div class="bg-green-300 w-2/10 flex flex-col justify-center items-center text-wgg-black">
                                        <span class="text-base">Under Review</span>
                                        <span class="text-xs font-normal">Waiting for HR</span>
                                    </div>
                                @elseif ($claim->status == 'approved_hr')
                                    <div class="bg-teal-300 w-2/10 flex flex-col justify-center items-center text-wgg-black">
                                        <span class="text-base">Approved By HR</span>
                                        <span class="text-xs font-normal">Waiting for Finance</span>
                                    </div>
                                @elseif ($claim->status == 'rejected')
                                    <div class="bg-red-400 w-2/10 flex flex-col justify-center items-center text-wgg-black">
                                        <span class="text-base">Rejected</span>
                                        <span class="text-xs font-normal">See Details</span>
                                    </div>
                                @elseif ($claim->status == 'approved_finance')
                                    <div class="bg-green-400 w-2/10 flex flex-col justify-center items-center text-wgg-black">
                                        <span class="text-base">Approved</span>
                                        <span class="text-xs font-normal">Waiting for Payment</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>

                @endforeach
            </div>

        </div>

    </main>

    @endauth

    @guest
        <!-- Redirectmy user to login page -->
        <script>window.location.href = "{{ route('login') }}";</script>   
    @endguest

</x-layout>


