<pre>
<?php
    use App\A2IDPass;

    $password = "mytest123";
    $encrypted = A2IDPass::write($password);
    $newpass = A2IDPass::read($encrypted);
    echo "pass: ".$password."<br>";
    echo "encrypted: ".$encrypted."<br>";
    echo "newpass: ".$newpass."<br>";

    echo "<br>Verifying password: ";
    echo (A2IDPass::verify($password, $encrypted)) ? "Passed!" : "Nope!";
?>
</pre>