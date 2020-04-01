@extends('layouts.app')

@section('content')
<?php $userRoles = Auth::user()->roles->pluck('name'); ?>

<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <!-- коды для alerts -->
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
            <h2 class="float-left">Запросы</h2>
            <!-- коды для Запросы -->
            @if(isset($requests)&&count($requests)>0)
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">тема</th>
                      <th scope="col">
                          <!-- коды для filter asc / desc status -->
                        <?php $sortStatus = (isset($_GET['sortStatus']) && $_GET['sortStatus'] == 'asc') ? 'desc' : 'asc';?>
                        <a href="{{ url('/requests').'?sortStatus='.$sortStatus }}">статус</a>
                      </th>
                      <th scope="col">дата</th>
                      <th scope="col">
                        <!-- коды для filter asc / desc prochitanniy / ne prochitanniy -->
                        <?php $sortIsRead = (isset($_GET['sortIsRead']) && $_GET['sortIsRead'] == 'asc') ? 'desc' : 'asc';?>
                        <a href="{{ url('/requests').'?sortIsRead='.$sortIsRead }}">прочитенний</a>
                      </th>
                      <th scope="col"></th>
                    </tr>
                    <tr>
                      <th scope="col"></th>
                      <th scope="col"></th>
                      <th scope="col">
                          <!-- kodi dlya sort status -->
                          <form id="sortByStatus" method="get" action="{{ url('requests') }}">  
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
              
                            <select onchange="sortByStatus()" name="status" class="custom-select" id="inputGroupSelect01">
                              <option selected>Выберите ...</option>
                              <option value="3">открыто</option>
                              <option value="1">закрыто</option>
                              <option value="2">принято</option>
                            </select>
                          </form>
                      </th>  
                      <th scope="col"></th>
                      <th scope="col">
                        <!-- коды для filter prochitanniy / ne prochitanniy -->
                        <form id="sortByRead" method="get" action="{{ url('requests') }}">  
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
              
                            <select onchange="sortByRead()" name="is_read" class="custom-select" id="inputGroupSelect01">
                              <option selected>Выберите...</option>
                              <option value="2">не прочитенний</option>
                              <option value="1">прочитенний</option>
                            </select>
                        </form>
                      </th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($requests as $request)
                        <tr>
                          <th scope="row">{{$request->id}}</th>
                          <td>{{$request->title}}</td>
                          <td>
                            @if($request->status == 0)
                            открыто
                            @elseif($request->status == 1)
                            закрыто
                            @else
                            принято
                            @endif
                          </td>
                          <td>{{$request->created_at}}</td>
                          <td>
                            @if($request->is_read == 0)
                              <span class="float-left badge badge-pill badge-info">новый</span>
                            @endif
                          </td>
                          <td>
                            <a href="{{ url('/view').'/'.$request->id }}" class="btn btn-outline-info">Посмотреть</a>
                          </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
            @else
                <div class="alert alert-info" role="alert">
                  Никаких запросов пока нет.
                </div>
            @endif

            <nav aria-label="Page navigation example">
              <ul class="pagination">
                {{ $requests->links() }}
              </ul>
            </nav>

        </div>
        
        <!-- ESLI USER ROLE 'KLENT', ETO FORMA BUDET VIDNO -->
        @if($userRoles->contains('client'))

        <div class="col-md-8">
            <h1 class="float-left">Отправить новый запрос</h1>
        </div>
        <div class="col-md-8">
            <form id="new_request" method="post" action="{{ route('requests.store') }}" enctype="multipart/form-data">  
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
              
            <div class="form-group">
              <label for="exampleInputEmail1">тема</label>
              @error('title')
                  <div class="alert alert-danger">{{ $message }}</div>
              @enderror
              <input type="text" value="{{ old('title') }}" class="@error('title') is-invalid @enderror form-control" id="title" name="title">
            </div>
            
            <div class="form-group">
              <label for="exampleInputEmail1">сообщение</label>
              @error('message')
                  <div class="alert alert-danger">{{ $message }}</div>
              @enderror
              <textarea class="@error('message') is-invalid @enderror form-control" id="message" name="message">{{ old('message') }}</textarea>
              
            </div>

            @error('file')
              <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupFileAddon01">Загрузить</span>
                </div>
                <div class="custom-file">
                  <input type="file" class="@error('message') is-invalid @enderror custom-file-input" name="file" value="{{ old('file') }}" id="file">
                  <label class="custom-file-label" for="inputGroupFile01">файловый инпут</label>
                </div>
                
            </div>
              
              <button type="submit" class="btn btn-primary">Отправить</button>
            </form>

        </div>
      @endif

    </div>
</div>

<!-- ETIH 2 JAVASCRIPT FUNKSIYU DLYA FILTROVANIYU -->
<script type="text/javascript">
  function sortByStatus()
  {
    document.getElementById("sortByStatus").submit();
  }

  function sortByRead()
  {
    document.getElementById("sortByRead").submit();
  }
</script>

@endsection
