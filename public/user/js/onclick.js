var form1 = document.getElementById('form1')
var form2 = document.getElementById('form2')
var form3 = document.getElementById('form3')

var next1 = document.getElementById('next1')
var next2 = document.getElementById('next2')
var back1 = document.getElementById('back1')
var back2 = document.getElementById('back2')

next1.onclick = function() {
  form1.style.left = "-2000px";
  form2.style.left = "568px";
}

back1.onclick = function() {
  form1.style.left = "568px";
  form2.style.left = "2000px";
}

next2.onclick = function() {
  form2.style.left = "-2000px";
  form3.style.left = "568px";
}

back2.onclick = function() {
  form2.style.left = "568px";
  form3.style.left = "2000px";
}
