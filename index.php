<?php
 
require 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Capsule\Manager as Capsule;

####################### CLASSES #######################
class User extends \Illuminate\Database\Eloquent\Model
{
  protected $table = 'users';
  public $timestamps = false;
  protected $fillable = array(
                          'email',
                          'encrypted_password', 
                          'first_name', 
                          'last_name'
                        );
}

class Company extends \Illuminate\Database\Eloquent\Model
{
  protected $table = 'companies';
  public $timestamps = false;
  protected $fillable = array(
                          'name',
                          'description'
                        );
}

class Perm extends \Illuminate\Database\Eloquent\Model
{
  protected $table = 'perms';
  public $timestamps = false;
  protected $fillable = array(
                          'company_id',
                          'user_id',
                          'role'
                        );
}

class Message extends \Illuminate\Database\Eloquent\Model
{
  protected $table = 'messages';
  public $timestamps = false;
  protected $fillable = array(
                          'company_id',
                          'from_user_id',
                          'recipient_user_id', 'message_content', 'timestamp_queued',' timestamp_dequeued');
}


//setup logging
###n$monolog = new \Flynsarmy\SlimMonolog\Log\MonologWriter(array(
###n    'handlers' => array(
###n        new \Monolog\Handler\StreamHandler('./logs/'.date('Y-m-d').'.log'),
###n    ),
###n));

/**
 * Configure the database and boot Eloquent
 */
$capsule = new Capsule;
$capsule->addConnection(array(
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'reach_app2',
    'username'  => 'reachdb',
    'password'  => 'reachdb123',
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => ''
));
$capsule->setAsGlobal();
$capsule->bootEloquent();


// Create Slim app
$app = new \Slim\Slim(array(
'log.enabled' =>    true,
'log.level' =>      \Slim\Log::DEBUG,
));

$app->container->singleton('log', function () {
    $log = new \Monolog\Logger();
    $log->pushHandler(new \Monolog\Handler\StreamHandler('./logs/log.txt'));
    return $log;
});

//setup jwt auth
$app->add(new \Slim\Middleware\JwtAuthentication([
"secure" => false,
"secret" => "supersecretkeyyoushouldnotcommittogithub",
#'logger' => $monolog,
"rules" => [
  new \Slim\Middleware\JwtAuthentication\RequestPathRule([
    "path" => "/",
    "passthrough" => array("/login")
	 ]),
  new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
    "passthrough" => ["OPTIONS"]
    ])
	 ]
]));


#########################
######## Routes #########
#########################

$app->get('/foo', function () {
    // Fetch all books
    $books = \Book::all();
    echo $books->toJson();

    // Or create a new book
    $book = new \Book(array(
        'title' => 'Sahara',
        'author' => 'Clive Cussler'
    ));
    $book->save();
});

//XXX put a if DEVMODE around this.
$app->get('/create-db-schema', function () {
  create_reach_schema();
});

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



#CRUD
$app->get('/user', function() use($app) {
    $app->response->setStatus(200);
    $users = \User::all();
    echo $users->toJson();
});

$app->get('/user/:uid', function($uid) use($app) {
    $app->response->setStatus(200);
});

$app->post('/user', function() use($app) {
    $app->response->setStatus(200);
});

$app->get('/company', function() use($app) {
    $app->response->setStatus(200);
    $companies = \Company::all();
    echo $companies->toJson();
});
$app->options('/company', function() use($app) {
    $app->response->setStatus(200);
});

/*
$app->get('/company/:cid', function($cid) use($app) {
    $app->response->setStatus(200);
});
*/

$app->post('/message', function() use($app) {
    $app->response->setStatus(200);
});

#END CRUD



$app->run();

####################### DATABASE HELPERS ###################
function create_reach_schema() {

  Capsule::schema()->dropIfExists('users');
  Capsule::schema()->create('users', function ($table) {
      $table->increments('id');
      $table->string('email');
      $table->string('encrypted_password');
      $table->string('first_name');
      $table->string('last_name');
  });

  Capsule::schema()->dropIfExists('companies');
  Capsule::schema()->create('companies', function ($table) {
      $table->increments('id');
      $table->string('name');
      $table->string('description');
  });

  Capsule::schema()->dropIfExists('perms');
  Capsule::schema()->create('perms', function ($table) {
      $table->increments('id');
      $table->string('company_id');
      $table->string('user_id');
      $table->string('role'); //enumerate
  });

  Capsule::schema()->dropIfExists('messages');
  Capsule::schema()->create('messages', function ($table) {
      $table->increments('id');
      $table->string('company_id');
      $table->string('from_user_id');
      $table->string('recipient_user_id');
      $table->string('message_content');
      $table->string('timestamp_queued'); //enumerate
      $table->string('timestamp_dequeued'); //enumerate
  });

}



############################################
############ Utility functions #############
############################################

