<x-layout>
    
    <main class="main">
        
        <div class="flex flex-row gap-10 *:bg-wgg-white *:border *:border-wgg-border *:drop-shadow-lg *:rounded-lg *:font-wgg">

            <!-- Claim Basic Details -->
            <div class="flex flex-col">
                <div class="flex flex-col gap-2 py-6 px-6">
                    <div class="flex">
                        <span class="py-1 px-3 bg-orange-500 text-wgg-white text-xs rounded-lg">Under Review</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h1 class="font-semibold text-xl">{{ $claim->title }}</h1>
                        <p class="font-normal text-sm">{{ $claim->description}}</p>
                    </div>
                </div>

                <div>
                    <table>
                        <tr class="claims-dashboard-table-header *:px-6">
                            <th>Submitted At</th>
                            <th>Date From</th>
                            <th>Date To</th>
                        </tr>
                        <tr class="claims-dashboard-table-row *:px-6">
                            <th>{{ $claim->submitted_at }}</th>
                            <th>{{ $claim->date_from }}</th>
                            <th>{{ $claim->date_to }}</th>
                        </tr>
                    </table>
                </div>
            </div>


            <!-- Toll Details -->
            <div class="flex flex-col gap-2">
                @php
                    $document = App\Models\ClaimDocument::where('claim_id', $claim->claim_id)->first();
                @endphp

                @if ($document)
                    @if (pathinfo($document->toll_file_path, PATHINFO_EXTENSION) == 'pdf')
                        <embed src="{{ asset($document->toll_file_path) }}" type="application/pdf" width="100%" height="600px"/>
                    @else
                        <img src="{{ asset($document->toll_file_path) }}" alt="Toll Document" max-width="100%" height="auto">
                    @endif
                @else
                    <p>No toll document available for this claim.</p>
                @endif
            </div>

        </div>

    </main>

</x-layout>
