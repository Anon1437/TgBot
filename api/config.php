<?php

$BOT_TOKEN = getenv("BOT_TOKEN");

$CHANNEL_1 = getenv("CHANNEL_1") ?: "tech_scripterindia";
$CHANNEL_2 = getenv("CHANNEL_2") ?: "tech_scripterindia";

$TG = "https://api.telegram.org/bot{$BOT_TOKEN}";

?>