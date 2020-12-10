<?php
use Goutte\Client;
if (isset($_POST["git_email"]) && isset($_POST["git_pwd"]) && !empty($_POST["git_pwd"]) && !empty($_POST["git_pwd"])) {

require_once("vendor/autoload.php");
require_once("Goutte/Goutte/Client.php");
$client = new Client();

//intialise the crawler with request type get and the login url
$crawler = $client->request('GET', 'https://github.com/login', [
'allow_redirects' => true
]);
$form = $crawler->selectButton('Sign in')->form();
$form->setValues(['login' => $_POST["git_email"], 'password' => $_POST["git_pwd"]]);

// submit the form
$crawler = $client->submit($form);
$username = "";
$crawler->filter('meta')->each(function ($node) {
global $username;
if(trim($node->attr("name")) == "octolytics-actor-login"){
$username = ($node->attr("content"));
return;
}
});

$crawler = $client->request('GET', 'https://github.com/'.$username.'?tab=repositories');

$crawler->filter('li.source a')->each(function ($node) {
if(is_numeric($node->text()) === false){
echo $node->text();
echo "<br/>";
}
});
} else {
?>

<form method="POST">
<div class="form-group">
<h1>Github Repositories scraper</h1>
<label for="git_email">Email address</label>
<input type="email" class="form-control" id="git_email" name="git_email" placeholder="Enter email">
</div>
<div class="form-group">
<label for="git_pwd">Password</label>
<input type="password" class="form-control" id="git_pwd" name="git_pwd" placeholder="Password">
</div>
<button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php
}
?>