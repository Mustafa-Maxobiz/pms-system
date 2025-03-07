<nav class="navbar navbar-expand-lg navbar-light bg-white topbar mb-4 static-top shadow justify-content-between">
    <div class="container mx-3 col-lg-8">
        <div class="d-flex align-items-center flex-wrap">

            @php
                $logo = \App\Models\Setting::first();
            @endphp

            <!-- Left-Aligned: Logo & Total Hours -->
            <a class="navbar-brand nav-logo" id="logo" href="{{ route('dashboard') }}">
                {{-- <img class="img-fluid" src="{{ asset('public/template/Images/Group 149.png') }}" alt="Logo" /> --}}
                <img class="img-fluid" src="{{ asset('storage/app/public/' . $logo->logo) }}" alt="Logo">
            </a>

            <div class="working-hours me-3">
                <p class="mb-2">Total Hours</p>
                <h2 id="timerDisplay" class="mb-0">00:00:00</h2>
                <input type="hidden" id="totalTimeDisplay">
                <a href="{{ url('chat') }}" class="btn btn-info btn-sm">Chat (<i class="fa fa-bell"></i> <span
                        class="NotificationsCount">{{ \App\Helpers\Helper::unSeenCount() }}</span>)</a>
            </div>

            <!-- Notifications Icon -->
            <div class="d-flex mx-2" id="top-notification">

                <a class="nav-link p-2" href="javascript:void(0)" id="notificationLink">

                    <span id="notificationDot" class="notification-dot" style="display: none;"></span>

                    <i class="far fa-bell notify-bell-icon">
                    </i>
                    <span id="totalCountTask">(0)</span>

                </a>

                <div class="dropdown-menu notification-menu">
                    <p class="dropdown-header">Notifications</p>
                    <div id="show-notification">
                        <!-- Notifications will be loaded here -->
                    </div>
                    <div class="dropdown-footer text-center">
                        <button class="btn btn-sm btn-info mark-as-read" onclick="markAsRead()">Mark as Read</button>
                    </div>
                </div>

                {{-- <a class="nav-link p-2" href="#notes" data-bs-toggle="modal">
                    <img src="{{ asset('public/template/Images/Group 1000002792.svg') }}" alt="Task"
                        class="img-fluid" />
                </a> --}}
            </div>


        </div>


        <div class="progress-container">

            <div class="row">

                <div class="col-md-6">
                    <label>
                        <p style="font-size: 12px;">Today Time: <strong class="today-hours" id="today-hours">0
                                M</strong></p>
                    </label>

                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                            aria-valuemin="0" aria-valuemax="100">
                            <p class="per_count">0%</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">

                    @can('View Progress')
                        <label>
                            <p style="font-size: 12px;">Task <strong>%</strong></p>
                        </label>
                        <div class="progress-task">
                            <div class="progress-bar-task" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                aria-valuemin="0" aria-valuemax="100">
                                <p class="per_count_task">0%</p>
                            </div>
                        </div>
                    @endcan

                </div>

            </div>

        </div>



    </div>
    <!-- Right-Aligned: Notifications, Status, and Profile -->
    <div>
        <ul class="navbar-nav ms-auto align-items-center d-flex flex-row mb-3">
            <!-- Status Dropdown -->
            <x-user-status :statuses="\App\Helpers\Helper::getUserStatuses()" :userStatusID="\App\Helpers\Helper::getLastStatusID()" :userStatusValue="\App\Helpers\Helper::getLastStatusValue()" />
            <!-- Profile Dropdown -->
            <li class="nav-item dropdown no-arrow mx-3 profile-dropdown">
                <a class="nav-link" href="" id="userDropdown-admin" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img class="rounded-circle" style="width: 55px; height: 55px;"
                        src="{{ asset('storage/app/public/' . (Auth::user()->profile_picture ?? '')) }}" alt="Profile"
                        onerror="this.onerror=null;this.src='{{ asset('public/no-image.png') }}';" />
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in">
                    <li class="dropdown-item d-flex align-items-center">
                        <img class="rounded-circle me-3" style="width: 51px; height: 51px;"
                            src="{{ asset('storage/app/public/' . (Auth::user()->profile_picture ?? '')) }}"
                            alt="Profile" onerror="this.onerror=null;this.src='{{ asset('public/no-image.png') }}';" />
                        <div class="profile">
                            <h3>{{ Auth::user()->name }}</h3>
                            <p>{{ Auth::user()->roles->pluck('name')->implode(', ') }}</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <x-responsive-nav-link :href="route('profile.edit')" class="dropdown-item">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> {{ __('Profile') }}
                        </x-responsive-nav-link>
                    </li>

                    @can('View My Report')
                        <li class="dropdown-item d-flex align-items-center">
                            <a href="{{ route('reports.see.my.report') }}">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> See My Report
                            </a>
                        </li>
                    @endcan


                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                class="dropdown-item">
                                <i class="fas fa-power-off fa-sm fa-fw mr-2 text-gray-400"></i> {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>


@if (Auth::check())
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let totalSeconds = 0;
            let lastUpdateTime = Date.now();
            let timerRunning = false;
            let timerInterval;
            const timerDisplay = document.getElementById("timerDisplay");
            const totalTimeDisplay = document.getElementById("totalTimeDisplay");

            const userId = "{{ Auth::id() }}";
            const storageKey = `work_timer_${userId}`;
            const lastDateKey = `work_timer_date_${userId}`;
            const today = new Date().toISOString().split('T')[0];

            // **Time Formatting Function**
            function formatTime(seconds) {
                const hours = Math.floor(seconds / 3600).toString().padStart(2, '0');
                const minutes = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
                const sec = (seconds % 60).toString().padStart(2, '0');
                return `${hours}:${minutes}:${sec}`;
            }

            // **Timer ko Update Karna (requestAnimationFrame)**
            function updateTimer() {
                if (!timerRunning) return;

                const now = Date.now();
                const elapsedSeconds = Math.floor((now - lastUpdateTime) / 1000);

                if (elapsedSeconds > 0) {
                    totalSeconds += elapsedSeconds;
                    lastUpdateTime = now;

                    timerDisplay.textContent = formatTime(totalSeconds);
                    totalTimeDisplay.value = totalSeconds;

                    // **LocalStorage me Save Karna**
                    localStorage.setItem(storageKey, totalSeconds);
                }

                requestAnimationFrame(updateTimer);
            }

            // **Backend se Time Fetch Karna**
            async function fetchTotalWorkTime() {
                try {
                    const response = await fetch("{{ route('get.today.work.time') }}");
                    const data = await response.json();

                    if (data.total_online_time !== undefined) {
                        let backendTime = data.total_online_time;

                        console.log("Fetched Time from Backend:", backendTime);

                        // **Agar LocalStorage empty ho ya naya din ho toh backend time lo**
                        if (!localStorage.getItem(storageKey) || localStorage.getItem(lastDateKey) !== today) {
                            totalSeconds = backendTime;
                            localStorage.setItem(storageKey, backendTime);
                            localStorage.setItem(lastDateKey, today);
                        }
                    }
                } catch (error) {
                    console.error("Error fetching work time:", error);
                }
            }

            // **Initial Timer Setup**
            async function startTimer() {
                await fetchTotalWorkTime(); // **Pehle Backend Time Lo**

                // **Agar LocalStorage me time hai aur date same hai toh use karo**
                if (localStorage.getItem(storageKey) && localStorage.getItem(lastDateKey) === today) {
                    totalSeconds = parseInt(localStorage.getItem(storageKey), 10);
                }

                console.log("Starting Timer from:", totalSeconds);

                // **UI Update**
                timerDisplay.textContent = formatTime(totalSeconds);
                totalTimeDisplay.value = totalSeconds;

                // **Timer Start**
                if (!timerRunning) {
                    timerRunning = true;
                    lastUpdateTime = Date.now();
                    requestAnimationFrame(updateTimer);
                }
            }

            startTimer();
        });
    </script>
