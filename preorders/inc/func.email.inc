<?
function doEmail($id)
{
    global $db;
    $sql = "SELECT o.*, g.game_name, g.game_price FROM orders AS o, games as g WHERE o.order_id = '".$id."' AND o.game_id = g.game_id LIMIT 1";

    $result = $db->query($sql);
    $row = mysql_fetch_array($result);
    
    $msg = 'Hi '.$row['order_cust_name']."\n";
    $msg .= 'Thank you for placing a preorder for "'.$row['game_name'].'"'."\n\nThis is a copy of your invoice.\n\n";
    $msg .= 'Customer Information'."\n";
    $msg .= 'Customer Name: '.$row['order_cust_name']."\n";
    $msg .= 'Customer Phone: '.formatPhone($row['order_phone'])."\n";
    $msg .= 'Customer Email: '.$row['order_email']."\n\n";
    
    $msg .= 'Store Information'."\n";
    $msg .= 'Employee Name: '.$row['employee']."\n";
    $msg .= 'Preorder Item: '.$row['game_name']."\n";
    $msg .= 'Console: '.$row['order_console']."\n";
    $msg .= 'Item Price: $'.$row['game_price']."\n";
    $msg .= 'Amount down: $'.$row['order_amount']."\n";
    
    $msg .= "\n\n";
    $msg .= "Thanks,\nGG Staff";
    
    $to = $row['order_email'];
    $subject = 'Gaming Generations - Preorder : Confirmation';
    $headers = 'From: noreply@gaminggenerations.com' . "\r\n" .
    'Reply-To: support@gaminggenerations.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $msg, $headers);
}

function massEmail($gameid, $subject, $msg)
{
    global $db;
    
    $sql = "SELECT order_email FROM orders WHERE game_id = ".$gameid." AND order_email != ''";

    $result = $db->query($sql);
    while($row = mysql_fetch_array($result))
    {
        $bcc[] = $row['order_email'];
    }

    $mass_bcc = implode(',', $bcc);
    //$mass_bcc = "kambodianboi@xnogard.com, evan@gaminggenerations.com";
    $to = 'Gaming Gen <noreply@gaminggenerations.com>';
    $headers = 'From: noreply@gaminggenerations.com' . "\r\n" .
    'Reply-To: support@gaminggenerations.com' . "\r\n" .
    'Bcc: ' .$mass_bcc. "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $msg, $headers);
}
?>