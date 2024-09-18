@extends('main/panel')
@section('content')

      <div class="container py-1 mt-5 justify-content-center text-center" style="min-height: 100vh;">
        <div class="card text-center">
<div class="card-header">
  Your Previous Vocabulary Attempts
</div>
<div class="card-body">
@if($latest_attempt != 0)
@php
  $total = $red + $green + $orange;
  $red_per = ($red/$total)*100;
  $green_per = ($green/$total)*100;
  $orange_per = ($orange/$total)*100;
@endphp
<div class="row mb-2 mt-2">
  <div class="col-12 text-center justify-content-center mb-1">
    <h6 class="text-center">Correct <code>{{$green_per}}%</code> ,incorrect <code>{{$red_per}}%</code> & partial correct <code>{{$orange_per}}%</code></h6>
  </div>
</div>
<div class="container-fluid">
  <ul class="nav nav-tabs justify-content-center" role="tablist">
	<li class="nav-item">
		<a class="nav-link active text-success" data-toggle="tab" href="#tabs-1" role="tab">Green List</a>
	</li>
	<li class="nav-item">
		<a class="nav-link text-danger" data-toggle="tab" href="#tabs-2" role="tab">Red List</a>
	</li>
	<li class="nav-item">
		<a class="nav-link text-warning" data-toggle="tab" href="#tabs-3" role="tab">Orange List</a>
	</li>
</ul><!-- Tab panes -->
</div>
<div class="tab-content">
	<div class="tab-pane active" id="tabs-1" role="tabpanel">
    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
              <th scope="col">Spanish</th>
              <th scope="col">
                English
              </th>
              <th scope="col">
                Answer
              </th>
              <th scope="col">
                Attempt NO:
              </th>
            </tr>
        </thead>
        <tbody>
            @if($assignments)
              @foreach($assignments as $a)
              @if($a->status == 'green')
              <tr>
                  <td scope="row" class="text-success">
                    {{$a->text_spanish}}
                  </td>
                  <td scope="row" class="text-success">
                    {{$a->text_english}}
                  </th>
                  <td scope="row" class="text-success">{{$a->answer}}</td>
                  <td scope="row" class="text-success">
                    {{$a->attempt_no}}
                  </td>
              </tr>
              @endif
              @endforeach
            @endif
        </tbody>
    </table>
    </div>
	</div>
	<div class="tab-pane" id="tabs-2" role="tabpanel">
    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
              <th scope="col">Spanish</th>
              <th scope="col">
                English
              </th>
              <th scope="col">
                Answer
              </th>
              <th scope="col">
                Attempt NO:
              </th>
            </tr>
        </thead>
        <tbody>
            @if($assignments)
              @foreach($assignments as $a)
              @if($a->status == 'red')
              <tr>
                  <td scope="row" class="text-danger">
                    {{$a->text_spanish}}
                  </td>
                  <td scope="row" class="text-danger">
                    {{$a->text_english}}
                  </th>
                  <td scope="row" class="text-danger">{{$a->answer}}</td>
                  <td scope="row" class="text-danger">
                    {{$a->attempt_no}}
                  </td>
              </tr>
              @endif
              @endforeach
            @endif
        </tbody>
    </table>
    </div>
	</div>
	<div class="tab-pane" id="tabs-3" role="tabpanel">
    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
              <th scope="col">Spanish</th>
              <th scope="col">
                English
              </th>
              <th scope="col">
                Answer
              </th>
              <th scope="col">
                Attempt NO:
              </th>
            </tr>
        </thead>
        <tbody>
            @if($assignments)
              @foreach($assignments as $a)
              @if($a->status == 'orange')
              <tr>
                  <td scope="row" class="text-warning">
                    {{$a->text_spanish}}
                  </td>
                  <td scope="row" class="text-warning">
                    {{$a->text_english}}
                  </th>
                  <td scope="row" class="text-warning">{{$a->answer}}</td>
                  <td scope="row" class="text-warning">
                    {{$a->attempt_no}}
                  </td>
              </tr>
              @endif
              @endforeach
            @endif
        </tbody>
    </table>
    </div>
	</div>
</div>
@else
<h2 class="text-center text-dark">
  No data to show
</h2>
@endif
</div>
<div class="card-footer">
  <input type="hidden" id="user-id" value="{{Auth::user()->id}}"/>
  @if($latest_attempt != 0)
  <select class="form-control w-100" id="selectAttempt">
    @for($i = $latest_attempt; $i>=1; $i--)
    <option value="{{$i}}" @if(Request()->attempt_no == $i)selected="selected"@endif>View Attempt No.{{$i}}</option>
    @endfor
  </select>
  @endif
</div>
</div>
      </div>

@stop
@section('javascript')
<script>
  $(document).ready(function(){
    //select attempt
    $("#selectAttempt").on('change',function(){
      const attempt_no = $(this).val()
      const user = $("#user-id").val()
      window.location.href = "{{url('')}}/user/panel/vocabulary/view/attempt/"+user+"/"+attempt_no
    })
  })
</script>
@stop
