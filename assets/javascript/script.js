function Solograph(callback, after) {
  this.local = new Array();
  this.callback = callback;
  this.after = after;
}

Solograph.prototype.initialize = function(data) {
  this.local = data.split(";");
  this.after.call(this, function(){
      window.setTimeout(this.tick.bind(this), 1000);
  });
};

Solograph.prototype.appendLeadingZero = function(n, width, z) {
  z = z || '0';
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
};

Solograph.prototype.tick = function() {
  var now = this.time();
  this.callback(now.getHours(), now.getMinutes(), now.getSeconds());
  if(now.getSeconds() == 0){
    this.update();
  }else{
    window.setTimeout(this.tick.bind(this), 1000);
  }
};

Solograph.prototype.time = function() {
  var local = new Date();
  var utc = local.getTime() + (local.getTimezoneOffset() * 60000);
  return new Date(utc + (3600000* parseInt(this.local[1])));
};

Solograph.prototype.update = function(){
    var xhr = new XMLHttpRequest();
    var s = this;
    xhr.open('GET', 'index.php?update=true');
    xhr.onload = function(){
      if(xhr.status === 200){
        s.initialize(xhr.responseText);
      }else{
        s.tick();
      }
    };
    xhr.send();
};

window.solograph = new Solograph(function(h, m, s) {
  var sec_bar = document.querySelectorAll("#seconds circle")[0];
  sec_bar.style.strokeDashoffset = s * (-26.183);

  var inner = document.querySelectorAll("#inner")[0];
  inner.style.transform = "rotate(" + (m * 6) + "deg)";

  var outer = document.querySelectorAll("#outer")[0];
  outer.style.transform = "rotate(" + (h * 30) + "deg)";

  for (var i = 0; i < 5; i++) {
    var r = Math.floor(Math.random() * 10);
    var z =   (300 - Math.floor(Math.random() * 600));
    var flare = document.querySelectorAll(".flare")[i];
    if(s % 5 == i) {
      flare.style.opacity = r / 5;
      flare.style.transform = "scale(" + r +") translate(" + z + "%, " + z + "%)";
    }
  }
  document.querySelectorAll(".location__time")[0].innerHTML = this.appendLeadingZero(h, 2) + ":" + this.appendLeadingZero(m, 2) + ":" + this.appendLeadingZero(s, 2);
}, function(tick) {
  document.querySelectorAll(".location__name")[0].innerHTML = this.local[2];
  document.getElementsByTagName("body")[0].style.background="rgb(" + this.local[4] + ")";
  document.getElementsByTagName("body")[0].style.background="-moz-linear-gradient(45deg, rgb(" + this.local[5] + "), rgb(" + this.local[6] + "))";
  document.getElementsByTagName("body")[0].style.background="-webkit-linear-gradient(45deg, rgb(" + this.local[5] + "), rgb(" + this.local[6] + "))";
  document.getElementsByTagName("body")[0].style.background="linear-gradient(45deg, rgb(" + this.local[5] + "), rgb(" + this.local[6] + "))";
  document.getElementById("gradient").children[0].style.stopColor="rgb(" + this.local[4] + ")";
  for (var i = 0; i < 5; i++) {
    var flare = document.querySelectorAll(".flare")[i];
    flare.style.transform = "translate(0px, 0px)";
  }
  tick.call(this);
});
