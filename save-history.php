<?php
error_log("POST: " . print_r($_POST, 1));
$response_url = $_POST["response_url"];
$command = $_POST['command'];
$domain = $_POST['text'];
$token = $_POST['token'];
$user = $_POST['user_name'];

if( $token == 'C6vVZxXoJzlIeDLTU943NMkm' || $token == 'HZ1YrUs1MRgxDF8cn60N9PMT' || $token == 'qimg5I8Ugim23eyCM0NipBc7' || $token = 'Pj0koI7ReFfkobwwlbclgtwL' || $token == 'Q2yLWcnTMHZfn1RNAqqvRzfQ' || $token = 'UzbxMw87O82z4C2OHOwc5jUl' ) {

    ob_end_clean();
    ob_start();
    $response = ["response_type" => "in_channel", "text" => "Hi ".$user.", :mag_right: Checking, please wait..."];
    echo json_encode($response);
    header("Content-Type: application/json");
    header("Content-Length: " . ob_get_length());
    ob_end_flush();
    flush();

    if($command == '/history' && $domain != '') {
        $output = '';
        $output .= shell_exec('grunt gen-conf --theme=' . $domain . ' && wraith history configs/' . $domain . '_config.yaml');

        error_log("History output: $output");

        $reply = ":smile: ".$user." I am happy to report that *history snaps* have been *generated* run `/wraith ". $domain ."` for a report!";

        $replyjson = json_encode([
            "response_type" => "in_channel",
            "text" => $reply
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $response_url,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $replyjson
        ]);

        $resp = curl_exec($ch);
        curl_close($ch);

        error_log("API response: $resp");
    } else if($command == '/wraith' && $domain != '') {
        $output = '';
        $output .= shell_exec('wraith latest configs/' . $domain . '_config.yaml');

        error_log("History output: $output");

        $reply = ":smile: " . $user . " I am happy to report that *<http://104.236.125.82/slack-commands/" . $domain . "_shots/gallery.html>* has been *generated*!";

        $replyjson = json_encode([
            "response_type" => "in_channel",
            "text" => $reply
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $response_url,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $replyjson
        ]);

        $resp = curl_exec($ch);
        curl_close($ch);

        error_log("API response: $resp");
    } else if($command == '/wraith_compare' && $domain != '') {
        $domain_array =  explode(' vs ',$domain);
        $name = str_replace('.','_',$user);

        $replyjson = json_encode([
            "response_type" => "in_channel",
            "text" => "Generating config file ..."
        ]);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $response_url,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $replyjson
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);
        $log_output = shell_exec('grunt gen-conf --type=compare --domain1='.$domain_array[0].' --domain2='.$domain_array[1].' --name='.$name.' ');
        $status = shell_exec('echo "$?"');
        if( $status != 0 ) {
            $replyjson = json_encode([
                "response_type" => "in_channel",
                "text" => "An *error* might have occurred:",
                "attachments"=> [
                        "text"=> $log_output
                ]
            ]);
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $response_url,
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $replyjson
            ]);
            $resp = curl_exec($ch);
            curl_close($ch);
        }

        $log_output = shell_exec('wraith capture configs/compare_'.$name.'_config.yaml ');
        $status = shell_exec('echo "$?"');
        if( $status != 0 ) {
            $replyjson = json_encode([
                "response_type" => "in_channel",
                "text" => "An *error* might have occurred:",
                "attachments"=> [
                    "text"=> $log_output
                ]
            ]);
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $response_url,
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $replyjson
            ]);
            $resp = curl_exec($ch);
            curl_close($ch);
        }

        error_log("History output: $log_output");

        $reply = ":smile: " . $user . " I am happy to report that *<http://104.236.125.82/slack-commands/compare_".$user."_shots/gallery.html>* has been *generated*!";

        $replyjson = json_encode([
            "response_type" => "in_channel",
            "text" => $reply
        ]);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $response_url,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $replyjson
        ]);

        $resp = curl_exec($ch);
        curl_close($ch);
    } else {
        $replyjson = json_encode([
            "response_type" => "in_channel",
            "text" => ":disappointed: ".$user." I am afraid i can't respond to this request"
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $response_url,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $replyjson
        ]);

        $resp = curl_exec($ch);
        curl_close($ch);
    }


} else {
    $msg = "The token for the slash command doesn't match. Check your script.";
    die($msg);
    echo $msg;
}




