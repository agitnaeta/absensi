@extends(backpack_view('blank'))

@section('content')
    <h1>Scan Absensi</h1>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <div class="row">
        <h2>Waktu Sekarang : <span id="time" class="text-center"></span></h2>
    </div>
    <div class="row">
        <div class="col-sm-2 offset-2">
            <video id="preview"></video>
        </div>
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
                error:function (e){
                    new Noty({
                        type: "error",
                        text: `${e.message} Error tidak diketahui`,
                    }).show();
                }
            })
        });
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                new Noty({
                    type: "error",
                    text: `Pastikan ada camera & di izinkan`,
                }).show();
            }
        }).catch(function (e) {
            console.error(e);
        });


        function startTime() {
            const today = new Date();
            let h = today.getHours();
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('time').innerHTML =  h + ":" + m + ":" + s;
            setTimeout(startTime, 1000);
        }

        function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }
        startTime()
    </script>
@endsection
