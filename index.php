<?php
$data = null;
$errorMessage = ''; // Variable to hold error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ip = $_POST['ip'];

    // Validate the IP address
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        // Valid IP address, proceed with API call
        $accessKey = 'YOUR_API_KEY';
        $apiUrl = "http://api.ipstack.com/{$ip}?access_key={$accessKey}";

        // Fetch the data
        $response = @file_get_contents($apiUrl); // Use @ to suppress warnings

        // Check if the response is valid
        if ($response === FALSE) {
            $errorMessage = "Error connecting to the API. Please try again later.";
        } else {
            $data = json_decode($response, true);

            // Check if the data is valid
            if ($data && isset($data['error'])) {
                $errorMessage = "Error: " . htmlspecialchars($data['error']['info']);
            } elseif ($data && isset($data['country_name'])) {
                // Data is valid, proceed to display it
            } else {
                $errorMessage = "Error retrieving data for this IP address.";
            }
        }
    } else {
        $errorMessage = "Please enter a valid IP address.";
    }
}
?>

<html>
    <head>
        <title>IPesto | Free IP Lookup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <div class="container" id="content">
            <h1 id="title"><a href="index.php" style="text-decoration: none; color: inherit;">IPesto</a></h1>
            <p id="subtitle">Free basic IP lookup!</p>

            <form action="index.php" method="post">
                <label for="ip">Enter the IP Address you want to lookup:</label>
                <input type="text" id="ip" name="ip" pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" title="Please enter a valid IP address (xxx.xxx.xxx.xxx)" required>
                <button type="submit">Submit</button>
            </form>

            <?php if (!empty($errorMessage)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php elseif ($data): ?>
                <h2>Network Data for <?php echo htmlspecialchars($ip); ?></h2>
                <p>Type: <?php echo htmlspecialchars($data['type']); ?></p>
                <p>Connection Type: <?php echo htmlspecialchars($data['connection_type']); ?></p>

                <h2>Geolocation Data for <?php echo htmlspecialchars($ip); ?></h2>
                <p>Country: <?php echo htmlspecialchars($data['country_name']); ?></p>
                <p>Region: <?php echo htmlspecialchars($data['region_name']); ?></p>
                <p>City: <?php echo htmlspecialchars($data['city']); ?></p>
                <p>Latitude: <?php echo htmlspecialchars($data['latitude']); ?></p>
                <p>Longitude: <?php echo htmlspecialchars($data['longitude']); ?></p>
                <p>Languages: <?php echo htmlspecialchars($data['location']['languages'][0]['name']); ?></p>
                <p>Calling Code: <?php echo htmlspecialchars($data['location']['calling_code']); ?></p>
                <p>Continent: <?php echo htmlspecialchars($data['continent_name']); ?></p>
                <p>Location: <?php echo htmlspecialchars($data['location']['geoname_id']); ?></p>
            <?php endif; ?>
        </div>

        

    </body>
</html>
