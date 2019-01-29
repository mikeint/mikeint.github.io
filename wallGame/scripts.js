function inputFocus(i){
	if(i.value==i.defaultValue){ i.value=""; i.style.color="#000000"; }
}
function inputBlur(i){
	if(i.value==""){ i.value=i.defaultValue; i.style.color="#DCDCDC"; }
}


function hideDiv() {  
	setTimeout(function(){  
	document.getElementById('signUp').style.display = "none";
    }, 600);
	$('#tableMustFade').fadeOut(600);
	setTimeout(function(){
		document.getElementById('signUp').style.width="1px";
    }, 500);
	setTimeout(function(){      
		document.getElementById('signUp').style.height="1px";
    }, 500); 
}
function showDiv() { 
	$('#tableMustFade').delay(1000).fadeIn(600);
	setTimeout(function(){  
	document.getElementById('signUp').style.display = "block";
    }, 100);
	setTimeout(function(){      
    	document.getElementById('signUp').style.height="200px";
    }, 200);
	setTimeout(function(){      
  	    document.getElementById('signUp').style.width="500px"; 
    }, 300);
} 

$(document).keyup(function(event) {
	if(event.which === 27) {
		$('#signUp').fadeOut(200); 
	}
});
