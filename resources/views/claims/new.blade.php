<x-layout>
    @auth

    <div class="flex flex-col gap-10">

        <div class="flex flex-col justify-center">
            <span class="font-normal text-sm text-red-500">Testing Purpose</span>
            <h1 class="font-normal text-wgg-black font-semibold text-3xl ">New Claim</h1>
        </div>

        <div class="py-4 px-6 flex justify-center items-center bg-red-200 rounded-lg text-wgg-black">
            <span class="font-normal text-xs">
                <strong>Note:</strong><br>
                This was intent for a testing purpose. Final design will be implemented when all the backend is working fine.<br>
                > Currently testing for <strong>Mileage</strong> type.
            </span>
        </div>


        <div>
            <form class="flex flex-col gap-4" action="">
                
                <!-- Date Selector From and To -->
                <div class="flex flex-row gap-4">
                    <!-- Date From Selector -->
                    <div class="flex flex-col gap-2">
                        <label class="font-normal text-m text-wgg-black" for="date-from">From</label>
                        <input class="py-2 px-4 flex flex-col border border-wgg-border rounded-lg font-normal text-sm text-wgg-black fill-wgg-black" type="date" name="date_from" id="">
                    </div>

                    <!-- Date To Selector -->
                    <div class="flex flex-col gap-2">
                        <label class="font-normal text-m text-wgg-black" for="date-from">To</label>
                        <input class="py-2 px-4 flex flex-col border border-wgg-border rounded-lg font-normal text-sm text-wgg-black fill-wgg-black" type="date" name="date_to" id="">
                    </div>
                </div>
                

                <!-- Developer Purpose : Note Regarding Company Name  -->
                <div class="py-4 px-6 flex justify-start items-center bg-green-200 rounded-lg text-wgg-black">
                    <span class="font-normal text-xs">
                        <strong>Note:</strong><br>
                        Company will be decided on the places that will be selected by staff.<br>
                        > <strong>Example:</strong> Claim will be under WGE if places selected is MHS.
                    </span>
                </div>

                <!-- Claim Places Selector -->
                <div class="flex flex-col gap-2 *:font-normal *:text-wgg-black">
                    <label class="text-m" for="claim-places">Branches</label>
                    <select class="py-2 px-4 rounded-lg text-xs" name="claim_places" id="claim_places">
                        <option value="MHS">Wegrow Global Sdn Bhd</option>
                        <option value="WGE">Malaysia Heritage Studios</option>
                        <option value="WGE">Zoo Melaka</option>
                        <option value="WGE">Zoo Teruntum</option>
                        <option value="WGE">Pusat Sains Kreativiti Terengganu</option>
                        <option value="WGE">Silverlake Outlet Mall</option>
                    </select>
                </div>

                <!-- Claim 2 Location Selector -->
                <div class="flex flex-row gap-2 *:font-normal *:text-wgg-black">
                    <div class="flex flex-col basis-1/2 gap-2">
                        <label class="text-m" for="from_location">From Location</label>
                        <input class="text-xs rounded-lg py-2 px-4 border border-wgg-border" type="text" name="from_locataion" id="">
                    </div>
                    <div class="flex flex-col basis-1/2 gap-2">
                        <label class="text-m" for="from_location">To Location</label>
                        <input class="text-xs rounded-lg py-2 px-4 border border-wgg-border" type="text" name="from_locataion" id="">
                    </div>
                </div>

                <!-- Remark Area -->
                <div class="flex flex-col gap-2 *:font-normal *:text-wgg-black">
                    <label class="text-m" for="remarks">Remarks</label>
                    <textarea class="text-xs p-4 rounded-lg border border-wgg-border" name="remarks" id="" cols="10" rows="10"></textarea>
                </div>

            </form>
        </div>

    </div>

    @endauth

    @guest
        <!-- Redirect user to login page -->
        <script>window.location.href = "{{ route('login') }}";</script>   
    @endguest

</x-layout>


