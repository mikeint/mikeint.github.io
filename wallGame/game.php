<?php 
	session_save_path("sesh");
	session_start();
	if(!isset($_SESSION['username']) || !isset($_COOKIE['loggedin']) || $_COOKIE['loggedin'] == 0){
		echo "Must be logged in to play this game. Redirecting to login page in 5 seconds.";
		echo "<br/>Or <a href='login.php'>Click Here</a>";
		header( "refresh:5;url=https://cs.utm.utoronto.ca/~sansonem/a1/login.php" );
		die();		
	}
	if(isset($_POST['postScore'])){
		$dbconn = pg_connect("host=cslinux.utm.utoronto.ca port=5432 dbname=sansonem user=sansonem password=69447")
			or die('Could not connect: ' . pg_last_error());
		if(isset($_COOKIE['updateScore']) && $_COOKIE['updateScore'] == 1){
				pg_prepare($dbconn, "addScore", "INSERT INTO score (name, score) VALUES ($1, $2)");
				$sql=pg_execute($dbconn, "addScore", array($_SESSION['username'], $_REQUEST['postScore']));
		}
		setcookie("updateScore", 0);
		$_COOKIE['updateScore']=0;
	}
?>
<html>
<head> <meta charset="utf-8"> 
<title> Login to THE GAME </title>
<script src="game_scripts/player.js"></script>
<script src="game_scripts/enemy.js"></script>
<script src="game_scripts/box.js"></script>
<script src="game_scripts/wall.js"></script>
<script src="game_scripts/blank.js"></script>
<script src="game_scripts/flame.js"></script>
<script src="game_scripts/boss.js"></script>
<script type="text/javascript">
var enemyTicksPerMovement = 3;
var score = 0;
var playerNum = 1;
var enemyNum = 2;
var boxNum = 3;
var wallNum = 4;
var gameover = false;
var player;
var gameSizeX = 15;
var gameSizeY = 15;
var gameBoardElements = new Array();
var gameBoard = new Array();
var enemyList = [];
var directionList = [[0,1],[1,1],[1,0],[1,-1],[0,-1],[-1,-1],[-1,0],[-1,1]];
var nextLevelBool = false;
var level = 1;
function moveAllEnemies(){
	var oneAlive = false;
	var c = enemyList.length;
	var i;
	for(i=0; i < c; i++){
		oneAlive |= enemyList[i].move();
	}
	return oneAlive;
}
function gameOverScreen(){
	var gg = document.getElementById('gameOver');
	gg.innerHTML = '<span style="font:bold 20px Georgia;">Game Over!</span><br/><br/><div style="text-align: left;"><span style="font:16px Georgia;">Congratulations <?php echo $_SESSION['username']; ?></span></div> <div style="text-align: left;"><span style="font:16px Georgia;">Total Score:</span><span id="score"></span></div> <div style="float:right"> <input name="postScore" id="postScore" type="hidden" value="0"/><input type="submit" value="Submit Score" onclick="this.form.submit()"/> </div>';
	gg.className = 'gameOver';
	var s = document.getElementById('score');
	var s2 = document.getElementById('postScore');
	s.innerHTML = score;
	s2.value = score;
	document.cookie="updateScore=1";
}
function drawGameBoard(){
	var x;
	var y;
	for(x=0;x<gameSizeX;x++){
		for(y=0;y<gameSizeY;y++){
			if(gameBoard[y][x] != 0)
				gameBoard[y][x].draw();
			else{
				drawBlank(x,y);
			}
		}
	}
}
function gameLoop() {
	if(nextLevelBool || gameover) return;
	if(!moveAllEnemies()){
		nextLevel(1);
		return;
	}
	if(!player.move()){
		gameover = true;
		stopTimer();
	}
	drawGameBoard();
	if(gameover){
		gameOverScreen();
	}
}
function keyPress(event) {
	var key = (event.keyCode);
	switch (key) {
		case 38:
		case 87:
			player.moveUp();
			event.preventDefault();
			break;
		case 83:
		case 40:
			player.moveDown();
			event.preventDefault();
			break;
		case 65:
		case 37:
			player.moveLeft();
			event.preventDefault();
			break;
		case 68:
		case 39:
			player.moveRight();
			event.preventDefault();
			break;
	}
}
function preventOnClick(e){
	e.preventDefault();
}
function fillWithBoxes(num, boxImg){
	var i;
	for(i=0; i < num; i++){
		var x;
		var y;
		do{
			x = Math.floor(Math.random()*(gameSizeX-2)+1);
			y = Math.floor(Math.random()*(gameSizeY-2)+1);
		}while(gameBoard[y][x] != 0);
		new Box(boxImg, x, y);
	}
}
function fillWithEnemies(num, enemyImg){
	enemyList = [];
	var i;
	var bossLevel = false;
	if((level % 5) == 0){
		bossLevel = true;
		num = (level / 5);
	}
	for(i=0; i < num; i++){
		var x;
		var y;
		do{
			do{
				x = Math.floor(Math.random()*(gameSizeX-2)+1);
			}while(Math.abs(7-x) <=2);
			do{
				y = Math.floor(Math.random()*(gameSizeY-2)+1);
			}while(Math.abs(7-y) <=2);
		}while(gameBoard[y][x] != 0 
			|| (bossLevel && (gameBoard[y+1][x] != 0 || gameBoard[y][x+1] != 0 || gameBoard[y+1][x+1] != 0) ));

		if(bossLevel)
			enemyList.push(new Boss(bossImgTL,bossImgTR,bossImgBL,bossImgBR, x, y));
		else
			enemyList.push(new Enemy(enemyImg, x, y));
	}
}
function addGameBoardHTML(){
	var game = document.getElementById("game");
	var html = "<font size='4'><b><center>Level <span id='level'></span></center></b></font><table cellspacing=\"0\" cellpadding=\"0\">";
	var x;
	var y;
	for(y=0; y<gameSizeY; y++){
		html+="<tr>";
		for(x=0; x<gameSizeX; x++){
			html+="<td><img id=\"" + x.toString() +","+ y.toString() + "\" src="+blankImg+" width=\"30\" height=\"30\" border=\"0\" style=\"border:none;outline: none;\"/\>\</td\>";
		}
		html+="</tr>";
	}
	game.innerHTML = html;
}
function initGameBoard(){
	var x;
	var y;
	for(y=0; y<gameSizeY; y++){
		gameBoard[y]=new Array();
		gameBoardElements[y]=new Array();
		for(x=0; x<gameSizeX; x++){
			gameBoard[y][x] = 0;
			gameBoardElements[y][x] = document.getElementById(x.toString() +","+ y.toString() );
		}
	}
}
function initWalls(wallImg){
	var x;
	var y;
	for(x=0;x<gameSizeX;x++){
		gameBoard[0][x] = new Wall(wallImg, x, 0);
		gameBoard[gameSizeY-1][x] = new Wall(wallImg, x, gameSizeY-1);
	}
	for(y=1;y<gameSizeY-1;y++){
		gameBoard[y][0] = new Wall(wallImg, 0, y);
		gameBoard[y][gameSizeY-1] = new Wall(wallImg, gameSizeX-1, y);
	}
}
function clearGameBoard(){
	for(x=1;x<gameSizeX-1;x++){
		for(y=1;y<gameSizeY-1;y++){
			gameBoard[y][x] = 0;
		}
	}
}
function nextBossHACK(){
	nextLevelBool = true;
	nextLevel(5-(level % 5));
}
function nextLevel(next){
	nextLevelBool = true;
	clearGameBoard();
	level += next;
	player = new Player(playerImg);
	document.getElementById("level").innerHTML = level;
	if((level % 5) == 0)
		fillWithBoxes(70, boxImg);
	else
		fillWithBoxes(70, boxImg);
	fillWithEnemies(level+1, enemyImg);
	drawGameBoard();
	nextLevelBool = false;
}
var enmyImg;
var boxImg;
var playerImg;
var wallImg;
var blankImg;
var bossImgTL;
var bossImgTR;
var bossImgBL;
var bossImgBR;
function onLoad(){
	blankImg = document.getElementById("blankId").src;
	enemyImg = document.getElementById("enemyId").src;
	boxImg = document.getElementById("boxId").src;
	wallImg = document.getElementById("wallId").src;
	playerImg = document.getElementById("playerId").src;
	bossImgTL = document.getElementById("bossTLId").src;
	bossImgTR = document.getElementById("bossTRId").src;
	bossImgBL = document.getElementById("bossBLId").src;
	bossImgBR = document.getElementById("bossBRId").src;
	addEventListener("keydown", keyPress);
	addGameBoardHTML();
	document.getElementById("level").innerHTML = level;
	initGameBoard();
	player = new Player(playerImg);
	initWalls(wallImg);
	fillWithEnemies(level+1, enemyImg);
	fillWithBoxes(70, boxImg);
	gameover = false;
	score = 0;
	drawGameBoard();
}
var timer;
var pause = false;
function startTimer(){
	timer=setInterval(function () {gameLoop();}, 200);
	document.getElementById("pause").src = "img/pause.png";
}
function stopTimer(){
	clearInterval(timer);
	document.getElementById("pause").src = "img/play.png";
}
function togglePause(){
	if(pause)
		startTimer();
	else
		stopTimer();
	pause = !pause;
}
</script>
<link rel="stylesheet" type="text/css" href="game.css">
</head> 
	<body onClick="preventOnClick(event);"  background="img/background.png" onload="onLoad();<?php if(!isset($_POST['postScore'])) echo 'startTimer();'; ?>">
	<div style="float:left;">
	<div id="game"></div>
	</div>
	<form id="gameOverForm" name="input" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><div id="gameOver"></div></form>
	<div style="padding:10px;float:left;">
		<div>
		<table align="center" style="textalign:center">
		<tr>
		<td><td/>
		<td class="legend" style="padding-right:5px;">Blank</td>
		<td class="legend" style="padding-right:5px;">Player</td>
		<td class="legend" style="padding-right:5px;">Enemy</td>
		<td class="legend" style="padding-right:5px;">Boxes</td>
		<td class="legend" style="padding-right:5px;">Wall</td>
		<td class="legend" style="padding-right:5px;">Boss</td>
		</tr>
		<tr>
		<td><img id="pause" onClick="togglePause();preventOnClick(event);" width="30" height="30" src="img/pause.png"/><td/>
		<td class="legend"><img id="blankId" style="border:1px solid #000000;" width="30" height="30" src="img/blank.png"/></td>
		<td class="legend"><img id="playerId" style="border:1px solid #000000;" width="30" height="30" src="img/player.png"/></td>
		<td class="legend"><img id="enemyId" style="border:1px solid #000000;" width="30" height="30" src="img/devil.png"/></td>
		<td class="legend"><img id="boxId" style="border:1px solid #000000;" width="30" height="30" src="img/box.png"/></td>
		<td class="legend"><img id="wallId" style="border:1px solid #000000;" width="30" height="30" src="img/wall.png"/></td>
		<td class="legend">
		<table cellspacing="0" cellpadding="0"><tr>
		<td><img id="bossTLId" style="border-top:1px solid #000000;border-left:1px solid #000000;" width="30" height="30" src="img/boss_tl.png"/></td>
		<td><img id="bossTRId" style="border-top:1px solid #000000;border-right:1px solid #000000;" width="30" height="30" src="img/boss_tr.png"/></td></tr>
		<tr><td><img id="bossBLId" style="border-left:1px solid #000000;border-bottom:1px solid #000000;" width="30" height="30" src="img/boss_bl.png"/></td>
		<td><img id="bossBRId" style="border-bottom:1px solid #000000;border-right:1px solid #000000;" width="30" height="30" src="img/boss_br.png"/></td></tr>
		</table>
		</td></tr>
		</table>
		</div>
		<div style="padding-top:25px;">
		<img src="img/arrows.png" usemap="#imagemap"/>
		<map name="imageMap">
		<area shape="poly" coords="147,150,202,205,263,178,300,209,300,94,262,123,201,94" onClick="player.setMove(1,0);preventOnClick(event);"/>
		<area shape="poly" coords="147,150,93,96,31,123,0,92,0,208,32,178,93,208" onClick="player.setMove(-1,0);preventOnClick(event);"/>
		<area shape="poly" coords="147,150,201,94,171,30,205,0,92,0,121,32,93,96" onClick="player.setMove(0,1);preventOnClick(event);"/>
		<area shape="poly" coords="147,150,93,208,123,270,89,300,204,300,172,266,203,206" onClick="player.setMove(0,-1);preventOnClick(event);"/>
		<area shape="poly" coords="0,300,0,208,33,178,93,208,123,270,89,300" onClick="player.setMove(-1,-1);preventOnClick(event);"/>
		<area shape="poly" coords="0,0,0,93,31,123,93,96,121,32,92,0" onClick="player.setMove(-1,1);preventOnClick(event);"/>
		<area shape="poly" coords="300,0,205,0,171,30,201,94,262,123,300,94" onClick="player.setMove(1,1);preventOnClick(event);"/>
		<area shape="poly" coords="300,209,263,178,203,205,172,266,204,300,300,300" onClick="player.setMove(1,-1);preventOnClick(event);"/>
		</map>
	</div>
	</div>
	<div style="clear:both">
	<a href="" onClick="nextBossHACK();">HACK: Goto Next Boss Level</a>
	</div>
<?php 
	if(isset($_POST['postScore'])){
		$sql = pg_query("SELECT name, score FROM score ORDER BY score DESC LIMIT 10");
		echo "<div class='highScores' id='highScores'>
		<span style='font:bold 20px Georgia;'>Highscores</span>";
		
		echo "<div style='height:235px'><table>
		<tr><th class='highscoreRow' style='width:200px'>Name</th>
		<th class='highscoreRow' style='width:75px'>Score</th></tr>";
		while($userINPUTArray = pg_fetch_array($sql))
		{
			echo "<tr><td class='highscoreRow'>" . $userINPUTArray[0] . "</td><td class='highscoreRow'>" .$userINPUTArray[1] . "</td></tr>";
		}
		echo "</table></div>
		<div style='position: relative;bottom: -10px;'>
		<input type='button' onclick='startTimer();document.getElementById(\"highScores\").style.display = \"none\";' value='Play Again'/>
		</div></div>";
		pg_close($dbconn);
	}
?>
	</body> 
</html> 

