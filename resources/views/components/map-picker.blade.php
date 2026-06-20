@props([
    'latitude'  => null,
    'longitude' => null,
])

<div x-data="mapPicker({{ $latitude ?? 'null' }}, {{ $longitude ?? 'null' }})"
     x-init="initMap()">

    {{-- Input Hidden (dikirim ke server) --}}
    <input type="hidden" name="latitude"  x-model="lat">
    <input type="hidden" name="longitude" x-model="lng">

    {{-- Info Koordinat --}}
    <div class="flex items-center gap-4 mb-3">
        <div class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
            <span class="text-gray-400 text-xs block">Latitude</span>
            <span class="font-mono text-gray-700" x-text="lat ?? 'Belum dipilih'"></span>
        </div>
        <div class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
            <span class="text-gray-400 text-xs block">Longitude</span>
            <span class="font-mono text-gray-700" x-text="lng ?? 'Belum dipilih'"></span>
        </div>
        <button type="button" @click="resetPin()"
            class="px-4 py-2.5 bg-red-50 text-red-500 rounded-xl text-sm hover:bg-red-100 transition">
            🗑 Reset
        </button>
    </div>

    {{-- Search Lokasi --}}
    <div class="relative mb-3">
        <input
            type="text"
            id="map-search-input"
            placeholder="🔍 Cari alamat atau nama tempat..."
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
        >
    </div>

    {{-- Peta --}}
    <div id="map-picker"
         class="w-full rounded-2xl border border-gray-200 overflow-hidden"
         style="height: 380px;">
    </div>

    <p class="text-xs text-gray-400 mt-2">
        💡 Klik pada peta untuk menentukan lokasi toko, atau cari menggunakan kotak pencarian di atas.
    </p>

</div>

@once
@push('scripts')
<script>
function mapPicker(initLat, initLng) {
    return {
        lat: initLat,
        lng: initLng,
        map: null,
        marker: null,

        initMap() {
            // Koordinat default: Kudus, Jawa Tengah
            const defaultLat = initLat ?? -6.8047;
            const defaultLng = initLng ?? 110.9213;

            this.map = new google.maps.Map(document.getElementById('map-picker'), {
                center: { lat: defaultLat, lng: defaultLng },
                zoom: initLat ? 16 : 13,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                styles: [
                    { featureType: 'poi', stylers: [{ visibility: 'simplified' }] }
                ]
            });

            // Kalau ada koordinat awal, langsung pasang marker
            if (initLat && initLng) {
                this.placeMarker({ lat: initLat, lng: initLng });
            }

            // Klik peta = pasang marker
            this.map.addListener('click', (e) => {
                this.placeMarker(e.latLng.toJSON());
            });

            // Search box
            const searchInput = document.getElementById('map-search-input');
            const searchBox   = new google.maps.places.SearchBox(searchInput);

            this.map.addListener('bounds_changed', () => {
                searchBox.setBounds(this.map.getBounds());
            });

            searchBox.addListener('places_changed', () => {
                const places = searchBox.getPlaces();
                if (!places.length) return;

                const place = places[0];
                if (!place.geometry?.location) return;

                this.map.setCenter(place.geometry.location);
                this.map.setZoom(17);
                this.placeMarker(place.geometry.location.toJSON());
            });
        },

        placeMarker(position) {
            // Hapus marker lama kalau ada
            if (this.marker) this.marker.setMap(null);

            this.marker = new google.maps.Marker({
                position,
                map: this.map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                }
            });

            // Update koordinat
            this.lat = parseFloat(position.lat.toFixed(8));
            this.lng = parseFloat(position.lng.toFixed(8));

            // Kalau marker di-drag juga update koordinat
            this.marker.addListener('dragend', (e) => {
                this.lat = parseFloat(e.latLng.lat().toFixed(8));
                this.lng = parseFloat(e.latLng.lng().toFixed(8));
            });
        },

        resetPin() {
            if (this.marker) {
                this.marker.setMap(null);
                this.marker = null;
            }
            this.lat = null;
            this.lng = null;
        }
    }
}
</script>
@endpush
@endonce