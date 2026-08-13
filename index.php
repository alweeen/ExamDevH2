<?php
session_start();

$questions = [
    [
        'id' => 1,
        'question' => "You went to a party last night and when you arrived to school the next day, everybody is talking about something you didn't do. What will you do?",
        'choices' => [
            'a' => "Avoid everything and go with your friends",
            'b' => "Go and talk with the person that started the rumors",
            'c' => "Go and talk with the teacher",
        ]
    ],
    [
        'id' => 2,
        'question' => "What quality do you excel the most?",
        'choices' => [
            'a' => "Empathy",
            'b' => "Curiosity",
            'c' => "Perseverance",
        ]
    ],
    [
        'id' => 3,
        'question' => "You are walking down the street when you see an old lady trying to cross, what will you do?",
        'choices' => [
            'a' => "Go and help her",
            'b' => "Go for a policeman and ask him to help",
            'c' => "Keep walking ahead",
        ]
    ],
    [
        'id' => 4,
        'question' => "You had a very difficult day at school, you will maintain a ___ attitude",
        'choices' => [
            'a' => "Depends on the situation",
            'b' => "Positive",
            'c' => "Negative",
        ]
    ],
    [
        'id' => 5,
        'question' => "You are at a party and a friend of yours comes over and offers you a drink, what do you do?",
        'choices' => [
            'a' => "Say no thanks",
            'b' => "Drink it until it is finished",
            'c' => "Ignore him and get angry at him",
        ]
    ],
    [
        'id' => 6,
        'question' => "You just started in a new school, you will...",
        'choices' => [
            'a' => "Go and talk with the person next to you",
            'b' => "Wait until someone comes over you",
            'c' => "Not talk to anyone",
        ]
    ],
    [
        'id' => 7,
        'question' => "In a typical Friday, you would like to..",
        'choices' => [
            'a' => "Go out with your close friends to eat",
            'b' => "Go to a social club and meet more people",
            'c' => "Invite one of your friends to your house",
        ]
    ],
    [
        'id' => 8,
        'question' => "Your relationship with your parents is..",
        'choices' => [
            'a' => "I like both equally",
            'b' => "I like both equally",
            'c' => "I like my Mom the most",
        ]
    ]
];

$results = [
    1 => [
        'title' => "Self-Management", 
        'text' => "You manage yourself well; You take responsibility for your own behavior and well-being."
    ],
    2 => [
        'title' => "Empathy", 
        'text' => "You are emphatic. You see yourself in someone else's situation before doing decisions. You tend to listen to other's voices."
    ],
    3 => [
        'title' => "Self-Awareness", 
        'text' => "You are conscious of your own character, feelings, motives, and desires. The process can be painful but it leads to greater self-awareness."
    ]
];

// login reqs
// If a has the highest count, output Result 2
// If b has the highest count, output Result 3
// If c has the highest count, output Result 1
$logicMap = [
    'a' => 2,
    'b' => 3,
    'c' => 1
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $counts = ['a' => 0, 'b' => 0, 'c' => 0];
    $validAnswers = 0;
    
    // Accept answers to questions
    foreach ($questions as $q) {
        $id = $q['id'];
        if (isset($_POST["question_$id"])) {
            $ans = $_POST["question_$id"];
            // Answers that are not in the above choices will not be accepted.
            if (in_array($ans, ['a', 'b', 'c'])) {
                $counts[$ans]++;
                $validAnswers++;
            }
        }
    }
    
    // The system should accept a total of eight valid answers.
    if ($validAnswers === 8) {
        $maxCount = max($counts);
        $highestResultNumber = 0;
        
        // If any two letters contain the same highest count, output higher result number
        foreach (['a', 'b', 'c'] as $letter) {
            if ($counts[$letter] === $maxCount) {
                $resultNumber = $logicMap[$letter];
                if ($resultNumber > $highestResultNumber) {
                    $highestResultNumber = $resultNumber;
                }
            }
        }
        
        $finalResult = $results[$highestResultNumber];
        $displayResults = true;
    } else {
        $error = "Please provide an answer for all 8 questions.";
        $displayResults = false;
    }
}

