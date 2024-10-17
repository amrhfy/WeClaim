@php
use App\Services\ClaimService;
use App\Models\Claim;
@endphp


<x-layout>
    <div class="wgg-box-border-shadow p-6">
        <div class="flex flex-col px-4 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Claims Approval</h1>
            <span class="text-red-500 text-sm italic">Temporary data going to be dump into table for testing purpose</span>
        </div>

        <div class="flex justify-between items-center mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 w-full">
                <div class="bg-white space-y-2 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600">Total Claims to Review</p>
                    <p class="text-3xl font-semibold text-gray-300">{{ Claim::count() }}</p>
                </div>
                <div class="bg-white space-y-2 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600">Pending Review</p>
                    <p class="text-3xl font-semibold text-gray-300">{{ Claim::where('status', '!=', Claim::STATUS_DONE)->count() }}</p>
                </div>
                <div class="bg-white space-y-2 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600">Approved Claims</p>
                    <p class="text-3xl font-semibold text-gray-300">{{ Claim::where('status', Claim::STATUS_APPROVED_FINANCE)->count() }}</p>
                </div>
                <div class="bg-white space-y-2 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600">Total Amount to Review</p>
                    <p class="text-3xl font-semibold text-gray-300">RM {{ number_format(Claim::sum('petrol_amount') + Claim::sum('toll_amount'), 2) }}</p>
                </div>
            </div>
        </div>

        <div class="flex-col flex gap-4">
            <div class="overflow-x-auto shadow-md sm:rounded-lg p-4">
                <div class="flex justify-end mb-4">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <select id="sortSelect" onchange="sortTable(this.value)" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                            <option value="">Sort by...</option>
                            <option value="status">Status</option>
                            <option value="submitted_at">Submitted Date</option>
                            <option value="user">Submitted By</option>
                            <option value="title">Title</option>
                            <option value="date_from">Date From</option>
                            <option value="date_to">Date To</option>
                        </select>
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <button type="button" onclick="toggleSortOrder('asc')" id="sortAsc" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                Ascending
                            </button>
                            <button type="button" onclick="toggleSortOrder('desc')" id="sortDesc" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                Descending
                            </button>
                        </div>
                    </div>
                </div>
                <table id="claimsTable" class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-white uppercase bg-gray-700">
                        <!-- ... your existing thead code ... -->
                    </thead>
                    <tbody>
                        @forelse ($claims as $claim)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $claim->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $claim->submitted_at->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $claim->user->first_name . ' ' . $claim->user->second_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $claim->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $claim->date_from->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $claim->date_to->format('d-m-Y') }}</td>
                                <td class="px-6 py-4">
                                    <!-- Status badge code here -->
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!-- Action buttons code here -->
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No claims found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100">
                            <td colspan="8" class="px-6 py-4 text-sm font-medium text-gray-900">
                                <strong>Total Entries:</strong> 
                                @if ($claims instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $claims->total() }}
                                @else
                                    {{ $claims->count() }}
                                @endif
                                / {{ App\Models\Claim::count() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                @if ($claims instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-4">
                        {{ $claims->links() }}
                    </div>
                @endif
            </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('claimsTable');
            const sortSelect = document.getElementById('sortSelect');
            const sortAsc = document.getElementById('sortAsc');
            const sortDesc = document.getElementById('sortDesc');
            let currentSortColumn = '';
            let isAscending = true;

            function sortTable(column, ascending) {
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                const columnIndex = getColumnIndex(column);

                rows.sort((a, b) => {
                    const aValue = a.cells[columnIndex].textContent.trim();
                    const bValue = b.cells[columnIndex].textContent.trim();

                    if (column === 'submitted_at' || column === 'date_from' || column === 'date_to') {
                        return ascending ? new Date(aValue) - new Date(bValue) : new Date(bValue) - new Date(aValue);
                    } else {
                        return ascending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                    }
                });

                const tbody = table.querySelector('tbody');
                rows.forEach(row => tbody.appendChild(row));
            }

            function getColumnIndex(column) {
                switch(column) {
                    case 'status': return 6;
                    case 'submitted_at': return 1;
                    case 'user': return 2;
                    case 'title': return 3;
                    case 'date_from': return 4;
                    case 'date_to': return 5;
                    default: return 0;
                }
            }

            sortSelect.addEventListener('change', function() {
                currentSortColumn = this.value;
                if (currentSortColumn) {
                    sortTable(currentSortColumn, isAscending);
                }
            });

            sortAsc.addEventListener('click', function() {
                isAscending = true;
                if (currentSortColumn) {
                    sortTable(currentSortColumn, isAscending);
                }
            });

            sortDesc.addEventListener('click', function() {
                isAscending = false;
                if (currentSortColumn) {
                    sortTable(currentSortColumn, isAscending);
                }
            });
        });
        </script>
        </div>
    </div>
</x-layout>
