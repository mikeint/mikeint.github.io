// -- Start Wall Class --
function Wall (img, x, y) {
	this.x = x;
	this.y = y;
	this.img = img;
	this.id = wallNum;
	gameBoard[y][x] = this;
}
Wall.prototype.draw = function() {
	gameBoardElements[this.y][this.x].src = this.img;
}
// -- End Wall Class --
