
<?php

require_once "config.php";
require_once "function.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    echo json_encode([
        "status" => "Bot Running"
    ]);

    exit;
}

$update = json_decode(
    file_get_contents("php://input"),
    true
);

if (!$update) {

    echo json_encode([
        "ok" => true
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Message
|--------------------------------------------------------------------------
*/

if (isset($update["message"])) {

    $message = $update["message"];

    $chatId = $message["chat"]["id"];
    $userId = $message["from"]["id"];

    verifyUser(
        $chatId,
        $userId
    );
}

/*
|--------------------------------------------------------------------------
| Callback Query
|--------------------------------------------------------------------------
*/

if (isset($update["callback_query"])) {

    $query = $update["callback_query"];

    answerCallback(
        $query["id"]
    );

    if ($query["data"] == "verify") {

        verifyUser(
            $query["message"]["chat"]["id"],
            $query["from"]["id"]
        );
    }
}

echo json_encode([
    "ok" => true
]);

?>