@endif









{{-- @if (Auth::check())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let totalSeconds = 0;
            let timerInterval;
            const timerDisplay = document.getElementById("timerDisplay");
            const totalTimeDisplay = document.getElementById("totalTimeDisplay");

            // **Time Format Function**
            function formatTime(seconds) {
                const hours = Math.floor(seconds / 3600).toString().padStart(2, '0');
                const minutes = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
                const sec = (seconds % 60).toString().padStart(2, '0');
                return `${hours}:${minutes}:${sec}`;
            }

            // **Timer Update Function**
            function updateTimer() {
                totalSeconds++; // **Increment Timer**
                timerDisplay.textContent = formatTime(totalSeconds);
                totalTimeDisplay.value = totalSeconds;
            }

            // **Backend se Total Work Time Fetch Karna**
            async function fetchTotalWorkTime() {
                try {
                    const response = await fetch("{{ route('get.today.work.time') }}");
                    const data = await response.json();

                    if (data.total_online_time !== undefined) {
                        totalSeconds = data.total_online_time;
                        console.log("Fetched Time from Backend:", totalSeconds);

                        // **Timer ko backend time se update karein**
                        timerDisplay.textContent = formatTime(totalSeconds);
                        totalTimeDisplay.value = totalSeconds;
                    }
                } catch (error) {
                    console.error("Error fetching work time:", error);
                }
            }

            // **Initial Timer Setup**
            async function startTimer() {
                await fetchTotalWorkTime(); // **Ensure Time is Loaded Before Starting Timer**

                if (!timerInterval) {
                    console.log("Starting Timer from:", totalSeconds);
                    timerInterval = setInterval(updateTimer, 1000); // **Every 1 sec**
                }

                // setInterval(fetchTotalWorkTime, 30000);
            }

            startTimer();
        });
    </script>
