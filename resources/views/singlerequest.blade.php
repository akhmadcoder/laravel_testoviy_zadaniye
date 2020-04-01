@extends('layouts.app')

@section('content')

<?php $userRoles = Auth::user()->roles->pluck('name'); ?>
<div class="container">
    <!-- KODI DLYA ALERTS -->
    <div class="row">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        @if(Session::has('fail'))
            <div class="alert alert-danger">
               {{Session::get('fail')}}
            </div>
        @endif
    </div>
    
    <!-- KOD DLYA VID ZAPROS -->
    <div class="card" style="width: 70%;">
      <div class="card-body">
        <h5 class="card-title">тема запроса: {{$request['title']}}</h5>
        <p class="card-text">сообщение запроса: {{$request['message']}}</p>
        <p class="card-text">имя клиента: {{$request['client']['name']}}</p>
        <p class="card-text">файл: <a href="{{ url('uploads/').'/'.$request['file'] }}" target="_blank" class="card-link">{{$request['file']}}</a></p>
        <p class="card-text">статус:
            @if($request['status'] == 0)
                открыто 

                @if($userRoles->contains('manager'))

                <a href="{{ url('/accept').'/'.$request->id }}" class="btn btn-outline-info">принять запрос</a>

                @elseif($userRoles->contains('client'))

                <a href="{{ url('/close').'/'.$request->id }}" class="btn btn-outline-info">закрой запрос</a>
                
                @endif

            @elseif($request['status'] == 1)
                закрыто

                <h6 class="card-subtitle mb-2 text-muted">кто закрыл: закрыто {{$request['closed']['name']}}</h6>
                
            @else
                принято
                <a href="{{ url('/close').'/'.$request->id }}" class="btn btn-outline-info">закрой запрос</a>
            @endif

            <hr>

            @if(isset($answers))

                 
                <h3 class="card-subtitle mb-2 text-muted">Все сообщения:</h3>
                <!-- KODI DLYA POKAZAT VSEH SOOBSHENIYU -->
                @foreach($answers as $answer)

                  

                    <div class="alert alert-primary" role="alert">
                      <p><i>Сообщение от:</i> {{$answer->user->name}}</p>
                      <p><i>Сообщение:</i> {{$answer->message}}</p>
                      <hr>
                      <p class="float-right"><i>Дата:</i> {{$answer->created_at}}</p>
                      <br>
                    </div>


                @endforeach

                
            @endif
        </p>
         
        <!-- FORMA DLYA OTPRAVIT NOVIY SOOBSHENIYU, ETO BUDET VIDNO ESLI ZAPROS POKA NE ZAKRITO -->
        @if($request['status']!=1)
        <h3 class="card-subtitle mb-2 text-muted">Отправить новое сообщение</h3>

        <form id="new_request" method="post" action="{{ route('answers.store') }}" enctype="multipart/form-data">  
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="request_id" value="{{ $request['id'] }}">
            <input type="hidden" name="client_name" value="{{$request['client']['name']}}">
            <input type="hidden" name="client_email" value="{{$request['client']['email']}}">
        
          <div class="form-group">
            @error('message')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <textarea placeholder="сообщение ..." class="@error('message') is-invalid @enderror form-control" id="message" name="message">{{ old('message') }}</textarea>
            
          </div>

          <button type="submit" class="btn btn-primary">Отправить</button>
        </form>

        @endif

        <a href="{{ url('/requests') }}" class="float-right btn btn-outline-info">вернуться назад</a>


        
        
      </div>
    </div>


</div>
@endsection
