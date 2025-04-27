<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    if ($data === null) {
        echo json_encode(["error" => "Invalid JSON received."]);
        exit();
    }

    if (isset($data['text'])) {
        $receivedText = $data['text'];

        $gemini_api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
        $api_key = 'AIzaSyDJbMYr587_qbKwoBmR7xYyjzudDSe_Vas';
        
     $systemPrompt = "You are a therapy chatbot with a capybara theme. Make sure your responses keep a conversation going.\n\nWhen a user submits a message, do two things:\n\n1. Interpret the emotion shown.\n2. Reply with a short, calming message in a friendly capybara style.\n\nRespond ONLY in proper JSON format like this:\n{\n  \"responseText\": \"your calming reply here\",\n  \"emotionFelt\": \"the emotion you detected\"\n}\n\nExample:\n{\n  \"responseText\": \"your calming reply here\",\n  \"emotionFelt\": \"anxious\"\n}\n\n- Only use one of these emotions: anxious, overwhelmed, lonely, numb, excited\n- Always send the emotion back in all lowercase.\n- Do NOT add any extra text, headers, explanations, or quotes around the JSON.\n- Only output pure JSON. No triple quotes, no markdown, no code blocks, no extra symbols.";





        $post_data = json_encode([
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\n" . $receivedText],
                    ],
                ],
            ],
        ]);

        $ch = curl_init($gemini_api_url . '?key=' . $api_key);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo json_encode([
                "status" => "error",
                "message" => curl_error($ch)
            ]);
        } else {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($http_code == 200) {
                $result = json_decode($response, true);

                $generated_text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No text found in response.';

                echo json_encode([
                    "status" => "success",
                    "generated_text" => $generated_text
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "http_code" => $http_code,
                    "response" => $response
                ]);
            }
        }

        curl_close($ch);

    } else {
        echo json_encode(["error" => "No text field provided."]);
    }
} else {
    echo json_encode(["error" => "Invalid request method. Only POST allowed."]);
}
