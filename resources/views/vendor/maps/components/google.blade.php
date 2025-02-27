<div id="{{$mapId}}" class='border border-blue-500 h-96 rounded-xl'></div>

<script>
    let map{{$mapId}} = "";  

    function initMap{{$mapId}}() {
        map{{$mapId}} = new google.maps.Map(document.getElementById("{{$mapId}}"), {
            center: { lat: {{$centerPoint['lat'] ?? $centerPoint[0]}}, lng: {{$centerPoint['long'] ?? $centerPoint[1]}} },
            zoom: {{$zoomLevel}},
            mapTypeId: '{{$mapType}}'
        });

    function addInfoWindow(marker, message) {

        var infoWindow = new google.maps.InfoWindow({
            content: message
        });

        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.open(map{{$mapId}}, marker);
        });
    }

    @if($fitToBounds || $centerToBoundsCenter)
    let bounds = new google.maps.LatLngBounds();
    @endif

    @foreach($markers as $marker)
        var marker{{ $loop->iteration }} = new google.maps.Marker({
            position: {
                lat: {{$marker['lat'] ?? $marker[0]}},
                lng: {{$marker['long'] ?? $marker[1]}}
            },
            map: map{{$mapId}},
            @if(isset($marker['title']))
            title: "{{ $marker['title'] }}",
            @endif
            icon: @if(isset($marker['icon']))"{{ $marker['icon']}}" @else null @endif
        });

        @if(isset($marker['info']))
            addInfoWindow(marker{{ $loop->iteration }}, @json($marker['info']));
        @endif

        @if($fitToBounds || $centerToBoundsCenter)
        bounds.extend({lat: {{$marker['lat'] ?? $marker[0]}},lng: {{$marker['long'] ?? $marker[1]}}});
        @endif

        @if($fitToBounds)
        map{{$mapId}}.fitBounds(bounds);
        @endif        
        @endforeach

        @if($centerToBoundsCenter)
        map{{$mapId}}.setCenter(bounds.getCenter());
        @endif
    }
</script>

<script
    async
    src="https://maps.googleapis.com/maps/api/js?key={{config('maps.google_maps.access_token', null)}}&callback=initMap{{$mapId}}&libraries=&v=3"
></script>
