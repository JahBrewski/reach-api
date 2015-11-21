<?php
 
require 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Capsule\Manager as Capsule;

####################### CLASSES #######################
//@todo  autoload these
class User extends \Illuminate\Database\Eloquent\Model
{
  protected $table = 'users';
  public $timestamps = false;
  protected $fillable = array(
                          'email',
                          'encrypted_password', 
                          'first_name', 
                          'last_name',
                          'company_name', 
                          'position' 
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
                          'recipient_user_id', 
								  'message_content', 
								  'timestamp_queued',
								  'timestamp_dequeued'
								  );
}

class Bulletin extends \Illuminate\Database\Eloquent\Model
{
  protected $table = 'bulletins';
  public $timestamps = false;
  protected $fillable = array(
                          'company_id',
                          'from_user_id',
                          'message_content', 
								  'timestamp_queued',
								  'timestamp_dequeued'
								  );
}

//setup logging
$monolog = new \Flynsarmy\SlimMonolog\Log\MonologWriter(array(
    'handlers' => array(
        new \Monolog\Handler\StreamHandler('./logs/'.date('Y-m-d').'.log'),
    ),
));

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
    'log.writer' => $monolog
));

//setup jwt auth
$app->add(new \Slim\Middleware\JwtAuthentication([
    "secure" => false,
    "secret" => "supersecretkeyyoushouldnotcommittogithub",
    "callback" => function ($options) use ($app) {
            $app->jwt = $options["decoded"];
	               },
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


#################################################
#################### Routes #####################
#################################################

//accept all options
$app->options('/(:name+)', function() use($app) {
    $app->response->setStatus(200);
});

$app->get('/', function() use($app) {
    $app->response->setStatus(200);
    echo "Reach API v1.0";
}); 

$app->post('/login', function() use($app) {
    $app->response->setStatus(200);
    doLogin();
});

$app->post('/pusher/auth', function() use($app) {
    $app->response->setStatus(200);
    $app_id = '140562';
    $app_key = '95129dfbfbc16ec4a811';
    $app_secret = '1a5dac7bf5d8f1fd9c33';
    $pusher = new Pusher( $app_key, $app_secret, $app_id );
    echo $pusher->socket_auth($_POST['channel_name'], $_POST['socket_id']);
});

$app->get('/user', function() use($app) {
    $app->response->setStatus(200);
    $users = \User::all();
    echo $users->toJson();
});

$app->get('/user/:uid', function($uid) use($app) {
    $user = \User::find($uid);
    $app->response->setStatus(200);
    echo $user->toJson();
});

$app->post('/user', function() use($app) {
    $app->response->setStatus(200);
	 $posty = $app->request->post();
	 //XXX check to see if the userID matches the JWT.
    $user = \User::find($posty['id']);
	 $user->first_name = $posty['first_name'];
	 $user->last_name = $posty['last_name'];
	 $user->email = $posty['email'];
	 $user->company_name = $posty['company_name'];
	 $user->position = $posty['position'];
	 $user->save();
});

$app->get('/company', function() use($app) {
    $app->response->setStatus(200);

    //figure out what companies they can know about.
	 $role_admin = $app->jwt->role_admin;
	 $role_employee = $app->jwt->role_employee;
	 $role_customer = $app->jwt->role_customer;
	 $all_roles = array_merge($role_admin, $role_employee, $role_customer);

    $companies = \Company::all();
    //index by company ID
	 if(sizeof($companies) > 0){
      foreach($companies as $company){
		  if(in_array($company->id, $all_roles)){
          $indexed_companies[$company->id] = $company;
		  }
		}
	 }
    echo json_encode($indexed_companies);
});
/*
$app->get('/company/:cid', function($cid) use($app) {
    $app->response->setStatus(200);
});
*/
$app->get('/message/:uid', function($uid) use($app) {
    $app->response->setStatus(200);
	 $req_user = $app->jwt->data->userId;
    $messages = \Message::orderBy('timestamp_queued','ASC')
	 ->whereRaw('(from_user_id=? OR recipient_user_id=?) AND (from_user_id=? OR recipient_user_id=?)', 
	   [$uid,$uid,$req_user,$req_user])
	 ->get();
	 echo $messages->toJson();
});

$app->post('/message', function() use($app) {
    $app->response->setStatus(200);
	 $posty = $app->request->post();
	 $message = new \Message();
	 $message->company_id = '1'; //XXX bad
	 $message->from_user_id = $posty['sender_uid'];
	 $message->recipient_user_id = $posty['recipient_uid'];
	 $message->message_content = $posty['message_content'];
	 $message->timestamp_queued = time();
	 $message->timestamp_dequeued = time();
	 $message->save();

    //XXX XXX XXX
    $app_id = '140562';
    $app_key = '95129dfbfbc16ec4a811';
    $app_secret = '1a5dac7bf5d8f1fd9c33';
    $pusher = new Pusher( $app_key, $app_secret, $app_id );
    $pusher->trigger( 'my_channel'.$posty['recipient_uid'], 'my_event', $posty['message_content']);

});

$app->get('/feed/:uid', function($uid) use($app) {
    $app->response->setStatus(200);
    $all_messages = \Message::orderBy('timestamp_queued','DESC')
	 ->whereRaw('from_user_id=? OR recipient_user_id=?', 
	   [$uid,$uid])
    ->get();
	 $feed = array();
	 $contacts = array();
    foreach($all_messages as $message){
      $contact = ($message->recipient_user_id !== $uid) ? 
		  $message->recipient_user_id : $message->from_user_id;
		if(isset($contacts[$contact])){
        continue;
		}
      $contacts[$contact] = TRUE;
		$feed[] = $message;
    }
    echo json_encode($feed);
});

$app->get('/bulletin/:cid', function($cid) use($app) {
    $app->response->setStatus(200);
    $bulletins = \Bulletin::orderBy('timestamp_queued','DESC')->where('company_id','=',$cid)->get();
	 echo $bulletins->toJson();
});

$app->post('/bulletin', function() use($app) {
    $app->response->setStatus(200);
	 $posty = $app->request->post();
	 $bulletin = new \Bulletin();
	 $bulletin->company_id = $posty['company_id']; 
	 $bulletin->from_user_id = $posty['sender_uid'];
	 $bulletin->message_content = $posty['message_content'];
	 $bulletin->timestamp_queued = time();
	 $bulletin->timestamp_dequeued = time();
	 $bulletin->save();

    //XXX XXX XXX
##    $app_id = '140562';
##    $app_key = '95129dfbfbc16ec4a811';
##    $app_secret = '1a5dac7bf5d8f1fd9c33';
##    $pusher = new Pusher( $app_key, $app_secret, $app_id );
##    $pusher->trigger( 'my_channel'.$posty['recipient_uid'], 'my_event', $posty['message_content']);

});


$app->get('/employee/:cid', function($cid) use($app) {
    $app->response->setStatus(200);
    $employees = \User::whereRaw("id in (select distinct user_id from perms where (role='admin' OR role='employee') and company_id='".$cid."')")->get();
	 echo $employees->toJson();
});

$app->get('/customer/:cid', function($cid) use($app) {
    $app->response->setStatus(200);
    $customers = \User::whereRaw("id in (select distinct user_id from perms where role='customer' and company_id='".$cid."')")->get();
	 echo $customers->toJson();
});


#END CRUD
//XXX..
$app->get('/create-db-schema', function () {
  create_reach_schema();
});

$app->run();

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
                    $expire     = $notBefore + 3600000; // Adding 60 seconds
                    $serverName = $config['serverName'];

						  $roles = \Perm::where('user_id', '=', $user->id)->get();

						  $admins = array();
						  $employees = array();
						  $customers = array();

						  $role_admin = \Perm::where(['user_id' => $user->id, 'role' => 'admin'])->get();
						  $role_emp = \Perm::where(['user_id' => $user->id, 'role' => 'employee'])->get();
						  $role_cust = \Perm::where(['user_id' => $user->id, 'role' => 'customer'])->get();

                    if(sizeof($role_admin) > 0){
                      foreach($role_admin as $role){
							   $admins[] = $role->company_id;
						    }
						  }

                    if(sizeof($role_emp) > 0){
                      foreach($role_emp as $role){
							   $employees[] = $role->company_id;
						    }
						  }

                    if(sizeof($role_cust) > 0){
                      foreach($role_cust as $role){
							   $customers[] = $role->company_id;
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
                            'userName' => $username // User name
									 ),
                        'role_admin' => $admins,
                        'role_employee' => $employees,
                        'role_customer' => $customers
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

### 
### function sendInvite() {}
### function processInviteAck() {}
### 
### function sendRequest() {}
### function processRequestAck() {}
### 
### 
