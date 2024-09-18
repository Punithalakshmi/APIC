<!DOCTYPE html>
<html>
<head>
    <title>Coohom Token Generated Successfully</title>
</head>
<body>
    <h1>{{ $mailData['title'] }}</h1>
  
    <p>The token has been generated successfully. Please click here to <a href="{{$mailData['link']}}">Login</a>. This login link will expire within 24 hours, so please make sure to log in before then.</p>
     
    <p>Thank you</p>
</body>
</html>