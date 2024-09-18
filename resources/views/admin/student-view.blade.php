@extends('admin/layout')
@section('css')
<link href="{{asset('public/admin/dist/css/player.css')}}" rel="stylesheet"/>
@stop
@section('content')
<div class="container-fluid">
  <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <h4 class="card-title">ALL STUDENTS</h4>
                  <h6 class="card-subtitle">Click <code>edit</code> button to modify & click on <code>delete</code> button to delete.</h6>
              </div>
              <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">First Name</th>
                              <th scope="col">
                                Last Name
                              </th>
                              <th scope="col">Email</th>
                              <th scope="col">
                                Date Joined
                              </th>
                              <th>
                                Phrase
                              </th>
                              <th>
                                Vocabulary
                              </th>
                              <th scope="col">Handle</th>
                          </tr>
                      </thead>
                      <tbody id="dataTable">
                          @if($students)
                            @foreach($students as $s)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{$s->first_name}}</td>
                                <td scope="row">
                                  {{$s->last_name}}
                                </td>
                                <td scope="row">
                                  {{$s->email}}
                                </td>
                                <td scope="row">
                                  {{$s->created_at}}
                                </td>
                                <td scope="row">
                                  <a href="{{url('/')}}/admin/phrase/assign/{{$s->id}}"><i class="  fas fa-text-height"></i></a>
                                </td>
                                <td scope="row">
                                  <a href="{{url('/')}}/admin/vocabulary/assign/{{$s->id}}"><i class=" fas fa-bold
"></i></a>
                                </td>
                                <td scope="row">
                                  @if($s->status == 'ACTIVE' && $s->subscription == 'CONTINUED')
                                    <a data-id="{{$s->id}}" data-email="{{$s->email}}" class="deactivateBtn" data-toggle="modal" data-target="#full-width-modal0" style="cursor:  pointer;"><i class="fas fa-toggle-on"></i></a>
                                  @else
                                    <a data-id="{{$s->id}}" data-email="{{$s->email}}" class="activateBtn" data-toggle="modal" data-target="#full-width-modal0" style="cursor:  pointer;"><i class="fas fa-toggle-off"></i></a>
                                  @endif
                                  <a data-id="{{$s->id}}" data-first-name="{{$s->first_name}}" data-last-name="{{$s->last_name}}" data-email="{{$s->email}}" class="updateBtn" data-toggle="modal" data-target="#full-width-modal" style="cursor:  pointer;"><i class="fas fa-edit text-warning"></i></a>
                                  <a data-id="{{$s->id}}" data-name="{{$s->first_name}}" class="deleteBtn" data-toggle="modal" data-target="#full-width-modal1" style="cursor:  pointer;"><i class=" fas fa-trash-alt
text-danger"></i></a>
                                </td>
                            </tr>
                            @endforeach
                          @endif
                      </tbody>
                      <tfoot>
                                  <tr class="active">
                                      <td colspan="8">
                                          <div class="dataTables_paginate paging_simple_numbers">
                                          @if ($students->lastPage() > 1)

<ul class="pagination">
  @if($students->currentPage() != 1 && $students->lastPage() >= 5)
  <li class="paginate_button page-item">
      <a href="{{ $students->url($students->url(1)) }}" class="page-link" aria-label="Previous">
          <span aria-hidden="true">First</span>
      </a>
  </li>
  @endif
  @if($students->currentPage() != 1)
  <li class="paginate_button page-item">
      <a href="{{ $students->url($students->currentPage()-1) }}" class="page-link" aria-label="Previous">
          <span aria-hidden="true">&#x3C;</span>
      </a>
  </li>
  @endif
  @for($i = max($students->currentPage()-2, 1); $i <= min(max($students->currentPage()-2, 1)+4,$students->lastPage()); $i++)
      @if($students->currentPage() == $i)
      <li class="paginate_button page-item active">
          @else
      <li class="paginate_button page-item ">
          @endif
          <a href="{{ $students->url($i) }}" class="page-link">{{ $i }}</a>
      </li>
      @endfor
      @if ($students->currentPage() != $students->lastPage())
      <li class="paginate_button page-item ">
          <a href="{{ $students->url($students->currentPage()+1) }}" class="page-link" aria-label="Next">
              <span aria-hidden="true">&#x3E;</span>
          </a>
      </li>
      @endif
      @if ($students->currentPage() != $students->lastPage() && $students->lastPage() >= 5)
      <li class="paginate_button page-item ">
          <a href="{{ $students->url($students->lastPage()) }}" class="page-link" aria-label="Next">
              <span aria-hidden="true">Last</span>
          </a>
      </li>
      @endif
</ul>
@endif
                                          </div>
                                      </td>
                                  </tr>
                              </tfoot>
                  </table>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- Full width modal content -->
                                        <div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-full-width">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="fullWidthModalLabel">Students</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <input type="hidden" id="hidden_id_edit">
                                                        <h6>Edit Student Record</h6>
                                                        <div class="alert alert-danger d-none" id="editError">

                                                        </div>
                                                        <form class="mt-3">

                                                            <div class="form-row">
                                                              <div class="col-md-6">
                                                                <div class="form-group mb-2">
                                                                    <input type="text" class="form-control" name="firstName" id="nametextFirst" aria-describedby="nametextFirst" placeholder="First Name">
                                                                    <small id="nametextFirst" class="form-text text-muted">enter first name of student</small>
                                                                </div>
                                                              </div>
                                                              <div class="col-md-6">
                                                                <div class="form-group mb-2">
                                                                    <input type="text" class="form-control" name="lastName" id="nametextLast" aria-describedby="nametextLast" placeholder="Last Name">
                                                                    <small id="nametextLast" class="form-text text-muted">enter last name of student</small>
                                                                </div>
                                                              </div>
                                                            </div>

                                                            <div class="form-group mb-2">
                                                                <input type="email" class="form-control" name="email" id="email" aria-describedby="email" placeholder="Enter your email">
                                                                <small id="email" class="form-text text-muted">Enter active | valid email</small>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

                                                        <button type="button" class="btn btn-primary" id="yesEdit">Confirm</button>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->

