<?php
// Prevent accidental errors from displaying to the user
error_reporting(0);

// 1. Get the Streamtape File ID from your website URL (e.g., download.php?id=abc123xyz)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. No file specified.");
}

$file_id = htmlspecialchars($_GET['id']);

// 2. Fetch the direct link from Streamtape
function getStreamtapeDirectLink($file_id) {
    $api_login = "a22694c58fd18bd4970c"; 
    $api_key = "WP464BzDYwSAaQ";     
    
    // FIXED: Added the correct API path and parameters back here
    $url = "https://streamtape.com" . urlencode($api_login) . "&key=" . urlencode($api_key) . "&file=" . urlencode($file_id);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if ($data && $data['status'] == 200 && isset($data['result']['url'])) {
        return $data['result']['url'];
    }
    
    return false;
}

// 3. Process and redirect
$fast_download_url = getStreamtapeDirectLink($file_id);

if ($fast_download_url) {
    header("Location: " . $fast_download_url);
    exit;
} else {
    echo "<h3>The download server is currently overloaded or the file is missing. Please try again later.</h3>";
}
?>
