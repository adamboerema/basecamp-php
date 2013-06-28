<?php
	
include "src/basecamp.php";

$auth = array(
	"account"  => "YOUR_ACCOUNT",
	"api_key"  => "YOUR_API_KEY",
	"user"     => "YOUR_USERNAME",
	"password" => "YOUR_PASSWORD"
);

$basecamp = new Basecamp($auth);
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<style>
	body { font-family: helvetica, sans-serif; margin: 30px; }
	h1 { display: inline-block; padding: 5px 10px;}
	.archived, .active, .inactive { 
		color: white; 
		display: inline-block;
		padding: 5px 10px;
		text-align: center;
	}
	.active   { background: green; }
	.inactive { background: red; }
	.archived { background: #ccc; color: #555; }
</style>
</head>

<body>

<?php if( isset($_GET["id"]) ) { ?>
	<?php $project = $basecamp->getProject($_GET["id"]); ?>

	<h1>Project Files for <?= $project->name ?></h1>
	<ol>
		<?php foreach($basecamp->getFiles($_GET["id"]) as $file): ?>
			<li>
				<a href="<?= $file->{'download-url'} ?>"><?= $file->name ?></a>
			</li>
		<?php endforeach; ?>
	</ol>

<?php } else { ?>

	<?php foreach($basecamp->getProjects() as $project): ?>
		<h1><a href="?id=<?= $project->id ?>"><?= $project->name ?></a></h1>
		<div class="<?= $project->status ?>"><?= $project->status ?></div>
		<hr>
	<?php endforeach; ?>

<?php } ?>

</body>
</html>