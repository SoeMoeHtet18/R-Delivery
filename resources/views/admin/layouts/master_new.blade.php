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
                    @yield('content')
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
                return;
            }

            data.forEach(function(notification) {
                var html = getNotificationHtml(notification);
                notificationList.append(html);
            });
        }

        function getNotificationHtml(notification) {
            var html = '';
            var hrefUrl = '';

            if (notification.title == 'payment channel confirm' || 
                notification.title == 'payable or not' || 
                notification.title == 'order create by shop' || 
                notification.title == 'collection create by shop' || 
                notification.title == 'order delay by rider' ||
                notification.title == 'order cancel request by rider') {
                var parts = notification.message.split(';');
                var message = parts[0].trim();
                var partTwo = parts[1].split('=');
                var id = partTwo[1].trim();

                if (partTwo[0].trim() == '$order_id') {
                    hrefUrl = "/orders/" + id;
                } else if (partTwo[0].trim() == '$collection_id') {
                    hrefUrl = "/collections/" + id;
                }

                html = '<a class="dropdown-item" href="' + hrefUrl + '">' + message + '</a>';
            } else {
                html = '<a class="dropdown-item" href="#">' + notification.message + '</a>';
            }

            return html;
        }

        function fetchNotifications() {
            $.ajax({
                url: '/get-notifications',
                method: 'GET',
                success: function(data) {
                    populateNotifications(data);
                    if(data.length > 0) {
                       
                        lastNotificationTime = data[data.length - 1].latest_time;
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function checkNewNotifications() {
            $.ajax({
                url: '/get-new-notifications', // Replace with your actual API endpoint
                method: 'GET',
                data: { lastNotificationTime: lastNotificationTime },
                success: function(response) {
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
                var options = {
                    text: getNotificationText(notification),
                    duration: 10000, // 10 seconds
                    close: true,
                    gravity: "top", // Position the notification at the top
                    className: "toastify-notification",
                    // You can customize the appearance with additional CSS classes
                };

                Toastify(options).showToast();
            });
        }

        function getNotificationText(notification) {
            return (notification.title == 'payment channel confirm' || 
                    notification.title == 'payable or not' || 
                    notification.title == 'order create by shop' || 
                    notification.title == 'collection create by shop' || 
                    notification.title == 'order cancel request by rider' || 
                    notification.title == 'order delay by rider') ? 
                    notification.message.split(';')[0].trim() : 
                    notification.message;
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
