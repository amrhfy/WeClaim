<x-layout>
    @auth

    <main class="main *:font-wgg gap-10">

        <div class="wgg-flex-col gap-2">


            <!-- Claims Form Container -->

            <div>
                <form class="wgg-flex-col gap-6" action="{{ route('claims-new') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                
                    <!-- Date & Map Container -->
                    <div class="form-container-1">
                        
                        <!-- Left Side -->
                        <div class="wgg-flex-col col-span-1 gap-2">

                            <!-- Date Range -->
                            <div class="wgg-flex-row gap-2">
                                
                                <div class="wgg-flex-col gap-2 basis-1/2">
                                    <label class="form-label" for="date-from">From</label>
                                    <input value="{{ old('date_from') }}" class="form-input text-wgg-black-600" type="date" name="date_from" id="">
                                </div>

                                <div class="wgg-flex-col gap-2 basis-1/2">
                                    <label class="form-label" for="date-from">To</label>
                                    <input value="{{ old('date_to') }}" class="form-input text-wgg-black-600" type="date" name="date_to" id="">
                                </div>

                            </div>

                            <!-- Date Range Error -->
                            <div>
                                @error('date_to')
                                <span class="error-text">*{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Toll Section -->
                            <div class="wgg-flex-col gap-2">

                                <!-- Toll Amount -->
                                <div class="wgg-flex-col gap-2">
                                    <label for="toll_amount" class="form-label">Toll Amount</label>
                                    <input value="{{ old('toll_amount') }}" class="form-input" type="number" name="toll_amount" id="toll_amount" step="0.01">
                                </div>

                            </div>

                            <!-- Toll Amount Error -->
                            <div>
                                @error('toll_amount')
                                    <span class="error-text">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <!-- Attachments -->
                            <div class="wgg-flex-col gap-2">
                                <!-- Toll Report Attachment -->
                                <div class="file-input-container basis-1/2">
                                    <input class="hidden" type="file" name="toll_report" id="toll_report">
                                    <label for="toll_report" class="form-label">
                                        <span id="toll_file_label">Toll Report</span>
                                    </label>
                                    <!-- Progress Bar -->
                                    <div id="toll_progress_container" class="progress-container hidden">
                                        <div id="toll_progress_bar" class="progress-bar" style="width: 0%"></div>
                                    </div>
                                </div>

                                <!-- Email Report Attachment -->
                                <div class="file-input-container basis-1/2">
                                    <input class="hidden" type="file" name="email_report" id="email_report">
                                    <label for="email_report" class="form-label">
                                        <span id="email_file_label">Email Approval</span>
                                    </label>
                                    <!-- Progress Bar -->
                                    <div id="email_progress_container" class="progress-container hidden">
                                        <div id="email_progress_bar" class="progress-bar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="wgg-flex-col gap-2">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea class="form-input" name="remarks" id="remarks" cols="30" rows="5">{{ old('remarks') }}</textarea>
                            </div>

                            <div class="form-child-1 wgg-flex-col gap-2">

                            
                            <!-- Location Input Container -->
                            <div class="wgg-flex-col gap-2" id="location-input-container">

                                <div class="info-box wgg-center-content">
                                    <span><strong>Change Order -</strong> Drag the Location</span>
                                </div>

                                <!-- Location 1 -->
                                <div class="wgg-flex-col gap-2" id="location-1">
                                    <label for="location-1" class="form-label cursor-grab">Location 1</label>
                                    <input type="text" name="location-1" id="location-1" class="form-input location-input" placeholder="">
                                </div>

                            </div>

                            <!-- Add Location Button -->
                            <div class="wgg-flex-row gap-2">
                                <a id="add-location-btn" class="btn-blue w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                                    </svg>
                                    Add Location
                                </a>
                                <button type="button" id="remove-location-btn" class="btn-danger w-fit disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-300" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        </div>
                        
                        <!-- Right Side -->
                        <div id="map" class="wgg-flex-col col-span-2 gap-2">

                            <!-- Location Selector -->
                            <div class="wgg-flex-row gap-2">

                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>

    </main>

    @endauth

    @guest
        <script>window.location.href = "{{ route('login') }}";</script>   
    @endguest



</x-layout>


