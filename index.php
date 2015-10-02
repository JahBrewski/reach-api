<?php
 
require 'vendor/autoload.php';
$app = new \Slim\Slim();

#########################
######## Routes #########
#########################


$app->get('/', function() use($app) {
    $app->response->setStatus(200);
    echo "Reach API v1.0";
}); 

$app->post('/login', function() use($app) {
    $app->response->setStatus(200);
    doLogin();

});

$app->options('/login', function() use($app) {
    $app->response->setStatus(200);
});

//user
$app->get('/user', function() use($app) {
    $app->response->setStatus(200);
    getAllUsers();
});

$app->get('/user/:uid', function($uid) use($app) {
    $app->response->setStatus(200);
    getUser($uid);
});

$app->post('/user', function() use($app) {
    $app->response->setStatus(200);
    updateUser();
});

$app->get('/company', function() use($app) {
    $app->response->setStatus(200);
    getAllCompanies();
});

$app->get('/company/:cid', function($cid) use($app) {
    $app->response->setStatus(200);
    getCompany($cid);
});

$app->get('/company/:cid/employees', function($cid) use($app) {
    $app->response->setStatus(200);
    getAllEmployees($cid);
});

$app->get('/company/:cid/customers', function($cid) use($app) {
    $app->response->setStatus(200);
    getAllCustomers($cid);
});


//message
/*
$app->get('/message/:uid/:timestamp', function() use($app) {
    $app->response->setStatus(200);
    getMessages($uid,$timestamp);
});
*/

$app->post('/message', function() use($app) {
    $app->response->setStatus(200);
    createMessage();
});

$app->get('/message', function() use($app) {
    $app->response->setStatus(200);
    echo'
       <html><body><form method="post">
       <br>sender:<input name="sender_uid">
       <br>recip: <input name="recipient_uid">
       <br>message:<input name="message_content">
       <br><input type="submit"> 
      </form></body></html>
    ';
});


//bulletin
$app->get('/bulletin/:cid/:timestamp', function() use($app) {
    $app->response->setStatus(200);
    getBulletins($cid,$timestamp);
});

$app->post('/bulletin', function() use($app) {
    $app->response->setStatus(200);
    sendBulletin();
});

############################################
############ Utility functions #############
############################################

function doLogin() {

    $sql = "SELECT * FROM users WHERE email=:email AND encrypted_password=MD5(:password)";

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $db = getDBConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->bindParam("password", $password);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        //$db = null;

        echo json_encode($user[0]);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


function getAllUsers() {
    $sql = "SELECT * FROM users";
    try {
        $db = getDBConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"users": ' . json_encode($users) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getUser($uid) {
    $sql = "SELECT * FROM users WHERE uid=:uid";
    try {
        $db = getDBConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("uid", $uid);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user[0]);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function createUser() {

    //xxx should check if user already exists

    $sql = "INSERT INTO users
            ('email','encrypted_password','first_name','last_name') 
            VALUES 
            (:email,:encrypted_password,:first_name,:last_name)";
    try {
        $db = getDBConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("uid", $uid);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateUser() {}

function getAllEmployees($cid) {

    $sql = "SELECT u.uid,u.first_name,u.last_name FROM users u, perms p WHERE p.cid=:cid AND u.uid=p.uid AND p.role='employee'";
    try {
        $db = getDBConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cid", $cid);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getAllCustomers($cid) {

    $sql = "SELECT u.uid,u.first_name,u.last_name FROM users u, perms p WHERE p.cid=:cid AND u.uid=p.uid AND p.role='customer'";
    try {
        $db = getDBConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cid", $cid);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function createMessage() {

    //insert the message into the database as unsent
    $sql = "INSERT INTO messages 
            (sender_uid,recipient_uid,message_content,timestamp_sent,timestamp_received) 
            VALUES 
            (:sender_uid,:recipient_uid,:message_content,:timestamp_sent,:timestamp_received)";
    //xxx validate data
    $sender_uid = $_POST['sender_uid'];
    $recipient_uid = $_POST['recipient_uid'];
    $message_content = $_POST['message_content'];
    $current_time = time();
    $received_time = 0;

    try {
        $db = getDBConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("sender_uid", $sender_uid);
        $stmt->bindParam("recipient_uid", $recipient_uid);
        $stmt->bindParam("message_content", $message_content);
        $stmt->bindParam("timestamp_sent", $current_time);
        $stmt->bindParam("timestamp_received", $received_time);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }

    //queue the message for delivery / deliver the message
    $data = array('name' => $sender_uid, 'message' => $message_content);
    $pusher = getPusher();
    $pusher->trigger( 'my_channel'.$recipient_uid, 'my_event', $data );

}

function getMessages($uid,$time=NULL) {

}

function getAllCompanies() {
    $sql = "SELECT * FROM companies";
    try {
        $db = getDBConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $companies = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($companies);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getCompany($cid) {

    $sql = "SELECT * FROM companies WHERE cid=:cid";
    try {
        $db = getDBConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cid", $cid);
        $stmt->execute();
        $company = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($company[0]);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }

}


function createBulletin() {}
function getBulletins($uid,$time=NULL) {}

function sendInvite() {}
function processInviteAck() {}

function sendRequest() {}
function processRequestAck() {}


function getDBConnection() {
    $dbhost="127.0.0.1";
    $dbuser="reachdb";
    $dbpass="reachdb123";
    $dbname="reach_app";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

function getPusher() {

    $app_id = '140562';
    $app_key = '95129dfbfbc16ec4a811';
    $app_secret = '1a5dac7bf5d8f1fd9c33';

    $pusher = new Pusher( $app_key, $app_secret, $app_id );

    return $pusher;
}

$app->run();
