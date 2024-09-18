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
      const phrases = $("#phrases").val()
      if(phrases != '')
      {
        if(phrases.length >= 10)
        {
          let fn = new FormData()
          fn.append("_token", "{{ csrf_token() }}")
          fn.append("vocabulary", JSON.stringify(phrases))
          $.ajax({
            type:'POST',
            url:'{{url("/admin/vocabulary/all/assign")}}',
            data: fn,
            contentType: false,
                      cache: false,
                      processData: false,
                      beforeSend() {
                                    $('.gifimg').removeClass('d-none');
                                    $('.overlay').removeClass('d-none');
                                },
            success: (res)=>{
              $('.gifimg').addClass('d-none');
              $('.overlay').addClass('d-none');
              window.location.reload()
            },
            error: (res)=>{
              console.log(res)
            }
          })
        }else{
          alert('select minimum 10 ')
        }
      }else{
        alert('select phrases')
      }
    })
  })
</script>
@stop