<!-- Full width modal content -->
                                        <div id="full-width-modal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel1" aria-hidden="true">
                                            <div class="modal-dialog modal-full-width">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="fullWidthModalLabel1">Students</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <input type="hidden" id="hidden_id_del">
                                                        <h6>Delete Student</h6>
                                                        <div class="alert alert-danger d-none" id="deleteError">

                                                        </div>
                                                        <div id="delete-text"></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

                                                        <button type="button" class="btn btn-primary" id="yesDelete">Delete</button>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->


                                        <!-- Full width modal content -->
                                                                                <div id="full-width-modal0" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel0" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-full-width">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h4 class="modal-title" id="fullWidthModalLabel0">Students</h4>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                            <input type="hidden" id="hidden_id_toggle">
                                                                                                <h6>Manage Student</h6>
                                                                                                <div class="alert alert-danger d-none" id="toggleError">

                                                                                                </div>
                                                                                                <div id="toggle-text"></div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

                                                                                                <button type="button" class="btn btn-primary" id="yesToggle">Confirm</button>
                                                                                            </div>
                                                                                        </div><!-- /.modal-content -->
                                                                                    </div><!-- /.modal-dialog -->
                                                                                </div><!-- /.modal -->
@stop
@section('javascript')
<script>
  $(document).ready(function(){
    //show edit modal
    $("#dataTable").on('click','.updateBtn',function(){
      let first_name = $(this).attr('data-first-name')
      let last_name = $(this).attr('data-last-name')
      let email = $(this).attr('data-email')
      let id = $(this).attr('data-id')
      $("#nametextFirst").val(first_name)
      $("#nametextLast").val(last_name)
      $("#email").val(email)
      $("#hidden_id_edit").val(id)
    })
    //edit request
    $("#yesEdit").on('click',function(){
      let first_name = $("#nametextFirst").val()
      let last_name = $("#nametextLast").val()
      let email = $("#email").val()
      let id = $("#hidden_id_edit").val()
      $.ajax({
        type: 'post',
        url: '{{url("")}}/admin/students/edit',
        data: {
          "_token": "{{csrf_token()}}",
          "first_name": first_name,
          "last_name": last_name,
          "email": email,
          "id": id
        },
        success: (res)=>{
          console.log(res)
let k=``;
          if(res == 'success')
          {
            window.location.reload()
          }else if(res == 'error')
          {
             k += `

              <li>
              First Name Required
              </li>
              <li>
              Last Name Required
              </li>
              <li>
              Email Required
              </li>

            `
          }else if(res == 'error-email')
          {
            k+=`
            <li>
            Email already in use
            </li>`
          }
          if(k != ``)
          {
            $("#editError").html(k)
            $("#editError").removeClass('d-none')
          }
        },
        error: (res)=>{
          console.log(res)
        }
      })
    })
    //delete modal show
    $("#dataTable").on('click','.deleteBtn',function(){
      let id = $(this).attr('data-id')
      let name = $(this).attr('data-name')
      $("#delete-text").html("<p>Are you sure want to delete <span class='text-bold'>'" + name + "'</span>? </p>")
                $("#hidden_id_del").val(id)
                        $("#full-width-modal1").modal('show')
    })
    //delete request
    $("#yesDelete").on('click',function(){
      let id = $("#hidden_id_del").val()
console.log(id)
      $.ajax({
        type: 'post',
        url: '{{url("")}}/admin/students/delete',
        data: {
          "_token": "{{csrf_token()}}",
          "id":id
        },
        success: (res)=>{
          window.location.reload()
        },
        error: (res)=>{
          console.log(res)
        }
      })
    })
    //activate
    $("#dataTable").on('click','.deactivateBtn',function(){
      let id = $(this).attr('data-id')
      let name = $(this).attr('data-email')
      $("#toggle-text").html("<p>Are you sure want to deactivate <span class='text-bold'>'" + name + "'</span>? </p>")
                $("#hidden_id_toggle").val(id)
                        $("#full-width-modal0").modal('show')
    })
    //activate
    $("#dataTable").on('click','.activateBtn',function(){
      let id = $(this).attr('data-id')
      let name = $(this).attr('data-email')
      $("#toggle-text").html("<p>Are you sure want to activate <span class='text-bold'>'" + name + "'</span>? </p>")
                $("#hidden_id_toggle").val(id)
                        $("#full-width-modal0").modal('show')
    })
  })
  $("#yesToggle").on('click',function(){
    let id = $("#hidden_id_toggle").val()
    console.log(id)
    $.ajax({
      type:'post',
      url:'{{url("")}}/admin/students/toggle',
      data:{
        "_token": "{{csrf_token()}}",
        "id": id
      },
      success: (res)=>{
        window.location.reload()
      },
      error: (res)=>{
        console.log(res)
      }
    })
  })
</script>

@stop
