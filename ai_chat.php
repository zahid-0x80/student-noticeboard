<?php
include 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$question = isset($data['question']) ? $data['question'] : '';

if ($question == '') {
    echo json_encode(['reply' => 'Please ask a question.']);
    exit;
}

$result = mysqli_query($conn, "SELECT title, message, category, created_at FROM notices ORDER BY created_at DESC");
$notices = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notices[] = "Title: " . $row['title'] . "\nMessage: " . $row['message'] . "\nCategory: " . $row['category'] . "\nPosted: " . $row['created_at'];
}

if (count($notices) == 0) {
    echo json_encode(['reply' => 'There are no notices on the board yet.']);
    exit;
}

$noticesText = implode("\n\n", $notices);


include 'config.php';
$apiKey = GROQ_API_KEY;

$prompt = "You are a helpful assistant for a university noticeboard. Answer the student's question based only on the notices below. If the answer is not in the notices, say you could not find relevant information.\n\nNotices:\n$noticesText\n\nStudent question: $question";

$payload = [
    'model' => 'llama-3.3-70b-versatile',
    'messages' => [
        [
            'role' => 'user',
            'content' => $prompt
        ]
    ]
];

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true);
$reply = isset($responseData['choices'][0]['message']['content']) 
    ? $responseData['choices'][0]['message']['content'] 
    : 'Sorry, something went wrong.';

echo json_encode(['reply' => $reply]);
?>