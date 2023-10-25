
var map; // Declare the map variable globally
var marker; // Declare the marker variable globally

// Initialize the map (similar to the previous example)
function initMap() {
    // Create a map
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 37.7749, lng: -122.4194 },
        zoom: 16
    });

    // You can add markers, polylines, and other features here
}

function updateMarkerPosition(lat, lng) {
    if (marker) {
        marker.setPosition(new google.maps.LatLng(lat, lng));
    } else {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: map,
            title: 'Bus Location'
        });
    }

    map.setCenter(new google.maps.LatLng(lat, lng));
}

$(document).ready(function() {
    $('#busSelect').change(function() {
        var busId = $(this).val();
        if (busId) {
            // Perform an AJAX request to fetch the latest coordinates from the server
            $.ajax({
                url: 'get_bus_location.php',
                type: 'POST',
                data: { bus_id: busId },
                success: function(data) {
                    // Parse the response (assuming it's JSON)
                    var coordinates = JSON.parse(data);
                    
                    // Update the marker's position on the map
                    updateMarkerPosition(coordinates.lat, coordinates.lng);
                },
                error: function() {
                    alert('Error fetching coordinates.');
                }
            });
        }
    });
});
