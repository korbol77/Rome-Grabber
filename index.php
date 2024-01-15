<?php
const WEBHOOK_DEFAULT = "https://discordapp.com/api/webhooks/";

$errors = array();
$new_rome_url = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $website_url = $_POST["website_url"];
    $webhook_url = $_POST["webhook_url"];

    if (empty(trim($website_url)) || empty(trim($webhook_url))) {
        $errors[] = "Fields can't be empty!";
    } else {
        if (str_starts_with($webhook_url, WEBHOOK_DEFAULT)) {
            $default_page_url = "http://127.0.0.1/rome_grabber/"; // Your website URL

            $b64_website_url = base64_encode($website_url);
            $b64_webhook_url_short = base64_encode(explode(WEBHOOK_DEFAULT, $webhook_url)[1]);
            $new_rome_url = $default_page_url . "redirect.php?id=$b64_webhook_url_short&target=$b64_website_url";
        } else {
            $errors[] = "You provided an incorrect webhook url format!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rome Grabber - Home</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body>
    <main class="main_page">
        <div class="rome_header">
            <img src="./assets/img/rome_grabber_logo.png" alt="🏛️">
            <h1>Rome Grabber</h1>
        </div>
        <form action="./index.php" method="POST">
            <div class="form_panel">
                <label for="website_url">Website URL:</label>
                <input type="text" name="website_url" id="website_url">
            </div>
            <div class="form_panel">
                <label for="webhook_url">Discord Webhook URL:</label>
                <input type="text" name="webhook_url" id="webhook_url">
            </div>
            <?php if (!empty($errors)) {
                echo "<p style='color: #dc2626'>$errors[0]</p>";
            } ?>
            <button type="submit" class="main_btn">Create URL</button>
            <?php if (!empty($new_rome_url)) {
                echo "<p class='rome_link'>$new_rome_url</p>";
            } ?>
        </form>
    </main>
    <footer>
        <a href="https://github.com/korbol77" target="_blank"><i class="fa-brands fa-github" title="My Github Profile"></i></a>
    </footer>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(0, 0, document.URL);
        }
    </script>
</body>

</html>