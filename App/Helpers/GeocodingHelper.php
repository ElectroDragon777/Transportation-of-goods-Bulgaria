<?php
namespace App\Helpers;

class GeocodingHelper
{
    /**
     * Geocode an address using Nominatim OpenStreetMap API
     * 
     * @param string $address The address to geocode
     * @return array|null Returns [lat, lng] coordinates or null if geocoding failed
     */
    public static function geocodeAddress($address)
    {
        if (empty($address)) {
            return null;
        }

        // URL encode the address for the API request
        $encodedAddress = urlencode($address);

        // Construct the URL for the Nominatim API
        $url = "https://nominatim.openstreetmap.org/search?q={$encodedAddress}&format=json&limit=1";

        // Set user agent as required by Nominatim usage policy
        $options = [
            'http' => [
                'header' => "User-Agent: CourierTrackingApp/1.0\r\n",
                'timeout' => 10 // 10 second timeout
            ]
        ];

        $context = stream_context_create($options);

        // Make the request
        $response = @file_get_contents($url, false, $context);

        // Check if the request was successful
        if ($response === false) {
            error_log("Geocoding failed for address: " . $address);
            return null;
        }

        // Parse the response
        $data = json_decode($response, true);

        // Check if we got valid results
        if (empty($data) || !isset($data[0]['lat']) || !isset($data[0]['lon'])) {
            error_log("No geocoding results found for address: " . $address);
            return null;
        }

        // Return the coordinates
        return [
            'lat' => (float) $data[0]['lat'],
            'lng' => (float) $data[0]['lon']
        ];
    }

    /**
     * Extract coordinates from input string in format "lat,lng"
     * 
     * @param string $coordString The coordinate string
     * @return array|null Returns [lat, lng] or null if invalid
     */
    public static function parseCoordinates($coordString)
    {
        if (empty($coordString)) {
            return null;
        }

        $parts = explode(',', $coordString);
        if (count($parts) != 2) {
            return null;
        }

        $lat = trim($parts[0]);
        $lng = trim($parts[1]);

        if (!is_numeric($lat) || !is_numeric($lng)) {
            return null;
        }

        return [
            'lat' => (float) $lat,
            'lng' => (float) $lng
        ];
    }
}
?>