<x-layout>
    @auth

    <div class="flex flex-col justify-center items-center gap-10">

        <div>
            <span class="font-normal text-sm text-red-500">Testing Purpose</span>
            <h1 class="font-normal text-wgg-black-950 font-semibold text-3xl ">New Claim</h1>
        </div>


        <div>
            <form class="flex flex-col gap-4" action="{{ route('claims-new') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="py-4 px-6 flex justify-center items-center bg-red-200 rounded-lg text-wgg-black-950">
                    <span class="font-normal text-xs">
                        <strong>Note:</strong><br>
                        This was intent for a testing purpose. Final design will be implemented when all the backend is working fine.<br>
                        > Currently testing for <strong>Mileage</strong> type.
                    </span>
                </div>

                <!-- Date Selector From and To -->
                <div class="flex flex-col gap-4">

                    <div class="flex flex-row gap-2">

                        <!-- Date From Selector -->
                        <div class="flex flex-col gap-2">
                            <label class="font-normal text-m text-wgg-black-950" for="date-from">From</label>
                            <input value="{{ old('date_from') }}" class="py-2 px-4 flex flex-col border border-wgg-border rounded-lg font-normal text-sm text-wgg-black-950 fill-wgg-black-950" type="date" name="date_from" id="">
                        </div>

                        <!-- Date To Selector -->
                        <div class="flex flex-col gap-2">
                            <label class="font-normal text-m text-wgg-black-950" for="date-from">To</label>
                            <input value="{{ old('date_to') }}" class="py-2 px-4 flex flex-col border border-wgg-border rounded-lg font-normal text-sm text-wgg-black-950 fill-wgg-black-950" type="date" name="date_to" id="">
                        </div>

                    </div>

                    <!-- Error Handling -->
                    <div class="flex basis-1/3 flex-col gap-2">
                        @error('date_to')
                        <span class="rounded-lg flex justify-start items-center font-normal text-red-500 text-xs">*{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                

                <!-- Developer Purpose : Note Regarding Company Name  -->
                <div class="py-4 px-6 flex justify-start items-center bg-green-200 rounded-lg text-wgg-black-950">
                    <span class="font-normal text-xs">
                        <strong>Note:</strong><br>
                        Company will be decided on the places that will be selected by staff.<br>
                        > <strong>Example:</strong> Claim will be under WGE if places selected is MHS.
                    </span>
                </div>

                <!-- Claim Places Selector -->
                <div class="flex flex-col gap-2 *:font-normal *:text-wgg-black-950">
                    <label class="text-m" for="claim_company">Branches</label>
                    <select value="{{ old('claim_company') }}" class="py-2 px-4 rounded-lg text-xs" name="claim_company" id="claim_company">
                        <option>Wegrow Global Sdn Bhd</option>
                        <option>Malaysia Heritage Studios</option>
                        <option>Zoo Melaka</option>
                        <option>Zoo Teruntum</option>
                        <option>Pusat Sains Kreativiti Terengganu</option>
                        <option>Silverlake Outlet Mall</option>
                    </select>
                    @error('claim_company')
                    <span class="rounded-lg flex justify-center items-center bg-red-500 py-2 px-4 font-normal text-wgg-white text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Claim 2 Location Selector -->
                <!-- Google MAPS API Usage Here -->

                <div class="flex flex-col gap-2 *:font-normal *:text-wgg-black-950">
                    <div class="flex flex-row gap-2">
                        
                        <!-- Origin Location -->
                        <div class="flex flex-col basis-1/2 gap-2">
                            <label class="text-m" for="from_location">From Location</label>
                            <input value="{{ old('origin') }}" class="text-xs rounded-lg py-2 px-4 border border-wgg-border" type="text" name="origin" id="origin">
                        </div>

                        <!-- Destination Location -->
                        <div class="flex flex-col basis-1/2 gap-2">
                            <label class="text-m" for="from_location">To Location</label>
                            <input value="{{ old('destination') }}" class="text-xs rounded-lg py-2 px-4 border border-wgg-border" type="text" name="destination" id="destination">
                        </div>
                    </div>
                    <div class="flex justify-between gap-2 w-100  *:font-normal *:text-xs">

                        <!-- Distance Calculation Text -->
                        <div class="flex flex-row gap-2 w-full">
                            <div class="flex justify-center items-center h-100 bg-green-200 border border-wgg-border rounded-lg  w-full py-4 px-8 gap-2">
                                <span class="">Total Distance (KM):</span>
                                <span class="font-bold" id="total_distance">N/A</span>
                                <input type="hidden" name="total_distance_input" id="total_distance_input">
                            </div>
                            @error('total_distance_input')
                            <span class="rounded-lg flex justify-center items-center bg-red-500 py-2 px-4 font-normal text-wgg-white text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Remark Area -->
                <div class="flex flex-col gap-2 *:font-normal *:text-wgg-black-950">
                    <label class="text-m" for="remarks">Remarks</label>
                    <textarea value="{{ old('remarks') }}" class="text-xs p-4 rounded-lg border border-wgg-border" name="remarks" id="" cols="10" rows="10"></textarea>
                </div>

                <!-- Toll Area -->
                <div class="flex flex-col gap-2 *:font-normal *:text-wgg-black-950">
                    <label class="text-m" for="toll-amount">Toll Amount</label>

                    <div class="flex flex-row gap-2">

                        <!-- Toll Amount Input -->
                        <input value="{{ old('toll_amount') }}" class="text-xs py-2 px-4 basis-1/3 border border-wgg-border rounded-lg" placeholder="51.25" type="number" name="toll_amount" id="toll_amount" step="0.01">
                        @error('toll_amount')
                        <span class="rounded-lg flex justify-center items-center bg-red-500 py-2 px-4 font-normal text-wgg-white text-xs">{{ $message }}</span>
                        @enderror
                        
                        <!-- Toll Report Attachment -->
                        <div class="transition-all flex flex-col basis-1/3 items-center justify-center border-2 border-dashed border-wgg-border rounded-lg py-2 px-4  hover:border-wgg-black-950 cursor-pointer">
                            <input class="hidden" type="file" name="toll_report" id="toll_report">
                            <label for="toll_report" class="text-center text-xs text-wgg-black-950 cursor-pointer">
                                <span id="toll_file_label">Toll Report</span>
                            </label>
                            <!-- Progress Bar -->
                            <div id="toll_progress_container" class="w-full bg-gray-200 rounded-full h-2.5 mt-2 hidden">
                                <div id="toll_progress_bar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Email Report Attachment -->
                        <div class="transition-all flex flex-col basis-1/3 items-center justify-center border-2 border-dashed border-wgg-border rounded-lg py-2 px-4  hover:border-wgg-black-950 cursor-pointer">
                            <input class="hidden" type="file" name="email_report" id="email_report">
                            <label for="email_report" class="text-center text-xs text-wgg-black-950 cursor-pointer">
                                <span id="email_file_label">Email Approval</span>
                            </label>
                            <!-- Progress Bar -->
                            <div id="email_progress_container" class="w-full bg-gray-200 rounded-full h-2.5 mt-2 hidden">
                                <div id="email_progress_bar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex">
                    <button type="submit" class="transition-all flex flex-row justify-center gap-2 items-center py-3 px-6 font-semibold text-wgg-white text-m bg-wgg-black-950 rounded-lg hover:bg-wgg-black-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z"/>
                        </svg>
                        Submit Claim
                    </button>
                </div>

            </form>
        </div>

    </div>

    @vite([
        'resources/css/app.css',
        'resources/css/app.js',
        'resources/js/form_logic.js'
        ])

    @endauth

    @guest
        <!-- Redirect user to login page -->
        <script>window.location.href = "{{ route('login') }}";</script>   
    @endguest



</x-layout>


