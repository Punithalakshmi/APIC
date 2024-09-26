<!DOCTYPE html>
<html>
<head>
    <title>Login Token Generated Successfully</title>
</head>
<body>
<h1>Hi {{$mailData['name']}}, </h1>
<br/><br/><br/>
    <h1>{{ $mailData['title'] }}</h1>
    <br/><br/><br/>
    <p>The login token for Decospoke has been generated successfully. Please click here to <a href="{{$mailData['link']}}">Login</a>. This login link will expire within 7 days, so please make sure to log in before then..</p>
     
    <p>Thank you</p>
</body>
</html>