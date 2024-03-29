<?php $Bar_title = "This";

$str = "<style>
/*customized styles*/
div.bar-sticky,
div.bar-modal{
  font-family: Arial, Helvetica, sans-serif;
}
.bar-sticky{
  background-color: #FF4081;
  color: white;
  position: fixed;
  top: 0px;
  width: 100% !important;
  left: 0px;
  height: 109px;
}

/* The Modal (background) */
.bar-modal {
    z-index:5000 !important; 
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transform: scale(1.1);
    transition: visibility 0s linear 0.25s, opacity 0.25s 0s, transform 0.25s;
}

/* Modal Content */
.bar-modal-content {
    position: absolute;
    left: 35%;
    top: 10%;
    padding: 20px;
    border: 1px solid #888;
    border-radius: 0.5rem;
    background-color: white;
    width: 40%;
    max-width: 500px;  
}


.show-modal {
    opacity: 1;
    visibility: visible;
    transform: scale(1.0);
    transition: visibility 0s linear 0s, opacity 0.25s 0s, transform 0.25s;
}


/* The Close Button */
.bar-close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.bar-close:hover,
.bar-close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.bar-title-block{
  width: 70%;
  text-align: left;
}
.bar-show-modal-block{
    width: 20%;
}
#myBtn{
    background: white;
    color: #FF4081;
    border: none;
    width: 145px;
    height: 35px;
    border: 2px solid !important;
    border-radius: 9px !important;
}

/*flex box*/
.flex-container {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
}

.flex-container > div {
  margin: 10px;
  line-height: 109px;
}


.share-icon{
    width: 50px;
    height: 50px;
    margin-right: 15px;
    margin-top: 15px;
}
.share-icon:hover{
    cursor: pointer;
}
.bar-modal-header{
  background-color: #FF4081;
  position: absolute;
  top: 0px;
  left: 0px;
  width: 100%;
  display: flex;
  flex-direction: row;
}
.btn-dismiss{
    border-radius: 50%;
    max-width: 33px;
    padding: 2px 8px;
    vertical-align: top;
    background: #FF4081;
    border: none;
    color: white;
    font-size: 19px;
    box-shadow: none;
}
.btn-dismiss:hover{
  color: black !important;
  background: #FF4081;
  box-shadow: none;
}


/*facebook share style*/
#fb-share-button {
    padding: 0px 2px;
}

#fb-share-button:hover {
    cursor: pointer;
}

/*Gmail Share Style*/
#compose-button:hover{
    cursor: pointer;
    text-decoration: none;
}


/*Responsive mobile */
/*@media only screen and (max-width: 700px) {
}*/

@media only screen and (max-width: 700px) {
  .bar-title-block{
    flex: 0.5;
    margin: auto;
    margin-left: -30px;
  }
  .bar-title{
    text-align: left;
    font-size: 15px;
    line-height: 14px;
  }
  #myBtn{
    width: 115%;
    font-size: 14px;
    margin-left: 30px;
  }
  
}

</style>
<div id = 'iamtest'>I am test</div>
<div class=\"bar-sticky flex-container\" id=\"bar1\">
  <div style=\"flex:0.1;\"></div>
  <div class=\"bar-title-block\" style=\"flex:0.5;margin: auto;\">
    <!-- Tell Your Friends About Us & Get FREE Gift Cards! -->
    <h2 class=\"bar-title\" id=\"bar-title-content\" style=\"text-align: left;\">$Bar_title</h2>
  </div>
  <div class=\"bar-btn-block btn-show-modal-block\" style=\"flex: 0.3;margin: auto;text-align: right;\">
    <button id=\"myBtn\" class=\"btn btn-primary\">Learn More</button>
  </div>
  <div class=\"bar-dismiss\" id=\"bar-dismiss\" style=\"flex: 0.1; color: white; text-align: right; margin-right: 13px; margin-top: 5px;\">
    <button id=\"dismiss-btn\" class=\"btn-dismiss\">X</button>
  </div>
</div>

