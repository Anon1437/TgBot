
<?php

function telegram($method, $data = [])
{
    global $TG;

    $ch = curl_init($TG . "/" . $method);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 10
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return [];
    }

    curl_close($ch);

    return json_decode($response, true);
}

function checkMember($channel, $userId)
{
    $result = telegram("getChatMember", [
        "chat_id" => "@".$channel,
        "user_id" => $userId
    ]);

    if (!isset($result["ok"]) || !$result["ok"]) {
        return false;
    }

    $status = $result["result"]["status"];

    return !in_array($status, [
        "left",
        "kicked"
    ]);
}

function keyboard()
{
    global $CHANNEL_1;
    global $CHANNEL_2;

    return [
        "inline_keyboard" => [
            [
                [
                    "text" => "Join Channel 1",
                    "url" => "https://t.me/".$CHANNEL_1
                ]
            ],
            [
                [
                    "text" => "Join Channel 2",
                    "url" => "https://t.me/".$CHANNEL_2
                ]
            ],
            [
                [
                    "text" => "Verify",
                    "callback_data" => "verify"
                ]
            ]
        ]
    ];
}

function sendMessage($chatId, $text, $markup = null)
{
    $data = [
        "chat_id" => $chatId,
        "text" => $text
    ];

    if ($markup !== null) {
        $data["reply_markup"] = $markup;
    }

    return telegram("sendMessage", $data);
}

function answerCallback($callbackId)
{
    return telegram("answerCallbackQuery", [
        "callback_query_id" => $callbackId
    ]);
}

function verifyUser($chatId, $userId)
{
    global $CHANNEL_1;
    global $CHANNEL_2;

    $joined1 = checkMember($CHANNEL_1, $userId);
    $joined2 = checkMember($CHANNEL_2, $userId);

    if ($joined1 && $joined2) {

        sendMessage(
            $chatId,
            "✅ Verified. Access granted."
        );

    } else {

        sendMessage(
            $chatId,
            "❌ Not verified.\n\nJoin both channels then press Verify.",
            keyboard()
        );

    }
}