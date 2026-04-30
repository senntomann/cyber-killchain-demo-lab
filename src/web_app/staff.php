<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>職員専用ログイン</title>
    <style>
        body {
            background: #f4f7fb;
            color: #1c2733;
            font-family: Arial, "Hiragino Kaku Gothic ProN", "Yu Gothic", Meiryo, sans-serif;
            margin: 0;
        }
        .wrap {
            margin: 64px auto;
            max-width: 520px;
            padding: 0 24px;
        }
        .card {
            background: #fff;
            border: 1px solid #d9e2ec;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(28, 39, 51, 0.08);
            padding: 32px;
        }
        h1 {
            font-size: 28px;
            margin: 0 0 8px;
        }
        p {
            color: #52616f;
            line-height: 1.6;
            margin: 0 0 24px;
        }
        label {
            display: block;
            font-weight: bold;
            margin: 18px 0 8px;
        }
        input[type="text"],
        input[type="password"] {
            border: 1px solid #bcccdc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            padding: 12px;
            width: 100%;
        }
        input[type="submit"] {
            background: #0b5cad;
            border: 0;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 22px;
            padding: 12px 18px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>職員専用ログイン</h1>
            <p>研究室職員向けポータルです。職員IDとパスワードを入力してください。</p>
            <form action="staff_result.php" method="post">
                <label for="user">職員ID</label>
                <input id="user" type="text" name="user" autocomplete="username">

                <label for="password">パスワード</label>
                <input id="password" type="password" name="password" autocomplete="current-password">

                <input type="submit" value="ログイン" name="s">
            </form>
        </div>
    </div>
</body>
</html>
