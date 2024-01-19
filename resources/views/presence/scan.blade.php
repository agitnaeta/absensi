@extends(backpack_view('blank'))

@section('content')
    <style>
        #map { height: 180px; }
    </style>
    <div class="text-right">
        <a href="/admin" class="text-right btn-default btn btn-sm">
            <i class="la la-sign-in"></i>    Saya admin
        </a>
    </div>
    <h1>Scan Absensi</h1>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <div class="row">
     <div class="col">

         <h2>Waktu Sekarang : <span id="time" class="text-center"></span></h2>

         <div class="alert">
             <ul style="list-style-type: none">
                 <li><i class="la la-camera"></i> Pastikan Akses Kamera di Izinkan</li>
                 <li><i class="la la-map-marker"></i> Pastikan Akses Kamera di Izinkan</li>
                 <li><i class="la la-music"></i> Pastikan Scan Sampai Berbunyi</li>
             </ul>
         </div>
     </div>
    </div>
    <div class="row">
        {{@csrf_field()}}
        <div class="col text-center">
            <video id="preview"></video>
            <audio style="display: none" id="audioPlayer" controls>
                <source src="{{asset('/sound/login.mp3')}}" type="audio/mp3">
                Your browser does not support the audio element.
            </audio>
            <audio style="display: none" id="audioPlayerFailed" controls>
                <source src="{{asset('/sound/failed.mp3')}}" type="audio/mp3">
                Your browser does not support the audio element.
            </audio>
        </div>


    </div>

    <script type="text/javascript">
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            let lat,lng;
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((location)=>{
                    lat = location.coords.latitude
                    lng = location.coords.longitude
                    jQuery.ajax({
                        url:'{{route('presence.record')}}',
                        method:'post',
                        headers:{
                            'X-Csrf-Token' : '{{@csrf_token()}}'
                        },
                        data:{
                            qr:content,
                            lat: lat,
                            lng: lng
                        },
                        success: function (params) {
                            play()
                            new Noty({
                                type: "success",
                                text: 'Absen Tersimpan',
                            }).show();
                        },
                        error:function (e){
                            playFailed()
                            new Noty({
                                type: "error",
                                text: `Qr Tidak dikenali`,
                            }).show();
                        }
                    })
                },
                    (error)=>{
                    playFailed()
                    new Noty({
                        type: "error",
                        text: `<i class='la la-map-marker'></i> Lokasi Diperlukan`,
                    }).show();
                },{enableHighAccuracy:true})
            }


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


        function play(){
            var audioPlayer = document.getElementById('audioPlayer');

            // Check if the audio is paused or ended
            if (audioPlayer.paused || audioPlayer.ended) {
                audioPlayer.play();
            } else {
                audioPlayer.pause();
            }
        }
        function playFailed(){
            var audioPlayer = document.getElementById('audioPlayerFailed');

            // Check if the audio is paused or ended
            if (audioPlayer.paused || audioPlayer.ended) {
                audioPlayer.play();
            } else {
                audioPlayer.pause();
            }
        }

    </script>
@endsection
