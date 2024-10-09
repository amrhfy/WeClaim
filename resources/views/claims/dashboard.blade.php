<x-layout>
    @auth
    <main class="main">

        <!-- Existing Claims List -->
        <div class="claims-dashboard-container">

            <!-- Claims Header -->
            <div class="flex flex-col px-4">
                <h1 class="text-2xl font-semibold">Your Claims List</h1>
                <span class="text-red-500">Temporary data going to be dump into table for testing purpose</span>
            </div>

            <!-- Table Container -->
            <div class="flex-col flex gap-4">

                <table class="table-auto">
                    <tr class="claims-dashboard-table-header">
                        <th>Submitted At</th>
                        <th>Company</th>
                        <th>Claim Type</th>
                        <th>Title</th>
                        <th>Petrol (RM)</th>
                        <th>Toll (RM)</th>
                        <th>Status</th>
                        <th> </th>
                    </tr>

                    @foreach ($claims as $claim)

                        <tr class="claims-dashboard-table-row">
                            <!-- format the submitted date nicer -->

                            <th>{{ $claim->submitted_at }}</th>
                            <th>{{ $claim->claim_company }}</th>
                            <th>{{ $claim->claim_type }}</th>
                            <th>{{ $claim->title }}</th>
                            <th>{{ $claim->amount }}</th>
                            <th>{{ $claim->toll_amount }}</th>
                            <th class="flex">
                                <span class="claims-dashboard-status-badge

                                    @if ($claim->status == 'Submitted')
                                        bg-orange-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-1-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M9.283 4.002H7.971L6.072 5.385v1.271l1.834-1.318h.065V12h1.312z"/>
                                        </svg>
                                        Admin
                                    @elseif ($claim->status == 'Approved_Admin')
                                        bg-yellow-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-2-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.646 6.24c0-.691.493-1.306 1.336-1.306.756 0 1.313.492 1.313 1.236 0 .697-.469 1.23-.902 1.705l-2.971 3.293V12h5.344v-1.107H7.268v-.077l1.974-2.22.096-.107c.688-.763 1.287-1.428 1.287-2.43 0-1.266-1.031-2.215-2.613-2.215-1.758 0-2.637 1.19-2.637 2.402v.065h1.271v-.07Z"/>
                                        </svg>
                                        HR
                                    @elseif ($claim->status == 'Approved_HR')
                                        bg-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-3-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8.082.414c.92 0 1.535.54 1.541 1.318.012.791-.615 1.36-1.588 1.354-.861-.006-1.482-.469-1.54-1.066H5.104c.047 1.177 1.05 2.144 2.754 2.144 1.653 0 2.954-.937 2.93-2.396-.023-1.278-1.031-1.846-1.734-1.916v-.07c.597-.1 1.505-.739 1.482-1.876-.03-1.177-1.043-2.074-2.637-2.062-1.675.006-2.59.984-2.625 2.12h1.248c.036-.556.557-1.054 1.348-1.054.785 0 1.348.486 1.348 1.195.006.715-.563 1.237-1.342 1.237h-.838v1.072h.879Z"/>
                                        </svg>
                                        Finance
                                    @elseif ($claim->status == 'Approved_Finance')
                                        bg-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-record-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                        </svg>
                                        Payment
                                    @elseif ($claim->status == 'Done')
                                        bg-green-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                        Done
                                    
                                    @endif
                                </span>  
                                <td>
                                    <a href="{{ route('claims-claim', $claim->claim_id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </th>
                        </tr>

                    @endforeach

                </table>
            </div>

        </div>

    </main>

    @endauth

    @guest
        <!-- Redirect user to login page -->
        <script>window.location.href = "{{ route('login') }}";</script>   
    @endguest

</x-layout>


