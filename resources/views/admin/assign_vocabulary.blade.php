@extends('admin/layout')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{asset('public/admin/dist/css/select2/select2-bootstrap.css')}}" rel="stylesheet"/>
<link href="{{asset('public/admin/dist/css/select2/select2-bootstrap.min.css')}}" rel="stylesheet"/>
@stop
@section('content')
<div class="container-fluid">
  <!-- ============================================================== -->
  <!-- Start Page Content -->
  <!-- ============================================================== -->
  <div class="row">
    <div class="col-12">
      <div class="card">
                          <div class="card-body">
                              <h4 class="card-title">ASSIGN VOCABULARY</h4>

                                <div class="alert alert-danger d-none" id="errors"></div>
                                  <div class="form-group mb-2">
                                      <div class="form-row">
                                        <div class="col-12">
                                          <input type="hidden" id="user-id" value="{{$user}}"/>
                                          <select class="form-control select2-container input-lg step2-select" id="phrases" name="states[]" multiple="multiple">
                                            @foreach($vocabulary as $v)
                                              <option value="{{$v->id}}">{{$v->text_spanish}}</option>
                                            @endforeach
                                          </select>
                                          <small id="name"  class="form-text text-muted">search & select phrases</small>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="form-group mb-2">
                                    <input type="button" value="Save" class="btn btn-info" id="submit-form">
                                    <input type="button" value="Reset" class="btn btn-dark" id="reset-form">
                                    <small id="name" class="form-text text-muted"></small>
                                  </div>

                          </div>
                      </div>
    </div>
  </div>
  <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                @if($latest_attempt != 0)
                @php
                  $total = $red + $green + $orange;
                  $red_per=0;
                  $green_per=0;
                  $orange_per=0;
                  if($red != 0)
                  {
                    $red_per = ($red/$total)*100;
                  }
                  if($green != 0)
                  {
                    $green_per = ($green/$total)*100;
                  }
                  if($orange != 0)
                  {
                    $orange_per = ($orange/$total)*100;
                  }
                @endphp
                <div class="d-flex justify-content-between mb-2">
                  <div>
                    <h4 class="card-title">PREVIOUS VOCABULARY ASSIGNMENTS</h4>
                    <h6 class="card-subtitle">Correct <code>{{$green_per}}%</code> ,incorrect <code>{{$red_per}}%</code> & partial correct <code>{{$orange_per}}%</code></h6>
                  </div>
                  <select class="form-control w-25" id="selectAttempt">
                    @for($i = $latest_attempt; $i>=1; $i--)
                    <option value="{{$i}}" @if(Request()->attempt_no == $i)selected="selected"@endif>View Attempt No.{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="container-fluid px-0">
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
                  <!--<div class="progress mb-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$green_per}}%"
                                                  aria-valuenow="{{$green_per}}" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{$orange_per}}%"
                                                  aria-valuenow="{{$orange_per}}" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{$red_per}}%"
                                                  aria-valuenow="{{$red_per}}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>-->
                </div>
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
                <!--
              <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
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
                    @if($assignments)
                    <tbody>
                          @foreach($assignments as $a)
                          <tr>
                              <td scope="row">{{$loop->iteration}}</td>
                              <td scope="row">
                                {{$a->text_spanish}}
                              </td>
                              <td scope="row">
                                {{$a->text_english}}
                              </th>
                              <td scope="row">{{$a->answer}}</td>
                              <td scope="row">
                                {{$a->attempt_no}}
                              </td>
                          </tr>
                          @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot>
                    @endif
                  </table>
              </div>
            -->
              @else
              <div class="d-flex mb-2">
                  <h4 class="card-title">PREVIOUS VOCABULARY ASSIGNMENTS</h4>
              </div>
            <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                      <tr>
                          <th scope="col">#</th>
                          <th scope="col">Spanish</th>
                          <th scope="col">
                            English
                          </th>
                      </tr>
                  </thead>
                  @if($vocabulary_assigned)
                  <tbody>
                        @foreach($vocabulary_assigned as $a)
                        <tr>
                            <td scope="row">{{$loop->iteration}}</td>
                            <td scope="row">
                              {{$a->text_spanish}}
                            </td>
                            <td scope="row">
                              {{$a->text_english}}
                            </th>
                        </tr>
                        @endforeach
                  </tbody>
                  @endif
                </table>
            </div>
              @endif
          </div>
      </div>
  </div>
</div>
@stop
@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function(){
    $("#phrases").select2({
        theme: "bootstrap"
    })
    //assign phrases
    $("#submit-form").on('click',function(){
      const user = $("#user-id").val()
      const phrases = $("#phrases").val()
      if(user != '' && phrases != '')
      {
        if(phrases.length >= 10)
        {
          let fn = new FormData()
          fn.append("_token", "{{ csrf_token() }}")
          fn.append("user", user)
          fn.append("vocabulary", JSON.stringify(phrases))
          $.ajax({
            type:'POST',
            url:'{{url("/admin/vocabulary/assign")}}',
            data: fn,
            contentType: false,
                      cache: false,
                      processData: false,
            success: (res)=>{
              window.location.reload()
            },
            error: (res)=>{
              console.log(res)
            }
          })
        }else{
          alert('select minimum 10')
        }
      }else{
        alert('select phrases')
      }
    })
    //select attempt
    $("#selectAttempt").on('change',function(){
      const attempt_no = $(this).val()
      const user = $("#user-id").val()
      window.location.href = "{{url('')}}/admin/vocabulary/assign/view-attempt/"+user+"/"+attempt_no
    })
  })
</script>
@stop
