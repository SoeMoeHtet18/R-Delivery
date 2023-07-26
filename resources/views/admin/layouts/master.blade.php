@include('admin.layouts.header')
@include('admin.layouts.topbar')
<div class="clearfix"></div>
<div class="page-container">

    @include('admin.layouts.sidebar')

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-title-box">
                            <h3 class="f_s_30 f_w_700 text-white">@yield('title')</h3>
                            <ol class="page-sub-title-box">
                                <li class="page-sub-title">R-Delivery</li>
                                <li class="page-sub-title">@yield('title')</li>
                                <li class="page-sub-title">@yield('sub-title')</li>
                                @yield('more-sub-title')
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="content-card" class="card content-card">
                            <div id="content-body" class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="scroll-to-top"
     style="display: none;">
    <i class="fa fa-arrow-up"></i>
</div>
@include('admin.layouts.loading')
@include('admin.layouts.script')
@if (session('error'))
    <script>
         Toastify({
            text: "{!! session('error') !!}",
            gravity: "top",
            position: "center",
            backgroundColor: "red",
            duration: 3000,
        }).showToast();
    </script>
@endif
<script type="text/javascript">
    $(document).ready(function() {
        var lastNotificationTime = null;
        function populateNotifications(data) {
            var notificationList = $('#notificationList');
            notificationList.empty();

            if (data.length === 0) {
                notificationList.append('<a class="dropdown-item" href="#">No notifications</a>');
            } else {
                console.log(data)
                data.forEach(function(notification) {
                    html = '<a class="dropdown-item" href="#">' + notification.message + '</a>';
                    if(notification.title == 'payment channel confirm'){
                        var parts = notification.message.split(';');
                        var message = parts[0].trim();
                        var part_two = notification.message.split('=');
                        var orderId = part_two[1].trim();
                        var hrefUrl = "/orders/" + orderId;
                        html = '<a class="dropdown-item" href="' + hrefUrl + '">' + message + '</a>';
                    }
                    notificationList.append(html);
                });
                
            }
        }

        function fetchNotifications() {
            $.ajax({
                url: '/get-notifications',
                method: 'GET',
                success: function(data) {
                    populateNotifications(data);
                    console.log(data.length);
                    if(data.length > 0) {
                        console.log('true');
                        console.log('fecth' + data[data.length - 1].latest_time);
                        lastNotificationTime = data[data.length - 1].latest_time;
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function checkNewNotifications() {
            console.log('check-new-noti');
            console.log(lastNotificationTime);
            $.ajax({
                url: '/get-new-notifications', // Replace with your actual API endpoint
                method: 'GET',
                data: { lastNotificationTime: lastNotificationTime },
                success: function(response) {
                    console.log('checknewnoti' + response.length)
                    if (response.length > 0) {
                        showNotification(response);
                        lastNotificationTime = response[response.length - 1].latest_time; // Update last notification time
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        // Function to show the pop-up notification
        function showNotification(notifications) {
            notifications.forEach(function(notification) {
                // Display the notification message using Toastify
                if(notification.title == 'payment channel confirm'){
                    var parts = notification.message.split(';');
                    var message = parts[0].trim();
                    Toastify({
                        text: message,
                        duration: 10000, // 10 seconds
                        close: true,
                        gravity: "top", // Position the notification at the top
                        className: "toastify-notification",
                        // You can customize the appearance with additional CSS classes
                    }).showToast();
                }else {
                    Toastify({
                        text: notification.message,
                        duration: 10000, // 10 seconds
                        close: true,
                        gravity: "top", // Position the notification at the top
                        className: "toastify-notification",
                        // You can customize the appearance with additional CSS classes
                    }).showToast();
                }
                
            });
        }

        fetchNotifications();
        checkNewNotifications();
        setInterval(fetchNotifications, 10000); // Fetch api every 10 seconds
        setInterval(checkNewNotifications, 5000); // Fetch api every 5 seconds
    });
</script>
@yield('javascript')

</body>
</html>
