<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha512-pAoMgvsSBQTe8P3og+SAnjILwnti03Kz92V3Mxm0WOtHuA482QeldNM5wEdnKwjOnQ/X11IM6Dn3nbmvOz365g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.2.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.2.0/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    WebFont.load({
        custom: {
            families: ['NotoSanfs'],
            urls: ['fonts/notosanfs.css']
        }
    });

    document.addEventListener("click", function(event) {
        const targetDiv = document.getElementById("pageSidebarCollapse");
        const dropdownItems = document.querySelectorAll('.sidebar-dropdown');
        const clickedElement = event.target;

        // Check if the clicked element is not inside the targetDiv
        try {
            if (!targetDiv.contains(clickedElement) && !dropdownItems.contains(clickedElement)) {
                    $('.page-container').click(function() {
                        if($('#pageSidebarCollapse').hasClass('toggle-menu')) {
                            $('#pageSidebarCollapse').toggleClass('toggle-menu');
                    }
                });
            }
        } catch {}
    });
    
    $(function() {
        $('#pageSidebarCollapseBtn').click(function() {
            $('#pageSidebarCollapse').toggleClass('toggle-menu');
        });
        
      
        $('#paymentBoxCollapse').click(function() {
            if ($('#paymentBoxCollapse .arrow').hasClass('up')) {
                $('#paymentBoxCollapse .arrow').removeClass('up');
            } else {
                $('#paymentBoxCollapse .arrow').addClass('up');
            }
        });

        $('#adminToolsCollapse').click(function() {
            if ($('#adminToolsCollapse .arrow').hasClass('up')) {
                $('#adminToolsCollapse .arrow').removeClass('up');
            } else {
                $('#adminToolsCollapse .arrow').addClass('up');
            }
        });
    });

    //for expanding side-bar
    $(function() {
        var pathName = window.location.pathname;

        var endParts = pathName.split('/');
        var endPart = endParts[1];

        // for payments
        if (['shoppayments', 'customer-payments', 'transactions-for-shop', 'rider-payments'].includes(endPart)) {
            $("#paymentBoxCollapse").attr('aria-expanded', 'true');
            $("#paymentBox.customize-collapse").toggleClass('show');
        }
        //fpr admin tools
        if (['users', 'cities', 'townships', 'itemtypes', 'payment-types', 'delivery-types', 'branches', 'gates'].includes(endPart)) {
            $("#adminToolsCollapse").attr('aria-expanded', 'true');
            $("#adminTools.customize-collapse").toggleClass('show');
        }
    })
</script>