function doLogin() {

    $config = array(
        'jwt' => array(
          'key'       => 'supersecretkeyyoushouldnotcommittogithub',     // Key for signing the JWT's, I suggest generate it with base64_encode(openssl_random_pseudo_bytes(64))
          'algorithm' => 'HS256' // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
          ),
        'serverName' => 'reachapp.com'
    );

    $username = $_POST['email'];
    $password = $_POST['password'];
    
    if ($username && $password) {
        try {
   
            $user = \User::where('email', '=', $username)->take(1)->get();
				$user = $user[0];
            if(true){
    
                /*
                 * Password was generated by password_hash(), so we need to use
                 * password_verify() to check it.
                 * 
                 * @see http://php.net/manual/en/ref.password.php
                 */
                if (md5($password) === $user->encrypted_password) {
					     //setup the data for the jwt
                    $random = mt_rand(0, 999999); 
                    $tokenId = base64_encode($random);
                    //$tokenId    = base64_encode(mcrypt_create_iv(32));
                    $issuedAt   = time();
                    $notBefore  = $issuedAt;  //Adding 10 seconds
                    $expire     = $notBefore + 3600; // Adding 60 seconds
                    $serverName = $config['serverName'];

                    $roles = getRoles($user->id);
						  $companies = array();
						  if(sizeof($roles) > 0){
						    foreach($roles as $role){
                         $company = \Company::where('id', '=', $role->company_id)->get();
								 $companies[] = $company[0];
							 }
                    }


                    /*
                     * Create the token as an array
                     */
                    $data = array(
                        'iat'  => $issuedAt,         // Issued at: time when the token was generated
                        'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                        'iss'  => $serverName,       // Issuer
                        'nbf'  => $notBefore,        // Not before
                        'exp'  => $expire,           // Expire
                        'data' => array(                  // Data related to the signer user
                            'userId'   => $user->id, // userid from the users table
                            'userName' => $username, // User name
                            'roles' => $roles,
									 'companies' => $companies
                        )
                    );
                    
                    //header('Content-type: application/json');
                    /*
                     * Extract the key, which is coming from the config file. 
                     * 
                     * Best suggestion is the key to be a binary string and 
                     * store it in encoded in a config file. 
                     *
                     * Can be generated with base64_encode(openssl_random_pseudo_bytes(64));
                     *
                     * keep it secure! You'll need the exact key to verify the 
                     * token later.
                     */
                    //$secretKey = base64_decode($config['jwt']['key']);
                    $secretKey = $config['jwt']['key'];
                    /*
                     * Extract the algorithm from the config file too
                     */
                    $algorithm = $config['jwt']['algorithm'];
                    
                    /*
                     * Encode the array to a JWT string.
                     * Second parameter is the key to encode the token.
                     * 
                     * The output string can be validated at http://jwt.io/
                     */
                    $jwt = \Firebase\JWT\JWT::encode(
                        $data,      //Data to be encoded in the JWT
                        $secretKey, // The signing key
                        $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
                        );
                        
                    $unencodedArray = array('jwt' => $jwt);
                    //$app->response->setStatus(200);
                    echo json_encode($unencodedArray);
                } else {
                    header('HTTP/1.0 401 Unauthorized');
                }
            } else {
                header('HTTP/1.0 404 Not Found');
            }
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
        }
    } else {
        header('HTTP/1.0 400 Bad Request');
    }
}

