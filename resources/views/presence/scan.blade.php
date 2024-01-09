@extends(backpack_view('blank'))

@section('content')
    <h3>Scan Absensi</h3>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <div class="row">
        <video id="preview"></video>
    </div>
    <script type="text/javascript">
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            jQuery.ajax({
                url:'{{route('presence.record')}}',
                method:'post',
                data:{
                  qr:content
                },
                success: function (params) {
                    new Noty({
                        type: "success",
                        text: 'Absen Tersimpan',
                    }).show();
                },
                error:function (){

                }
            })
        });
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    </script>
@endsection
