var bossTicksPerMovement = 3;
var bossId = 1;
// -- Start Boss Class --
function Boss (tlimg, trimg, blimg, brimg, x, y) {
    this.x = x;
    this.y = y;
	this.tlimg = tlimg;
	this.trimg = trimg;
	this.blimg = blimg;
	this.brimg = brimg;
	this.id = enemyNum;
	this.bossId = bossId++;
	this.ticks = 0;
	this.dead = false;
	gameBoard[this.y][this.x] = this;
	gameBoard[this.y+1][this.x] = this;
	gameBoard[this.y][this.x+1] = this;
	gameBoard[this.y+1][this.x+1] = this;
}
Boss.prototype.draw = function() {
	gameBoardElements[this.y][this.x].src = this.tlimg;
	gameBoardElements[this.y+1][this.x].src = this.blimg;
	gameBoardElements[this.y][this.x+1].src = this.trimg;
	gameBoardElements[this.y+1][this.x+1].src = this.brimg;
}
Boss.prototype.checkMove = function(x, y) {
	var p = gameBoard[y][x];
	if(p != 0) { // Top Left
		if(p.id == enemyNum && p.bossId != this.bossId) return false;
		if(p.id == wallNum) return false;
		if(p.id == boxNum){
			var canMove = false;
			var d = directionList[0];
			canMove = p.checkMove(d[0], d[1], 1);
			if(!canMove){
				d = directionList[6];
				canMove = p.checkMove(d[0], d[1], 1);
			}
			if(!canMove) return false;
		}
	}
	p = gameBoard[y][x+1];
	if(p != 0) { // Top Right
		if(p.id == enemyNum && p.bossId != this.bossId) return false;
		if(p.id == wallNum) return false;
		if(p.id == boxNum){
			var canMove = false;
			var d = directionList[0];
			canMove = p.checkMove(d[0], d[1], 1);
			if(!canMove){
				d = directionList[2];
				canMove = p.checkMove(d[0], d[1], 1);
			}
			if(!canMove) return false;
		}
	}
	p = gameBoard[y+1][x+1];
	if(p != 0) { // Bottom Right
		if(p.id == enemyNum && p.bossId != this.bossId) return false;
		if(p.id == wallNum) return false;
		if(p.id == boxNum){
			var canMove = false;
			var d = directionList[4];
			canMove = p.checkMove(d[0], d[1], 1);
			if(!canMove){
				d = directionList[2];
				canMove = p.checkMove(d[0], d[1], 1);
			}
			if(!canMove) return false;
		}
	}
	p = gameBoard[y+1][x];
	if(p != 0) { // Bottom Left
		if(p.id == enemyNum && p.bossId != this.bossId) return false;
		if(p.id == wallNum) return false;
		if(p.id == boxNum){
			var canMove = false;
			var d = directionList[6];
			canMove = p.checkMove(d[0], d[1], 1);
			if(!canMove){
				d = directionList[4];
				canMove = p.checkMove(d[0], d[1], 1);
			}
			if(!canMove) return false;
		}
	}
	return true;
}
Boss.prototype.moveBoxes = function(x, y) {
	var p = gameBoard[y][x];
	if(p != 0){
		if(p.id == enemyNum && p.bossId != this.bossId) return false;
		if(p.id == wallNum) return false;
		if(p.id == boxNum){ // Top Left
			var canMove = false;
			var d = directionList[0];
			canMove = p.move(d[0], d[1], 1);
			if(!canMove){
				d = directionList[6];
				canMove = p.move(d[0], d[1], 1);
			} 
			if(!canMove) return false;
		}
	}
	p = gameBoard[y][x+1];
	if(p != 0){
		if(p.id == enemyNum && p.bossId != this.bossId) return false;
		if(p.id == wallNum) return false;
		if(p.id == boxNum){ // Top Right
			var canMove = false;
			var d = directionList[0];
			canMove = p.move(d[0], d[1], 1);
			if(!canMove){
				d = directionList[2];
				canMove = p.move(d[0], d[1], 1);
			}
			if(!canMove) return false;
		}
	}
	p = gameBoard[y+1][x+1];
	if(p != 0){ 
		if(p.id == enemyNum && p.bossId != this.bossId) return false;
		if(p.id == wallNum) return false;
		if(p.id == boxNum){ // Bottom Right
			var canMove = false;
			var d = directionList[4];
			canMove = p.move(d[0], d[1], 1);
			if(!canMove){
				d = directionList[2];
				canMove = p.move(d[0], d[1], 1);
			}
			if(!canMove) return false;
		}
	}
	p = gameBoard[y+1][x];
	if(p != 0){
		if(p.id == enemyNum && p.bossId != this.bossId) return false;
		if(p.id == wallNum) return false;
		if(p.id == boxNum){ // Bottom Left
			var canMove = false;
			var d = directionList[6];
			canMove = p.move(d[0], d[1], 1);
			if(!canMove){
				d = directionList[4];
				canMove = p.move(d[0], d[1], 1);
			}
			if(!canMove) return false;
		}
	}
	return true;
}
Boss.prototype.remove = function() {
	gameBoard[this.y][this.x] = new Flame(this.x, this.y);
	gameBoard[this.y][this.x+1] = new Flame(this.x+1, this.y);
	gameBoard[this.y+1][this.x] = new Flame(this.x, this.y+1);
	gameBoard[this.y+1][this.x+1] = new Flame(this.x+1, this.y+1);
	this.dead = true;
}
Boss.prototype.move = function() {
	if(this.dead) return false;
	this.ticks++;
	if(this.ticks < bossTicksPerMovement) return true;
	this.ticks = 0;
	var c;
	var start;
	do{
		c = Math.floor(Math.random()*8);
	}while(c == 8);
	start = c;
	
	var d; 
	var v;
	var check;
	do{
		c++;
		c %= 8;
		d = directionList[c];
	} while(!(check=this.checkMove(this.x+d[0],this.y-d[1], 1)) && c != start);
	if(check){
		this.moveBoxes(this.x+d[0],this.y-d[1], 1);
		gameBoard[this.y][this.x] = 0;
		gameBoard[this.y+1][this.x] = 0;
		gameBoard[this.y][this.x+1] = 0;
		gameBoard[this.y+1][this.x+1] = 0;
		this.x += d[0];
		this.y -= d[1];
		gameBoard[this.y][this.x] = this;
		gameBoard[this.y+1][this.x] = this;
		gameBoard[this.y][this.x+1] = this;
		gameBoard[this.y+1][this.x+1] = this;
	}else{
		score += 500;
		this.dead = true;
		gameBoard[this.y][this.x] = new Flame(this.x, this.y);
		gameBoard[this.y+1][this.x] = new Flame(this.x+1, this.y);
		gameBoard[this.y][this.x+1] = new Flame(this.x, this.y+1);
		gameBoard[this.y+1][this.x+1] = new Flame(this.x+1, this.y+1);
		return false;
	}
	return true;
}
// -- End Boss Class --
