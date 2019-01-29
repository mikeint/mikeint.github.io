// -- Start Flame Class --
function Flame (x, y) {
    this.x = x;
    this.y = y;
	this.img = "img/flame.gif";
	this.id = enemyNum;
	gameBoard[y][x] = this;
}
Flame.prototype.draw = function() {
	gameBoardElements[this.y][this.x].src = this.img;
}
// -- End Flame Class --