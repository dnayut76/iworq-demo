<pre>
<?php
    use App\A2IDPass;
    use App\Config;

    $password = "mytest123";
    $encrypted = A2IDPass::write($password);
    $newpass = A2IDPass::read($encrypted);
    echo "pass: ".$password."<br>";
    echo "encrypted: ".$encrypted."<br>";
    echo "newpass: ".$newpass."<br>";

    echo "<br>Verifying password: ";
    echo (A2IDPass::verify($password, $encrypted)) ? "Passed!" : "Nope!";

    echo "<br><hr><br>";

    echo "Retrieving darren6@test.com user from users table.<br>";

    $config = new Config();

    $dsn = 'mysql:host=' . $config->get('db.host')
     . ';dbname=' . $config->get('db.db')
     . ';charset=utf8mb4';

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,  // use real prepared statements
    ];

    $pdo = new PDO($dsn, $config->get('db.user'), $config->get('db.pass'), $options);

    $dbh = $pdo->prepare('SELECT password FROM users WHERE email = ? LIMIT 1');
    $dbh->execute(["darren6@test.com"]);
    $user = $dbh->fetch(PDO::FETCH_ASSOC);

    if (!$user) { 
        echo "User not found in db!<br>";
    } else {
        $password = "abc1234ABC!";
        echo "Encrypted PW: ".$user['password']."<br>";
        echo "Plain text PW: ".$password."<br>";
        echo "Verifying PW: ";
        echo (A2IDPass::verify($password, $user['password'])) ? "Passed!" : "Nope!";
    }

?>
</pre>