<!-- The Modal -->
  <div id=\"barModal\" class=\"bar-modal\">

    <!-- Modal content -->
    <div class=\"bar-modal-content\">
      
      <div class=\"bar-modal-header\">
        <h4 class=\"modal-title\" style=\"flex: 0.9; padding-left: 10px; color: white;\">Share With Your Friends</h4>
        <div class=\"bar-close\" id=\"bar-close\" style=\"flex: 0.1; color: white; text-align: right; margin-right: 13px; margin-top: 5px;\">x</div>
      </div>
      <div class=\"bar-modal-body\">
        <div class=\"bar-promo-block\" id=\"copy-block\" style=\"margin-top: 4%;\">

          <div class=\"bar-description-content\">
            <!-- <h4>Only Three steps</h4>
            <p>1. Copy the link above</p>
            <p>2. Share to your family and friends via social media or Email!</p>
            <p>3. Get One FREE Gift Card when people register using your Promotion Code ! </p> -->
          </div>

          <h4 style=\"clear: both; float: left;\">Get Another FREE Week and Let Your Friend Enjoy 90% Off !</h4>
          <p>Share this On</p>
          <div class=\"img-share\" id=\"img-share\" style=\"clear: both; margin-bottom: 20px;\">
            <img src='https://s7.postimg.cc/tcmsovhxz/Face_Book.png' border='0' alt='Face_Book' class=\"share-icon\" id=\"fb-share-button\"/>

            <img src='https://s8.postimg.cc/tcpp1r1zl/Twitter.png' border='0' alt='Ins' class=\"share-icon\" id=\"twitter-share-btn\" />
            
            <a target=\'_blank\' class id=\"compose-button\"><img src=\'https://s7.postimg.cc/xkm6bre0r/Gmail.png\' border=\'0\' alt=\'Gmail\' class=\"share-icon\"/>
            </a>
            
            <a target=\'_blank\' href=\"mailto:?bcc=&subject=Email Subject is here&body=Email Content is here.\" rel=\"EMAIL\"><img src='https://s7.postimg.cc/mz2az67iz/Outlook.png' border='0' alt='Outlook' class=\"share-icon\"/></a>

            <a href=\"https://www.instagram.com/\" target='_blank'><img src='https://s7.postimg.cc/amzeloz3r/Ins.png' border='0' alt='Ins' class=\"share-icon\"/></a>
          </div> 

          <p>Or copy-paste this link</p>
          <div class=\"mdl-textfield mdl-js-textfield\">
            <input class=\"\" type=\"text\" id=\"promolink\" value=\"promo link\" style=\"width: 30%;\">
            <button type=\"button\" id=\"btn-copy-promo\">Copy Link</button>
          </div>
        </div>

      </div>
      <div class=\"bar-modal-footer\">
        
      </div>
    </div>


<!-- Modal Ends -->


