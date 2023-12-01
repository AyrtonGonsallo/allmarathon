<?php 
$options = [
    'cost' => 12,
];
echo password_hash("luther", PASSWORD_BCRYPT, $options);
echo '   ';
echo (password_verify("luther",password_hash("luther", PASSWORD_BCRYPT, $options))==1);
?>