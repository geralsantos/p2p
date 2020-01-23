//var getUserMedia = require('getusermedia')
var Peer = require('simple-peer')
var video = document.createElement('video')
    document.body.appendChild(video)
  
    var constraints = { audio: true, video: false };
   /* navigator.getUserMedia_ = (   navigator.getUserMedia
        || navigator.webkitGetUserMedia 
        || navigator.mozGetUserMedia 
        || navigator.msGetUserMedia);*/
        
         async function getMedia() {
            let stream = null;
          
            try {
                console.log("dawdw")
                console.log(navigator.mediaDevices)
              stream = await navigator.mediaDevices.getUserMedia(constraints).then(function(stream2) {

                gotMedia(stream2);
              })
              .catch(function(err) {
               
                console.log(err);
              });
            } catch(err) {
              /* handle the error */
              console.log(err);
              
            }
          }

   // gotMedia();
  
   getMedia();
 function gotMedia (stream) {
              var peer1 = new Peer(
                {
                  initiator: location.hash === '#host',
                  trickle: false,
                  //wrtc: wrtc,
                  stream: stream
                })
              peer1.on('signal', data => {
                //GET MY DATA ON THE
                document.getElementById('yourId').value = JSON.stringify(data)
              })

              document.getElementById('connect').addEventListener('click', function () {
                var otherId = JSON.parse(document.getElementById('otherId').value)
                peer1.signal(otherId)

                alert('connect: '+otherId);
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
                  console.log("yourMessage JSON.stringify(data): "+JSON.stringify(yourMessage));
                  peer1.send(yourMessage)
                })
  }