<script>

  // Twitter btn style
  var twButton = document.getElementById(\"twitter-share-btn\");
  var url1 = \"http%3A%2F%2Flocalhost%2Fqbank%2Fmy%2F&ref_src=twsrc%5Etfw&text=\";
  var url2 = \"Join%20Me%20on%20NurseAchieve%20and%20Use%20This%20Link%20to%20Get%20an%20extra%20discount%20!!!&tw_p=tweetbutton&url=\";
  var url3 = \"https%3A%2F%2Fnurseachieve.com%2Fregister\";

  twButton.addEventListener('click', function() {
      window.open('https://twitter.com/intent/tweet?original_referer=' + url1 + url2 + url3,
          'twitter-share-dialog',
          'width=800,height=600'
      );
      return false;
  });

  // Allocate space for Bars, height 105px
  $('#page').attr('style','margin-top: 1%; position: absolute; width: 96%; right: 0px; left: 0px;');
  $('header.navbar.navbar-fixed-top.moodle-has-zindex').attr('style','margin-top: 105px; position: relative;');

  // Get the modal
  var modal = document.getElementById('barModal');

  $('#myBtn').click(function(){
    $('#barModal').addClass('show-modal');
  });

  // When the user clicks on <span> (x), close the modal
  $('#bar-close').click(function(){
    $('#barModal').removeClass('show-modal');
  });

  // Copy promotion link button
  $('#btn-copy-promo').click(function(){
    var copyText = document.getElementById('promolink');
    copyText.select();
    document.execCommand('Copy');
    alert('Promotion Link has been copied to your clipboard');
  });

  // Dimiss Bar
  $('#dismiss-btn').click(function(){
    $('#bar1').html(\"\");
    $('#bar1').attr({
      class:\"\",
      id:\"\"
    });
    $('#page').attr('style',\"\");
    $('header.navbar.navbar-fixed-top.moodle-has-zindex').attr('style',\"\");
  });
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
        $('#barModal').removeClass('show-modal');
    }
  }



  // ============================================
  // ============== FaceBook Api ================
  // ============================================
  var fbButton = document.getElementById('fb-share-button');
  var url = 'https://nurseachieve.com';

  fbButton.addEventListener('click', function() {
      window.open('https://www.facebook.com/sharer/sharer.php?u=' + url,
          'facebook-share-dialog',
          'width=800,height=600'
      );
      return false;
  });

  // ============================================
  // ============== FaceBook Api ================
  // ============================================

// ************************************************************************************************* //

  // ============================================
  // ============== Gmail Api ===================
  // ============================================

  // Identify different broswers
    // Opera 8.0+
    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

    // Firefox 1.0+
    var isFirefox = typeof InstallTrigger !== \"undefined\";

    // Safari 3.0+ \"[object HTMLElementConstructor]\" 
    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === \"[object SafariRemoteNotification]\"; })(!window['safari'] || safari.pushNotification);

    // Internet Explorer 6-11
    var isIE = /*@cc_on!@*/false || !!document.documentMode;

    // Edge 20+
    var isEdge = !isIE && !!window.StyleMedia;

    // Chrome 1+
    var isChrome = !!window.chrome && !!window.chrome.webstore;

    // Blink engine detection
    var isBlink = (isChrome || isOpera) && !!window.CSS;


    $('#compose-button').click(function(){
      if (isIE || isSafari) {
        // $('#compose-button').attr('href','https://mail.google.com/mail/u/0/?view=cm&fs=1&to=someone@example.com&su=Join me on NurseAchieve!&body= Here is the link... ');
        handleAuthClick();
        var href = 'https://mail.google.com/mail/u/0/?view=cm&fs=1&to=someone@example.com&su=Join me on NurseAchieve!&body= Here is the link...'; 
          window.open(href); 
      }else{
        checkAuth();
      }
    });
      var clientId = '406244306827-v9aa9et94fqjqkm0208lpn73h390h6cg.apps.googleusercontent.com';
      var apiKey = 'AIzaSyBgda28xBHbjsTJyVoPXoU5PiJ9YqxeXtc';
      var scopes = 'https://mail.google.com/';
      function checkAuth() {
        gapi.auth.authorize({
          client_id: clientId,
          scope: scopes,
          immediate: true
        }, handleAuthResult);
      }
      function handleAuthClick() {
        gapi.auth.authorize({
          client_id: clientId,
          scope: scopes,
          immediate: false
        }, handleAuthResult);
        return false;
      }
      function handleAuthResult(authResult) {
        if(authResult && !authResult.error && !isIE && !isSafari) {
          var href = 'https://mail.google.com/mail/u/0/?view=cm&fs=1&to=someone@example.com&su=Join me on NurseAchieve!&body= Here is the link...'; 
          window.open(href); 
          return false;       
        } else {
          if (isIE || isSafari) {
            return false;
          }
          handleAuthClick();
        }
      }
  // ============================================
  // ============== Gmail Api ===================
  // ============================================
</script>

<!-- Include Gmail Api -->
<script src=\"https://apis.google.com/js/client.js\"></script>
<script async src=\"https://platform.twitter.com/widgets.js\" charset=\"utf-8\"></script>
<!-- Include Gmail Api -->";

echo "$str";

