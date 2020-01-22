<?php
  include "../../meta.php";
  include "loginFunc.php";
?>
<title>temp title</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <main>
        <div class="loginContain">
            <h1>Login</h1>
            <div>
                <form method="post">
                    <input id="InputUserID" type="email" name="email" placeholder="Email">
                    <input id="InputPassID" type="password" name="password" placeholder="Password">
                    <button id="loginID" class="login" name="loginButton">Login</button>
                </form>
            </div>
        </div>
    </main>
</body>
