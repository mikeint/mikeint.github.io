var player;
var deadImg = "img/uncool-smiley.gif"
// -- Start Player Class --
function Player (img) {
    this.x = 7;
    this.y = 7;
	this.moveX = 0;
	this.moveY = 0;
	this.img = img;
	this.id = playerNum;
	gameBoard[this.y][this.x] = this;
}
Player.prototype.draw = function() {
	gameBoardElements[this.y][this.x].src = this.img;
}
Player.prototype.move = function() {
	var lastX = this.x;
	var lastY = this.y;
	this.x += this.moveX;
	this.y -= this.moveY;
	if(gameBoard[this.y][this.x].id == boxNum){
		if(!gameBoard[this.y][this.x].move(this.moveX,this.moveY,99)){
			this.x = lastX;
			this.y = lastY;
		}
	}else if(gameBoard[this.y][this.x].id == wallNum){
		this.x = lastX;
		this.y = lastY;
	}else if(gameBoard[this.y][this.x].id == enemyNum){
		this.moveX = 0;
		this.moveY = 0;
		gameBoard[this.y][this.x].remove();
		if(lastY != this.y || lastX != this.x)
			if(gameBoard[lastY][lastX].id == playerNum)
				gameBoard[lastY][lastX] = 0;
		gameBoard[this.y][this.x] = this;
		this.img = deadImg
		return false;
	}
	this.moveX = 0;
	this.moveY = 0;
	if(gameBoard[lastY][lastX].id == playerNum)
		gameBoard[lastY][lastX] = 0;
	gameBoard[this.y][this.x] = this;
	return true;
}
Player.prototype.setMove = function(x, y) { this.moveX = x; this.moveY = y; }
Player.prototype.moveUp = function() { this.moveY = 1; }
Player.prototype.moveRight = function() { this.moveX = 1; }
Player.prototype.moveDown = function() { this.moveY = -1; }
Player.prototype.moveLeft = function() { this.moveX = -1; }

// -- End Player Class --