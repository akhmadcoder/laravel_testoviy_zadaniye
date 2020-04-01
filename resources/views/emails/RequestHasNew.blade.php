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
		        <h5 class="card-title">Есть новое сообщение !!!</h5>
		        <p class="card-text"><i>сообщение</i>: {{ $details['message'] }}</p>
		        <a href="{{ url('/view').'/'.$details['request_id'] }}" target="_blank" class="btn btn-primary">Перейти по запрос</a>
		        <p>Спасибо!!!</p>
		      </div>
		    </div>
		</div>
	</div>

</body>
</html>