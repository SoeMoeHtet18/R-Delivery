<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script>
    $(function() {
        $('#paymentBoxCollapse').click(function() {
            if($('#paymentBoxCollapse .arrow').hasClass('up')) {
                $('#paymentBoxCollapse .arrow').removeClass('up');
            } else {
                $('#paymentBoxCollapse .arrow').addClass('up');
            }
        });
       
        $('#adminToolsCollapse').click(function() {
            if($('#adminToolsCollapse .arrow').hasClass('up')) {
                $('#adminToolsCollapse .arrow').removeClass('up');
            } else {
                $('#adminToolsCollapse .arrow').addClass('up');
            }
        });
    });

    $(function() {
        var pathName = window.location.pathname;

        var endParts = pathName.split('/');
        var endPart = endParts[1];

        // for payments
        if (['shoppayments', 'customer-payments', 'transactions-for-shop'].includes(endPart)) {
            $("#paymentBoxCollapse").attr('aria-expanded', 'true');
            $("#paymentBox.customize-collapse").toggleClass('show');
        }
        //fpr admin tools
        if (['users', 'cities', 'townships', 'itemtypes'].includes(endPart)) {
            $("#adminToolsCollapse").attr('aria-expanded', 'true');
            $("#adminTools.customize-collapse").toggleClass('show');
        }
    })
    
</script>
