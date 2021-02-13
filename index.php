<?php

require_once __DIR__ . "/vendor/autoload.php";
require "TolgeeManager.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$tolgeeManager = new TolgeeManager();

function t(string $key, array $params = [], $noWrap = false): string
{
    global $tolgeeManager;
    return $tolgeeManager->getTolgee()->translate($key, $params, $noWrap);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tolgee PHP Example Application</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?= $tolgeeManager->getTolgeeFrontendPart() ?>
<div id="root">
    <select id="languageSelect" aria-label="<?= t("language_select_aria_label") ?>>">
        <option value="en" <?= $_GET["lang"] === "en" ? "selected" : "" ?>>English</option>
        <option value="cs" <?= $_GET["lang"] === "cs" ? "selected" : "" ?>>Česky</option>
    </select>
    <h1><?= t("hello_world") ?></h1>
    <p><?= t("Strings with parameters can be translated like this:") ?></p>
    <p><?= t("Peter has n dogs", ["dogsCount" => "5"]) ?></p>
    <p><?= t("And also! You can translate text inside options of select!") ?></p>
    <select>
        <option><?= t("hi_i_am_translated_option") ?></option>
    </select>
</div>
<script>
    document.getElementById("languageSelect").onchange = e => {
        window.location.href = `?lang=${e.target.value}`;
    }
</script>
</body>
</html>