// Randomly ordered list containing eight questions
// Re-shuffle when not POSTing or when retaking the test
if (!isset($_SESSION['question_order']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || (isset($displayResults) && $displayResults)) {
    shuffle($questions);
    $_SESSION['question_order'] = $questions;
} else {
    // just for edge case hehe, Preserve order if there was a validation error so the user doesn't get confused
    $questions = $_SESSION['question_order'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Yourself - Personality Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-start: #0f172a;
            --bg-end: #1e1b4b;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, var(--bg-start), var(--bg-end));
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem 1rem;
            background-attachment: fixed;
        }

        .container {
            max-width: 800px;
            width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .question-block {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease;
        }

        .question-block:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .question-text {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #fff;
            line-height: 1.4;
        }

        .options {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .option-label {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .option-label:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .option-label input[type="radio"] {
            display: none;
        }

        .custom-radio {
            width: 20px;
            height: 20px;
            border: 2px solid var(--text-muted);
            border-radius: 50%;
            margin-right: 1rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .custom-radio::after {
            content: '';
            width: 10px;
            height: 10px;
            background: var(--primary);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .option-label input[type="radio"]:checked + .custom-radio {
            border-color: var(--primary);
        }

        .option-label input[type="radio"]:checked + .custom-radio::after {
            transform: scale(1);
        }

        .option-label:has(input[type="radio"]:checked) {
            background: rgba(99, 102, 241, 0.1);
            border-color: var(--primary);
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 1.25rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 2rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(99, 102, 241, 0.6);
        }

        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 500;
        }

        .results-container {
            text-align: center;
            animation: fadeIn 0.8s ease-out forwards;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            min-width: 130px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #818cf8;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .result-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.02));
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem 2rem;
            border-radius: 24px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .result-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(to right, #818cf8, #c084fc);
        }

        .result-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .result-desc {
            font-size: 1.1rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .btn-retake {
            display: inline-block;
            padding: 1rem 2rem;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .btn-retake:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {
            .container { padding: 1.5rem; }
            .header h1 { font-size: 2rem; }
            .question-block { padding: 1.5rem; }
            .stats { gap: 1rem; }
            .stat-box { min-width: 100px; padding: 1rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($displayResults) && $displayResults): ?>
            <div class="results-container">
                <div class="header">
                    <h1>Your Personality Profile</h1>
                    <p>Based on your responses, here is what we discovered.</p>
                </div>

                <div class="stats">
                    <div class="stat-box">
                        <div class="stat-value"><?= $counts['a'] ?></div>
                        <div class="stat-label">Count (a)</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value"><?= $counts['b'] ?></div>
                        <div class="stat-label">Count (b)</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value"><?= $counts['c'] ?></div>
                        <div class="stat-label">Count (c)</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-value"><?= $validAnswers ?></div>
                        <div class="stat-label">Total Answers</div>
                    </div>
                </div>

                <div class="result-card">
                    <h2 class="result-title"><?= htmlspecialchars($finalResult['title']) ?></h2>
                    <p class="result-desc"><?= htmlspecialchars($finalResult['text']) ?></p>
                </div>

                <a href="index.php" class="btn-retake">Take Test Again</a>
            </div>
        <?php else: ?>
            <div class="header">
                <h1>Personality Test</h1>
                <p>Answer the following 8 questions to discover your dominant trait.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <?php foreach ($questions as $index => $q): ?>
                    <div class="question-block" style="animation: fadeIn 0.5s ease-out <?= $index * 0.1 ?>s both;">
                        <div class="question-text"><?= ($index + 1) . ". " . htmlspecialchars($q['question']) ?></div>
                        <div class="options">
                            <?php foreach (['a', 'b', 'c'] as $choice): ?>
                                <label class="option-label">
                                    <input type="radio" name="question_<?= $q['id'] ?>" value="<?= $choice ?>" <?= (isset($_POST["question_{$q['id']}"]) && $_POST["question_{$q['id']}"] === $choice) ? 'checked' : '' ?>>
                                    <span class="custom-radio"></span>
                                    <span class="option-text"><?= htmlspecialchars($choice . ") " . $q['choices'][$choice]) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn-submit">Reveal My Results</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
