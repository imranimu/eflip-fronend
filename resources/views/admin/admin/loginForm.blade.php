
<html>
<head>
    <title>Login to Admin Panel</title>
<link href="{{ asset('public/admin/css/login.css') }}" rel="stylesheet">
</head>
<body><br><br><br>
<div class="container">
    <section id="content">
        {!! Form::open(['url'=>'/adminn/checkLogin','method'=>'POST'])!!}
             {{ csrf_field() }}
        <h1>Admin Login</h1>
        <p style="color:red">
          @if (session('errorMessage'))
            {{session('errorMessage')}}
          @endif
        </p>
        <div classs="{{ $errors->has('email') ? ' has-error' : '' }}">
            {{Form::email('email',null,['placeholder'=>'Email','required'=>''])}}
            @if ($errors->has('email'))
                <br><strong style="color:red;">{{ $errors->first('email') }}</strong><br>
            @endif

        </div>
            <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                {{Form::password('password',['placeholder'=>'Password','required'=>''])}}
                @if ($errors->has('password'))
                    <br><strong style="color:red;">{{ $errors->first('password') }}</strong><br>
                @endif
            </div>
            <table cellspacing="30px">
                <tr>
                    <td>
                        {{Form::checkbox('remember')}} Remember me</td>
                </tr>
            </table>
            <div>
                {{Form::submit('Log In')}}
            </div>
        {!! Form::close() !!}
        
        <div class="button">
            Powered by:<a href="http://www.ahlimon.com"target="_blank"><img src="{{ asset('public/developer.png') }}" alt="Developer" height="20px"> A. H. Limon</a>
        </div>
    </section>
</div>
</body>
</html>
