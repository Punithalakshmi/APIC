<!DOCTYPE html>
<html>
<head>
    <title>Token Refreshed Successfully</title>
</head>
<body>
<h1>Hi {{$mailData['name']}}, </h1>
<br/><br/><br/>
    <h1>{{ $mailData['title'] }}</h1>
    <br/><br/><br/>
    <p>Token has been refreshed successfully. Please click here to <a href="{{$mailData['link']}}">Login</a>. This login link will expire within 7 days, so please make sure to log in before then..</p>
     
    <p>Thank you</p>
</body>
</html>