// -- Start Box Class --
function Box (img, x, y) {
    this.x = x;
    this.y = y;
	this.img = img;
	this.id = boxNum;
	gameBoard[y][x] = this;
}
Box.prototype.draw = function() {
	gameBoardElements[this.y][this.x].src = this.img;
}
Box.prototype.checkMove = function(x,y,recurse) {
	if(recurse == 0) return false;
	var next = gameBoard[this.y - y][this.x + x];
	if(next == 0){
		return true;
	}else if(next.id == boxNum){
		return next.checkMove(x, y, recurse-1);
	} 
	return false;
}
Box.prototype.move = function(x,y,recurse) {
	if(recurse == 0) return false;
	var next = gameBoard[this.y - y][this.x + x];
	if(next == 0){
		gameBoard[this.y - y][this.x + x] = this;
		gameBoard[this.y][this.x] = 0;
		this.x += x;
		this.y -= y;
		return true;
	}else if(next.id == boxNum){
		if(next.move(x,y,recurse-1)){
			gameBoard[this.y - y][this.x + x] = this;
			gameBoard[this.y][this.x] = 0;
			this.x += x;
			this.y -= y;
			return true;
		}
	} 
	return false;
}
// -- End Box Class --