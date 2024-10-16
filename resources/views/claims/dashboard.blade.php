@php
use App\Models\Claim;
@endphp

<x-layout>
    @auth
    <main class="main">
        @if ($claims->isEmpty())
            <p>No Data</p>
        @else
            <!-- Existing Claims List -->
            <div class="wgg-box-border-shadow">
                
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
                            <th>Remarks</th>
                        </tr>

                        @foreach ($claims as $claim)

                        <tr class="claims-dashboard-table-row" onclick="window.location='{{ route('claims.claim', $claim->id) }}';" style="cursor: pointer;">
                                <!-- format the submitted date nicer -->

                                <th>{{ $claim->submitted_at->format('d-m-Y') }}</th>
                                <th>{{ $claim->claim_company }}</th>
                                <th>{{ $claim->claim_type }}</th>
                                <th>{{ $claim->title }}</th>
                                <th>{{ $claim->petrol_amount }}</th>
                                <th>{{ $claim->toll_amount }}</th>
                                <th class="flex">
                                    <span class="claims-dashboard-status-badge
                                    @if ($claim->status == Claim::STATUS_SUBMITTED)
                                        bg-orange-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-1-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M9.283 4.002H7.971L6.072 5.385v1.271l1.834-1.318h.065V12h1.312z"/>
                                        </svg>
                                        Submitted
                                    @elseif ($claim->status == Claim::STATUS_APPROVED_ADMIN)
                                        bg-yellow-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-2-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.646 6.24c0-.691.493-1.306 1.336-1.306.756 0 1.313.492 1.313 1.236 0 .697-.469 1.23-.902 1.705l-2.971 3.293V12h5.344v-1.107H7.268v-.077l1.974-2.22.096-.107c.688-.763 1.287-1.428 1.287-2.43 0-1.266-1.031-2.215-2.613-2.215-1.758 0-2.637 1.19-2.637 2.402v.065h1.271v-.07Z"/>
                                        </svg>
                                        Admin Approved
                                    @elseif ($claim->status == Claim::STATUS_APPROVED_DATUK)
                                        bg-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-3-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8.082.414c.92 0 1.535.54 1.541 1.318.012.791-.615 1.36-1.588 1.354-.861-.006-1.482-.469-1.54-1.066H5.104c.047 1.177 1.05 2.144 2.754 2.144 1.653 0 2.954-.937 2.93-2.396-.023-1.278-1.031-1.846-1.734-1.916v-.07c.597-.1 1.505-.739 1.482-1.876-.03-1.177-1.043-2.074-2.637-2.062-1.675.006-2.59.984-2.625 2.12h1.248c.036-.556.557-1.054 1.348-1.054.785 0 1.348.486 1.348 1.195.006.715-.563 1.237-1.342 1.237h-.838v1.072h.879Z"/>
                                        </svg>
                                        Datuk Approved
                                    @elseif ($claim->status == Claim::STATUS_APPROVED_HR)
                                        bg-purple-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-4-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM7.519 5.057c-.886 1.418-1.772 2.838-2.542 4.265v1.12H8.85V12h1.26v-1.559h1.007V9.334H10.11V4.002H8.176c-.218.352-.438.703-.657 1.055ZM6.225 9.281v.053H8.85V5.063h-.065c-.867 1.33-1.787 2.806-2.56 4.218Z"/>
                                        </svg>
                                        HR Approved
                                    @elseif ($claim->status == Claim::STATUS_APPROVED_FINANCE)
                                        bg-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-5-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0Zm-8.006 4.158c1.74 0 2.924-1.119 2.924-2.806 0-1.641-1.178-2.584-2.56-2.584-.897 0-1.442.421-1.612.68h-.064l.193-2.344h3.621V4.002H5.791L5.445 8.63h1.149c.193-.358.668-.809 1.435-.809.85 0 1.582.604 1.582 1.57 0 1.085-.779 1.682-1.57 1.682-.697 0-1.389-.31-1.53-1.031H5.276c.065 1.213 1.149 2.115 2.72 2.115Z"/>
                                        </svg>
                                        Finance Approved
                                    @elseif ($claim->status == Claim::STATUS_REJECTED)
                                        bg-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                          </svg>
                                        Rejected
                                    @elseif ($claim->status == Claim::STATUS_DONE)
                                        bg-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                        Payment
                                    @endif
                                    </span>
                                    <td>
                                        <span class="w-fit font-medium underline">
                                            @if ($claim->status == Claim::STATUS_SUBMITTED)
                                                Admin Review
                                            @elseif ($claim->status == Claim::STATUS_APPROVED_ADMIN)
                                                Datuk Approval
                                            @elseif ($claim->status == Claim::STATUS_APPROVED_DATUK)
                                                HR Review
                                            @elseif ($claim->status == Claim::STATUS_APPROVED_HR)
                                                Finance Review
                                            @elseif ($claim->status == Claim::STATUS_APPROVED_FINANCE)
                                                Payment Processing
                                            @elseif ($claim->status == Claim::STATUS_DONE)
                                                Completed
                                            @elseif ($claim->status == Claim::STATUS_REJECTED)
                                                @php
                                                    $latestReview = $claim->reviews()->latest('reviewed_at')->first();
                                                @endphp
                                                Rejected by {{ $latestReview ? $latestReview->reviewer->role->name : 'Unknown' }}
                                            @endif
                                        </span>
                                    </td>
                                </th>
                                
                            </tr>

                        @endforeach

                    </table>
                </div>

            </div>
        @endif
    </main>

    @endauth

    @guest
        <!-- Redirect user to login page -->
        <script>window.location.href = "{{ route('login') }}";</script>   
    @endguest

</x-layout>


