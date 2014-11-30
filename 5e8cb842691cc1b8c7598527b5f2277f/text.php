<?php


$texteACrypter = "he who doesn't do anything, doesn't go wrong -- Zeev Suraski";
$clefSecrete = "glop";


$encrypted = openssl_encrypt($texteACrypter, "AES-128-CBC", $clefSecrete);
$decrypted = openssl_decrypt($encrypted, "AES-128-CBC", $clefSecrete);
echo $method . ' : ' . $encrypted . ' ; ' . $decrypted . "\n";


?>