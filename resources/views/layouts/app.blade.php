<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        $Setting = \App\Helpers\Helper::getSetting();
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($Setting) ? $Setting->name : config('app.name', 'Maxobiz') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Multi Select Tag CSS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('public/template/css/style.css') }}" />

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet" />

    <!-- DataTables CSS (Latest version) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" />

    <!-- DataTables Buttons CSS (Latest version) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Tagify CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet" />
</head>

<body>
    <x-application-logo></x-application-logo>
    <x-navigation-bar></x-navigation-bar>
    {{ $slot }}
</body>

<!-- jQuery (Latest version) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<!-- FontAwesome (Latest version) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<!-- Bootstrap Bundle (JS) (Latest version) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS (Latest version) -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons JS (Latest version) -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>

<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>

<!-- PDFMake for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- Button Export JS -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>

<!-- Multi Select Tag JS -->
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>

<!-- Moment.js (Latest version) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Pie Charts -->
{{-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<!-- Tagify JS -->
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<script>
    $(document).ready(function() {

        fetchHours();
        fetchTaskProgress();

        $("#logo").click(function() {
            location.reload();
        });
        $('.select2').select2({
            placeholder: "--Select--",
            allowClear: true
        });
        $(document).on("keydown", "form", function(event) {
            return event.key != "Enter";
        });
        
        var input = document.querySelector('#tags');
        new Tagify(input, {
            delimiter: ',', // Separate tags by commas
            //maxTags: 10,    // Optional: Limit the number of tags
            //whitelist: ["SEO", "Marketing", "Development", "Design"], // Optional: Predefined suggestions
        });
    });
</script>
@yield('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: true
    });

    var channel = pusher.subscribe('my-channel');
    var channelTaskCount = pusher.subscribe("top-task");
    var timeProgress = pusher.subscribe("time");

    channel.bind('my-event', function(data) {
        //NotificationsCount
        $.ajax({
            type: "GET",
            url: "{{ route('notificationsCount') }}",
            success: function(data) {
                $('.NotificationsCount').html(data);
            }
        });

    });

    channelTaskCount.bind("top-task-count", function(data) {
        setTimeout(() => {
            getUnReadNotification();
        }, 1000);
    });

    timeProgress.bind("time-progress", function(data) {
        fetchHours();
    });


    setTimeout(() => {
        getUnReadNotification();
    }, 1000);

    setInterval(() => {
        // fetchHours();
    }, 2000);

    var user_id = "{{ auth()->user()->id ?? '' }}";

    function getUnReadNotification() {
        $.ajax({
            url: "{{ route('get.unread.notification') }}",
            type: 'GET',
            data: {
                user_id: user_id,
            },
            dataType: 'json',
            success: function(response) {
                console.log('Unread Notifications:', response.notifications);

                // Count total notifications
                let totalNotifications = response.count;

                // Show count in span
                document.getElementById('totalCountTask').innerText = `(${totalNotifications})`;


                // Clear previous notifications
                $('#show-notification').empty();

                // Check if notifications exist
                if (response.notifications.length > 0) {
                    response.notifications.forEach(function(notification) {

                        let notificationClass = (notification.is_admin || notification.status ===
                            'unread') ? 'notification-unread' : 'notification-read';

                        $('#show-notification').append(
                            `<a class="dropdown-item ${notificationClass}" href="javascript:void(0)">${notification.message}</a> <hr>`
                        );
                    });
                } else {
                    $('#show-notification').append(
                        `<a class="dropdown-item text-muted" href="#">No new notifications</a>`
                    );
                }

                if (totalNotifications > 0) {
                    document.getElementById('notificationDot').style.display = 'block';
                } else {
                    document.getElementById('notificationDot').style.display = 'none';
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching notifications:', error);
            }
        });
    }

    function markAsRead() {
        $.ajax({
            url: "{{ route('mark.as.read') }}",
            type: 'GET',
            data: {
                user_id: user_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    console.log('Notifications marked as read successfully.');

                    // Reset the notification count to zero
                    $('#totalCountTask').text(0);
                    document.getElementById('notificationDot').style.display = 'none';

                    // Clear notification list smoothly
                    $('#show-notification').fadeOut(300, function() {
                        $(this).empty().show();
                    });
                } else {
                    console.warn('Failed to mark notifications as read.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking notifications as read:', xhr.responseText || error);
            }
        });
    }

    function fetchHours() {

        var userId = @json(Auth::id());

        $.ajax({
            url: '{{ route('get.time.progress') }}',
            type: 'GET',
            data: {
                user_id: userId
            },
            dataType: 'json',
            success: function(response) {
                console.log("Success:", response);

                // $('.today-hours').text(response.today_time);

                $('.per_count').text(response.progress_percentage + '%');

                $('.progress-bar')
                    .css('width', response.progress_percentage + '%');
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }

    function fetchTaskProgress() {

        var userId = @json(Auth::id());

        $.ajax({
            url: '{{ route('get.task.progress') }}',
            type: 'GET',
            data: {
                user_id: userId
            },
            dataType: 'json',
            success: function(response) {
                console.log("Success:", response);

                $('.per_count_task').text(response.progress_percentage + '%');

                $('.progress-bar-task')
                    .css('width', response.progress_percentage + '%');
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }
</script>

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     let userId = "{{ Auth::user()->id ?? 0 }}"; // Get logged-in user ID
    //     let storedTimeKey = `todayTotalSeconds_${userId}`; // Unique key for each user
    //     let storedDateKey = `lastUpdatedDate_${userId}`; // Unique date key

    //     let storedTime = localStorage.getItem(storedTimeKey);
    //     let storedDate = localStorage.getItem(storedDateKey);
    //     let currentDate = new Date().toDateString(); // Get today's date as a string

    //     // Agar storedDate purani hai, to time reset kar do
    //     if (storedDate !== currentDate) {
    //         localStorage.setItem(storedTimeKey, "0");
    //         localStorage.setItem(storedDateKey, currentDate);
    //         storedTime = "0"; // Reset stored time
    //     }

    //     let todayTime = storedTime ? parseFloat(storedTime) : 0; // Start from 0 if no stored value
    //     let lastUpdateTime = performance.now();

    //     function updateTodayTime(timestamp) {
    //         let delta = (timestamp - lastUpdateTime) / 1000; // Time difference in seconds
    //         lastUpdateTime = timestamp;

    //         todayTime += delta; // Increase total seconds
    //         localStorage.setItem(storedTimeKey, todayTime); // Update time in localStorage

    //         let totalMinutes = Math.floor(todayTime / 60); // Convert seconds to minutes
    //         let hours = Math.floor(totalMinutes / 60);
    //         let minutes = totalMinutes % 60;

    //         let formattedTime = hours >= 1 ? `${hours} H` :
    //             `${minutes} M`; // Show only H if 1 hour or more, else M

    //         document.getElementById("today-hours").innerText = formattedTime;

    //         requestAnimationFrame(updateTodayTime); // Call function again
    //     }

    //     requestAnimationFrame(updateTodayTime); // Start loop
    // });



    document.addEventListener("DOMContentLoaded", async function() {
        let userId = "{{ Auth::user()->id ?? 0 }}"; // Get logged-in user ID
        let storedTimeKey = `todayTotalSeconds_${userId}`; // Unique key for each user
        let storedDateKey = `lastUpdatedDate_${userId}`; // Unique date key

        let storedTime = localStorage.getItem(storedTimeKey);
        let storedDate = localStorage.getItem(storedDateKey);
        let currentDate = new Date().toDateString(); // Get today's date as a string

        let todayTime = storedTime ? parseFloat(storedTime) : 0; // Default 0 if no stored value
        let lastUpdateTime = performance.now();

        // **1. Backend se Work Time Fetch Karna**
        async function fetchTodayWorkTime() {
            try {
                const response = await fetch("{{ route('get.today.work.time') }}"); // API Call
                const data = await response.json();

                if (data.total_online_time !== undefined) {
                    let backendTime = parseFloat(data.total_online_time); // Backend se time lo

                    // **Agar LocalStorage empty hai ya naya din hai, toh backend time use karo**
                    if (!storedTime || storedDate !== currentDate) {
                        todayTime = backendTime;
                        localStorage.setItem(storedTimeKey, backendTime);
                        localStorage.setItem(storedDateKey, currentDate);
                    }
                }
            } catch (error) {
                console.error("Error fetching work time:", error);
            }
        }

        // **2. Timer Update Function**
        function updateTodayTime(timestamp) {
            let delta = (timestamp - lastUpdateTime) / 1000; // Time difference in seconds
            lastUpdateTime = timestamp;

            todayTime += delta; // Increase total seconds
            localStorage.setItem(storedTimeKey, todayTime); // Update time in localStorage

            let totalMinutes = Math.floor(todayTime / 60); // Convert seconds to minutes
            let hours = Math.floor(totalMinutes / 60);
            let minutes = totalMinutes % 60;

            let formattedTime = hours >= 1 ? `${hours} H` : `${minutes} M`; // Display format

            document.getElementById("today-hours").innerText = formattedTime;

            requestAnimationFrame(updateTodayTime); // Call function again
        }

        // **3. Backend se pehle time fetch karo phir timer start karo**
        await fetchTodayWorkTime();
        requestAnimationFrame(updateTodayTime);

    });
</script>



</html>