@endif --}}




{{-- @if (Auth::check())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let interval;
            let totalSeconds = 0;
            const timerDisplay = document.getElementById("timerDisplay");
            const totalTimeDisplay = document.getElementById("totalTimeDisplay");

            const userId = "{{ Auth::user()->id }}"; // Get the logged-in user's ID
            const storageKey = `userTimer_${userId}`; // Unique storage key per user
            const dateKey = `timerDate_${userId}`;
            let lastUpdateTime = 0;
            let accumulatedTime = 0;

            function formatTime(seconds) {
                const hours = Math.floor(seconds / 3600).toString().padStart(2, '0');
                const minutes = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
                const sec = (seconds % 60).toString().padStart(2, '0');
                return `${hours}:${minutes}:${sec}`;
            }

            function updateTimer() {
                const now = performance.now();
                const deltaTime = (now - lastUpdateTime) / 1000; // Time in seconds
                accumulatedTime += deltaTime;
                totalSeconds = Math.floor(accumulatedTime); // Accumulated time in whole seconds
                timerDisplay.textContent = formatTime(totalSeconds);
                totalTimeDisplay.value = formatTime(totalSeconds);
                localStorage.setItem(storageKey, totalSeconds);
                lastUpdateTime = now;
                requestAnimationFrame(updateTimer);
            }

            function startTimer() {
                const today = new Date().toISOString().split('T')[0];
                const savedDate = localStorage.getItem(dateKey);
                let savedTime = parseInt(localStorage.getItem(storageKey), 10);

                // If it's a new day, reset the timer
                if (savedDate !== today) {
                    totalSeconds = 0;
                    accumulatedTime = 0;
                    localStorage.setItem(storageKey, totalSeconds);
                    localStorage.setItem(dateKey, today);
                } else if (!isNaN(savedTime)) {
                    totalSeconds = savedTime;
                    accumulatedTime = savedTime;
                }

                timerDisplay.textContent = formatTime(totalSeconds);
                totalTimeDisplay.value = totalSeconds;

                lastUpdateTime = performance.now();
                requestAnimationFrame(updateTimer);
            }

            startTimer();

        });
    </script>
@endif --}}





{{-- @if (Auth::check())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let interval;
            let totalSeconds = 0;
            const timerDisplay = document.getElementById("timerDisplay");
            const totalTimeDisplay = document.getElementById("totalTimeDisplay");

            const storageKey = "userTimer";
            const dateKey = "timerDate";
            let lastUpdateTime = 0;
            let accumulatedTime = 0;

            function formatTime(seconds) {
                const hours = Math.floor(seconds / 3600).toString().padStart(2, '0');
                const minutes = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
                const sec = (seconds % 60).toString().padStart(2, '0');
                return `${hours}:${minutes}:${sec}`;
            }

            function updateTimer() {
                const now = performance.now();
                const deltaTime = (now - lastUpdateTime) / 1000; // Time in seconds
                accumulatedTime += deltaTime;
                totalSeconds = Math.floor(accumulatedTime); // Accumulated time in whole seconds
                timerDisplay.textContent = formatTime(totalSeconds);
                totalTimeDisplay.value = formatTime(totalSeconds);
                localStorage.setItem(storageKey, totalSeconds);
                lastUpdateTime = now;
                requestAnimationFrame(updateTimer);
            }

            function startTimer() {
                const today = new Date().toISOString().split('T')[0];
                const savedDate = localStorage.getItem(dateKey);
                let savedTime = parseInt(localStorage.getItem(storageKey), 10);

                // If it's a new day, reset the timer
                if (savedDate !== today) {
                    totalSeconds = 0;
                    accumulatedTime = 0;
                    localStorage.setItem(storageKey, totalSeconds);
                    localStorage.setItem(dateKey, today);
                } else if (!isNaN(savedTime)) {
                    totalSeconds = savedTime;
                    accumulatedTime = savedTime;
                }

                timerDisplay.textContent = formatTime(totalSeconds);
                totalTimeDisplay.value = totalSeconds;

                lastUpdateTime = performance.now();
                requestAnimationFrame(updateTimer);
            }

            startTimer();
        });
    </script>
@endif --}}
