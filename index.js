//var getUserMedia = require('getusermedia')
/*
var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);
*/
var Peer = require('simple-peer')
var video = document.createElement('video')
document.body.appendChild(video)
var channel = null;

var constraints = {
  audio: true, video: { 
    mediaSource: "screen",
    width: { max: '1920' },
    height: { max: '1080' },
    frameRate: { max: '1000' }
  }
};
/* 
si el admin de la sala entra por primera vez
1. puede abrir la sala para que los usuarios entren
  1.1 la sala queda abierta por un tiempo limitado 
    1.1.1 esto se va actualizando por cada minuto que el admin de la sala esté activo
2.
si el usuario le da conectar a la sala por primera vez
* */
/* navigator.getUserMedia_ = (   navigator.getUserMedia
     || navigator.webkitGetUserMedia 
     || navigator.mozGetUserMedia 
     || navigator.msGetUserMedia);*/
/*
io.on('connection', (socket) => {
  console.log('user connected');

  socket.on('initiate', () => {
    io.emit('initiate');
  });
  socket.on('offer', (data) => {
    socket.broadcast.emit('offer', data);
  });

});
*/
async function getMedia() {
  let stream = null;

  try {
    console.log("dawdw")
    if (location.hash === '#host') {
      navigator.getUserMedia_ = navigator.mediaDevices.getDisplayMedia(constraints)

    }else{
      navigator.getUserMedia_ = navigator.mediaDevices.getUserMedia({audio: true})
    }
    stream = await navigator.getUserMedia_.then(function (stream2) {
     
    
      /*capturar pantall*/// stream = await navigator.mediaDevices.getDisplayMedia(constraints).then(function(stream2) {
      gotMedia(stream2);
      console.log(stream2);
    })
      .catch(function (err) {

        console.log(err);
      });
  } catch (err) {
    /* handle the error */
    console.log(err);

  }
}

// gotMedia();

getMedia();
function gotMedia(stream) {
  var objs = {
    initiator: location.hash === '#host',
    trickle: false,
    //wrtc: wrtc,
    stream: stream,
    config: stunServerConfig
  };
console.log(stream)
const stunServerConfig = {  
  iceServers: [{  
    url: "turn:numb.viagenie.ca",  
    username: "geral",  
    credential: "123"  
  }]  
};

  var peer1 = new Peer( location.hash === '#host' ? objs: {
    trickle: false,
  } )
  peer1.on('signal', data => {
    //GET MY DATA ON THE
    let dat = JSON.stringify(data);
    if (location.hash === '#host') {
      console.log(dat);
      localStorage.setItem("stream", dat);
      //channel = localStorage.getItem("stream");
     }else{
      channel = localStorage.getItem("stream");
      localStorage.setItem("cliente",dat);
      console.log((channel))
     }
    document.getElementById('yourId').value = dat
    
    //channel = JSON.stringify(data);
  })
  /*function addMedia (stream) {
    peer1.addStream(stream) // <- add streams to peer dynamically
  }*/
  document.getElementById('connect').addEventListener('click', function () {
    //var otherId = JSON.parse(document.getElementById('otherId').value)
    /*navigator.getUserMedia({  audio: true }, addMedia, (res) => {
      console.log(res);
    })*/
    let dat = localStorage.getItem("stream");
    if (location.hash === '#host') {
      let cl = localStorage.getItem("cliente");
      peer1.signal(JSON.parse(cl))
    }else{
      localStorage.setItem("cliente[]",dat);
      peer1.signal(JSON.parse(dat))
    }

   // alert('connect: ' + dat);
  })
  peer1.on('data', data => {

    document.getElementById('messages').textContent += data + '\n'
    console.log('got a message from peer1: ' + data)
  })

  peer1.on('stream', stream => {
    // got remote video stream, now let's show it in a video tag
    var video = document.querySelector('video')

    if ('srcObject' in video) {
      video.srcObject = stream
    } else {
      video.src = window.URL.createObjectURL(stream) // for older browsers
    }

    video.play()
  })

  document.getElementById('send').addEventListener('click', function () {
    var yourMessage = document.getElementById('yourMessage').value
    console.log("yourMessage JSON.stringify(data): " + JSON.stringify(yourMessage));
    peer1.send(yourMessage)
  })
}