function getPusher() {

    $app_id = '140562';
    $app_key = '95129dfbfbc16ec4a811';
    $app_secret = '1a5dac7bf5d8f1fd9c33';

    $pusher = new Pusher( $app_key, $app_secret, $app_id );

    return $pusher;
}
### 
### 
### function getAllUsers() {
###     $sql = "SELECT * FROM users";
###     try {
###         $db = getDBConnection();
###         $stmt = $db->prepare($sql);
###         $stmt->execute();
###         $users = $stmt->fetchAll(PDO::FETCH_OBJ);
###         $db = null;
###         echo '{"users": ' . json_encode($users) . '}';
###     } catch(PDOException $e) {
###         echo '{"error":{"text":'. $e->getMessage() .'}}';
###     }
### }
### 
### function getUser($uid) {
###     $sql = "SELECT * FROM users WHERE uid=:uid";
###     try {
###         $db = getDBConnection();
###         $stmt = $db->prepare($sql);
###         $stmt->bindParam("uid", $uid);
###         $stmt->execute();
###         $user = $stmt->fetchAll(PDO::FETCH_OBJ);
###         $db = null;
###         echo json_encode($user[0]);
###     } catch(PDOException $e) {
###         echo '{"error":{"text":'. $e->getMessage() .'}}';
###     }
### }
### 
### function createUser() {
### 
###     //xxx should check if user already exists
### 
###     $sql = "INSERT INTO users
###             ('email','encrypted_password','first_name','last_name') 
###             VALUES 
###             (:email,:encrypted_password,:first_name,:last_name)";
###     try {
###         $db = getDBConnection();
###         $stmt = $db->prepare($sql);
###         $stmt->bindParam("uid", $uid);
###         $stmt->execute();
###         $db = null;
###     } catch(PDOException $e) {
###         echo '{"error":{"text":'. $e->getMessage() .'}}';
###     }
### }
### 
### function updateUser() {}
### 
### function getAllEmployees($cid) {
### 
###     $sql = "SELECT u.uid,u.first_name,u.last_name FROM users u, perms p WHERE p.cid=:cid AND u.uid=p.uid AND p.role='employee'";
###     try {
###         $db = getDBConnection();
###         $stmt = $db->prepare($sql);
###         $stmt->bindParam("cid", $cid);
###         $stmt->execute();
###         $user = $stmt->fetchAll(PDO::FETCH_OBJ);
###         $db = null;
###         echo json_encode($user);
###     } catch(PDOException $e) {
###         echo '{"error":{"text":'. $e->getMessage() .'}}';
###     }
### }
### 
### function getAllCustomers($cid) {
### 
###     $sql = "SELECT u.uid,u.first_name,u.last_name FROM users u, perms p WHERE p.cid=:cid AND u.uid=p.uid AND p.role='customer'";
###     try {
###         $db = getDBConnection();
###         $stmt = $db->prepare($sql);
###         $stmt->bindParam("cid", $cid);
###         $stmt->execute();
###         $user = $stmt->fetchAll(PDO::FETCH_OBJ);
###         $db = null;
###         echo json_encode($user);
###     } catch(PDOException $e) {
###         echo '{"error":{"text":'. $e->getMessage() .'}}';
###     }
### }
### 
### function createMessage() {
### 
###     //insert the message into the database as unsent
###     $sql = "INSERT INTO messages 
###             (sender_uid,recipient_uid,message_content,timestamp_sent,timestamp_received) 
###             VALUES 
###             (:sender_uid,:recipient_uid,:message_content,:timestamp_sent,:timestamp_received)";
###     //xxx validate data
###     $sender_uid = $_POST['sender_uid'];
###     $recipient_uid = $_POST['recipient_uid'];
###     $message_content = $_POST['message_content'];
###     $current_time = time();
###     $received_time = 0;
### 
###     try {
###         $db = getDBConnection();
###         $stmt = $db->prepare($sql);
###         $stmt->bindParam("sender_uid", $sender_uid);
###         $stmt->bindParam("recipient_uid", $recipient_uid);
###         $stmt->bindParam("message_content", $message_content);
###         $stmt->bindParam("timestamp_sent", $current_time);
###         $stmt->bindParam("timestamp_received", $received_time);
###         $stmt->execute();
###         $db = null;
###     } catch(PDOException $e) {
###         echo '{"error":{"text":'. $e->getMessage() .'}}';
###     }
### 
###     //queue the message for delivery / deliver the message
###     $data = array('name' => $sender_uid, 'message' => $message_content);
###     $pusher = getPusher();
###     $pusher->trigger( 'my_channel'.$recipient_uid, 'my_event', $data );
### 
### }
### 
### function getMessages($uid,$time=NULL) {
### 
### }
### 
### function getAllCompanies() {
###     $sql = "SELECT * FROM companies";
###     try {
###         $db = getDBConnection();
###         $stmt = $db->prepare($sql);
###         $stmt->execute();
###         $companies = $stmt->fetchAll(PDO::FETCH_OBJ);
###         $db = null;
###         echo json_encode($companies);
###     } catch(PDOException $e) {
###         echo '{"error":{"text":'. $e->getMessage() .'}}';
###     }
### }
### 
### function getCompany($cid) {
### 
###     $sql = "SELECT * FROM companies WHERE cid=:cid";
###     try {
###         $db = getDBConnection();
###         $stmt = $db->prepare($sql);
###         $stmt->bindParam("cid", $cid);
###         $stmt->execute();
###         $company = $stmt->fetchAll(PDO::FETCH_OBJ);
###         $db = null;
###         echo json_encode($company[0]);
###     } catch(PDOException $e) {
###         echo '{"error":{"text":'. $e->getMessage() .'}}';
###     }
### 
### }
### 
### 
### function createBulletin() {}
### function getBulletins($uid,$time=NULL) {}
### 
### function sendInvite() {}
### function processInviteAck() {}
### 
### function sendRequest() {}
### function processRequestAck() {}
### 
### 
function getRoles($user_id) {
  $roles = \Perm::where('user_id', '=', $user_id)->get();
  return $roles;
}
### 
### 
### function getDBConnection() {
###     $dbhost="127.0.0.1";
###     $dbuser="reachdb";
###     $dbpass="reachdb123";
###     $dbname="reach_app";
###     $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
###     $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
###     return $dbh;
### }
### 
