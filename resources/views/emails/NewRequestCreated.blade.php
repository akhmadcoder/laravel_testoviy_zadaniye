<!DOCTYPE html>
<html>
<head>
    
    <title>новый запрос</title>

</head>

<body>

	<div class="row">
		<div class="col-md-10">
		    <div class="card">
		      <div class="card-body">
		        <h5 class="card-title">У вас есть новый запрос !!!</h5>
		        <p class="card-text"><i>тема</i>: {{ $details['title'] }}</p>
		        <p class="card-text"><i>сообщение</i>: {{ $details['message'] }}</p>
		        <p class="card-text"><i>имя клиента</i>: {{ $details['client_name'] }}</p>
		        <a href="{{ url('/view').'/'.$details['request_id'] }}" target="_blank" class="btn btn-primary">Перейти по запрос</a>

		        <p>Спасибо!!!</p>
		      </div>
		    </div>
		</div>
	</div>

    
</body>
</html>