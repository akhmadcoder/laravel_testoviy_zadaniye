<!DOCTYPE html>
<html>
<head>
    
    <title>запрос был успешно закрыт</title>

</head>

<body>

	<div class="row">
		<div class="col-md-10">
		    <div class="card">
		      <div class="card-body">
		        <h5 class="card-title">Уважаемый {{ $details['name'] }}, запрос был успешно закрыт !!!</h5>
		        <a href="{{ url('/view').'/'.$details['request_id'] }}" target="_blank" class="btn btn-primary">Перейти по запрос</a>
		        <p>Спасибо!!!</p>
		      </div>
		    </div>
		</div>
	</div>

</body>
</html>