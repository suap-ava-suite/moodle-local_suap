<?php
define("MOODLE_INTERNAL", true);
define("MATURITY_STABLE", true);
$plugin = new stdClass();
include_once("version.php");

echo json_encode(
    [
        "component" => $plugin->component,
        "release" => $plugin->release,
        "version" => $plugin->version,
    ],
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);
