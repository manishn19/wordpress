<?php
$client_id = '******************';
$client_secret = '*********************';
$redirect_uri = 'http://domain.com/linkedin-redirect';
$code = $_GET['code'];
if(isset($_GET['code'])){
	/* Get User Access Token */
	$method_ = 1; // method = 1, because we want POST method
	$url_ = "https://www.linkedin.com/uas/oauth2/accessToken";
	$header_ = array( "Content-Type: application/x-www-form-urlencoded" );
	$data_ = http_build_query( array(
				"client_id" => $client_id,
				"client_secret" => $client_secret,
				"redirect_uri" => $redirect_uri,
				"grant_type" => "authorization_code",
				"code" => $code
			));
	$json_ = 1; // json = 1, because we want JSON response
	$get_access_token = Linkedin_HTTP($method_, $url_, $header_, $data_, $json_);
	$access_token = $get_access_token['access_token']; // user access token
	/* Get User Info */
	$method = 0; // method = 0, because we want GET method
	$url = "https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,phoneNumber,emailAddress)"; // read about field: https://developer.linkedin.com/docs/fields/basic-profile
	$header = array("Authorization: Bearer $access_token");
	$data = 0; // data = 0, because we do not have data
	$json = 1; // json = 1, because we want JSON response
	$user_info = Linkedin_HTTP($method, $url, $header, $data, $json);
	$_SESSION['user_info'] = $user_info; // save user info in session
	// print the array with user info
	echo '<pre>';
	print_r($_SESSION['user_info']);
	// you can store value on your database....
}
// function to call linkedin APIs
function Linkedin_HTTP($method, $url, $header, $data, $json){
    if( $method == 1 ){
        $method_type = 1; // 1 = POST
    }else{
        $method_type = 0; // 0 = GET
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    if( $header !== 0 ){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
   curl_setopt($curl, CURLOPT_POST, $method_type);
 
    if( $data !== 0 ){
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    $response = curl_exec($curl);
    if( $json == 0 ){
    	$json = $response;
    }else{
    	$json = json_decode($response, true);
    }
    curl_close($curl);
    return $json;
}
?>
<div class="page-container">
	<button class="lnBtn">Sign in with Linkedin</button>
</div>
<script>
// while clicking on 'Sing in with Linkedin' btn
jQuery('.lnBtn').click(function(){
	window.location.href = 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id=<?php echo $client_id; ?>&redirect_uri=<?php echo $redirect_uri; ?>&state=aRandomString&scope=r_liteprofile%20r_emailaddress%20w_member_social';
});
</script>