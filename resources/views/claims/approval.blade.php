<x-layout>
    @auth

    @if (Auth::user()->role != 'Staff')
    
    <main class="main">

        <!-- Existing Claims List -->
        <div class="claims-approval-container">


            <!-- Claims Header -->
                <div class="flex flex-col px-4">
                    <h1 class="text-2xl font-semibold">Welcome Back, Shida!</h1>
                    <span class="text-red-500">Temporary data going to be dump into table for testing purpose</span>
                </div>

                <!-- Table Container -->
                <div class="flex-col flex gap-4">

                    <table class="table-auto">
                        <tr class="claims-approval-table-header">
                            <th>Submitted At</th>
                            <th>Company</th>
                            <th>Claim Type</th>
                            <th>Title</th>
                            <th>Petrol (RM)</th>
                            <th>Toll (RM)</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th> </th>
                        </tr>

                            @foreach ($claims as $claim)

                                <!----------------------------------->
                                <!------ Admin Only View Section ---->
                                <!----------------------------------->
                                {{-- @if ($claim->status == 'Submitted' && auth()->user()->role == 'Admin') --}}
                                @if ($claim->status == 'Submitted' && auth()->user()->role == 'SU')

                                    <tr class="claims-approval-table-row">
                                        
                                        <th>{{ $claim->submitted_at }}</th>
                                        <th>{{ $claim->claim_company }}</th>
                                        <th>{{ $claim->claim_type }}</th>
                                        <th>{{ $claim->title }}</th>
                                        <th>{{ $claim->amount }}</th>
                                        <th>{{ $claim->toll_amount }}</th>
                                        <th>
                                            <span class="claims-approval-status-badge-admin">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M9.283 4.002H7.971L6.072 5.385v1.271l1.834-1.318h.065V12h1.312z"/>
                                                </svg>
                                                Admin
                                            </span>
                                        </th>
                                        <th>
                                            <a class="inline-flex claims-approval-action-button" href="{{ route('claims-approval-review', $claim->claim_id) }}">
                                                Review
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                                </svg>
                                            </a>
                                        </th>

                                    </tr>
                                
                                <!----------------------------------->
                                <!------- HR Only View Section ------>
                                <!----------------------------------->
                                @elseif ($claim->status == 'Approved_Admin' && auth()->user()->role == 'HR')

                                <!----------------------------------->
                                <!-- Finance 1st Only View Section -->
                                <!----------------------------------->
                                @elseif (($claim->status == 'Approved_HR' || $claim->status == 'Done') && auth()->user()->role == 'Finance')

                                @endif

                            @endforeach

                    </table>
                </div>
        </div>

    </main>

    @elseif (Auth::user()->role == 'Staff')
        <!-- Redirect user to login page -->
        <script>window.location.href = "{{ route('login') }}";</script>  

    @endif

    @endauth

    @guest
        <!-- Redirect user to login page -->
        <script>window.location.href = "{{ route('login') }}";</script>   
    @endguest

</x-layout>


