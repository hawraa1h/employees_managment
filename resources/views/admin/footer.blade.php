<!-- Essential javascripts for application to work-->
<script src="{{asset('admin/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('admin/js/popper.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
<script src="{{asset('admin/js/main.js')}}"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="{{asset('admin/js/plugins/pace.min.js')}}"></script>
<!-- Page specific javascripts-->
<script type="text/javascript" src="{{asset('admin/js/plugins/chart.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/confirm.js')}}"></script>
<script type="text/javascript">$('#sampleTable').DataTable();</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('chart')
@stack('js')
<script>
    $('.select2').select2({
        placeholder: "اختر",
        width: '100%'
    });
    function confirmation(actionID, type) {
        event.preventDefault()
            dialog.confirm({
                title: "{{__('Confirm Action')}}",
                message: "{{__('Are You Sure Want To delete?')}}" + type,
                cancel: "لا",
                button: "نعم",
                required: true,
                callback: function(value){
                    if(value) {
                        document.getElementById(actionID).submit();
                    }
                }
            });
    }

    function confirmationLogout(actionID) {
        event.preventDefault()
        dialog.confirm({
            title: "{{__('Confirm Action')}}",
            message: "{{__('هل أنت متأكد من تسجيل الخروج?')}}" ,
            cancel: "لا",
            button: "نعم",
            required: true,
            callback: function(value){
                if(value) {
                    document.getElementById(actionID).submit();
                }
            }
        });
    }
    window.onload = function() {
        var sessionLifetime = {{ \App\Models\Setting::first()->session_period ?? 120 }}; // This will be in minutes from the settings table
        var warningTime = 2; // Time to warn user before session ends

        setTimeout(function() {
            dialog.alert({
                title: "انتهاء الجلسة",
                message: 'سوف تنتهي جلستك خلال ' + warningTime + ' دقائق. يرجى الضغط على "موافق" للمتابعة.',
                button: "موافق",
                required: true,
            });
        }, (sessionLifetime - warningTime) * 60 * 1000);
    };



</script>
