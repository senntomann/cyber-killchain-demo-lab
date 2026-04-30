<?php
$user = isset($_POST['user']) ? $_POST['user'] : '';
$command = 'id ' . $user . ' 2>&1';
$output = shell_exec($command);
$message = trim($output);
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログイン結果</title>
    <style>
        body {
            background: #fff;
            color: #000;
            font-family: Arial, "Hiragino Kaku Gothic ProN", "Yu Gothic", Meiryo, sans-serif;
            margin: 0;
        }
        .message {
            font-size: 16px;
            line-height: 1.5;
            padding: 16px;
            text-align: left;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
</body>
</html>
