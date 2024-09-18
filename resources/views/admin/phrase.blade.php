@extends('admin/layout')
@section('css')
<link href="{{asset('public/admin/dist/css/player.css')}}" rel="stylesheet"/>
<link href="{{asset('public/admin/dist/css/media.css')}}" rel="stylesheet"/>
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
                                <h4 class="card-title">MAKE A PHRASE</h4>
                                <form class="mt-3">
                                  <div class="alert alert-danger d-none" id="errors"></div>
                                    <div class="form-group mb-2">
                                        <input type="text" class="form-control" id="nametextSpanish" aria-describedby="name" placeholder="Name">
                                        <small id="name" class="form-text text-muted">Write context in Spanish</small>
                                    </div>
                                    <div class="form-group mb-2">
                                        <input type="text" class="form-control" id="nametextEnglish" aria-describedby="name" placeholder="Name">
                                        <small id="name" class="form-text text-muted">Write context in English</small>
                                    </div>
                                    <div class="form-group mb-2">
                                        <div class="form-row">
                                          <div class="col-md-1">
                                            <a class="record" id="start-recording-1"><i class=" fas fa-microphone fa-3x"></i></a>
                                            <a class="record d-none" id="stop-recording-1"><i class=" fas fa-microphone-slash
 fa-3x"></i></a>
                                          </div>
                                          <div class="col-md-11">
                                            <audio class="audio-controls audio-sp" id="voice-note-1" controls></audio>
                                          </div>
                                        </div>
                                        <small id="name" class="form-text text-muted">Record context in Spanish</small>
                                    </div>
                                    <div class="form-group mb-2">
                                        <input type="file" accept=".wav" class="form-control" id="recordingMediaSpanish" >
                                        <small id="name" class="form-text text-muted">Or upload recorded file (.wav)</small>
                                    </div>
                                    <div class="form-group mb-2">
                                        <div class="form-row">
                                          <div class="col-md-1">
                                            <a class="record" id="start-recording-2"><i class=" fas fa-microphone fa-3x"></i></a>
                                            <a class="record d-none" id="stop-recording-2"><i class=" fas fa-microphone-slash
 fa-3x"></i></a>
                                          </div>
                                          <div class="col-md-11">
                                            <audio class="audio-controls audio-eng" id="voice-note-2" controls></audio>
                                          </div>
                                        </div>
                                        <small id="name" class="form-text text-muted">Record context in English</small>
                                    </div>
                                    <div class="form-group mb-2">
                                        <input type="file" accept=".wav" class="form-control" id="recordingMediaEnglish" >
                                        <small id="name" class="form-text text-muted">Or upload recorded file (.wav)</small>
                                    </div>
                                    <div class="form-group mb-2">
                                      <input type="submit" value="Save" class="btn btn-info" id="submit">
                                      <input type="button" value="Reset" class="btn btn-dark" id="reset-form">
                                      <small id="name" class="form-text text-muted"></small>
                                    </div>
                                </form>
                            </div>
                        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">PREVIOUS PHRASES</h4>
                    <h6 class="card-subtitle">Click <code>edit</code> button to modify & click on <code>delete</code> button to delete.</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Text Spanish</th>
                                <th scope="col">
                                  Text English
                                </th>
                                <th scope="col">Audio English</th>
                                <th scope="col">
                                  Audio Spanish
                                </th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                            @if($phrases)
                              @foreach($phrases as $p)
                              <tr>
                                  <th scope="row">{{$loop->iteration}}</th>
                                  <td>{{$p->text_spanish}}</td>
                                  <td>
                                    {{$p->text_english}}
                                  </td>
                                  <td>
                                    <audio class="audio-controls audio-controls-table" controls>
                                      <source src="{{url('/public/adminRecordings')}}/{{$p->audio_english}}" type="video/webm"/>
                                    </audio>
                                  </td>
                                  <td>
                                    <audio class="audio-controls audio-controls-table" controls>
                                      <source src="{{url('/public/adminRecordings')}}/{{$p->audio_spanish}}" type="video/webm"/>
                                    </audio>
                                  </td>
                                  <td>
                                    <a data-id="{{$p->id}}" data-name-sp="{{$p->text_spanish}}" data-name-eng="{{$p->text_english}}" class="updateBtn" data-toggle="modal" data-target="#full-width-modal" style="cursor:  pointer;"><i class="fas fa-edit text-warning"></i></a>
                                    <a data-name="{{$p->text_spanish}}" data-id="{{$p->id}}" class="deleteBtn" data-toggle="modal" data-target="#full-width-modal1" style="cursor:  pointer;"><i class=" fas fa-trash-alt
 text-danger"></i></a>
                                  </td>
                              </tr>
                              @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                                    <tr class="active">
                                        <td colspan="6">
                                            <div class="dataTables_paginate paging_simple_numbers">
                                            @if ($phrases->lastPage() > 1)

<ul class="pagination">
    @if($phrases->currentPage() != 1 && $phrases->lastPage() >= 5)
    <li class="paginate_button page-item">
        <a href="{{ $phrases->url($phrases->url(1)) }}" class="page-link" aria-label="Previous">
            <span aria-hidden="true">First</span>
        </a>
    </li>
    @endif
    @if($phrases->currentPage() != 1)
    <li class="paginate_button page-item">
        <a href="{{ $phrases->url($phrases->currentPage()-1) }}" class="page-link" aria-label="Previous">
            <span aria-hidden="true">&#x3C;</span>
        </a>
    </li>
    @endif
    @for($i = max($phrases->currentPage()-2, 1); $i <= min(max($phrases->currentPage()-2, 1)+4,$phrases->lastPage()); $i++)
        @if($phrases->currentPage() == $i)
        <li class="paginate_button page-item active">
            @else
        <li class="paginate_button page-item ">
            @endif
            <a href="{{ $phrases->url($i) }}" class="page-link">{{ $i }}</a>
        </li>
        @endfor
        @if ($phrases->currentPage() != $phrases->lastPage())
        <li class="paginate_button page-item ">
            <a href="{{ $phrases->url($phrases->currentPage()+1) }}" class="page-link" aria-label="Next">
                <span aria-hidden="true">&#x3E;</span>
            </a>
        </li>
        @endif
        @if ($phrases->currentPage() != $phrases->lastPage() && $phrases->lastPage() >= 5)
        <li class="paginate_button page-item ">
            <a href="{{ $phrases->url($phrases->lastPage()) }}" class="page-link" aria-label="Next">
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
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
</div>



<!-- Full width modal content -->
                                        <div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-full-width">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="fullWidthModalLabel">PHRASE</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <input type="hidden" id="hidden_id_edit">
                                                        <h6>Edit Phrase</h6>
                                                        <div class="alert alert-danger d-none" id="editError">

                                                        </div>
                                                        <form class="mt-3">
                                                            <div class="form-group mb-2">
                                                                <input type="text" class="form-control" id="nametextSpanishEdit" aria-describedby="name" placeholder="Name">
                                                                <small id="name" class="form-text text-muted">Write context in Spanish</small>
                                                            </div>
                                                            <div class="form-group mb-2">
                                                                <input type="text" class="form-control" id="nametextEnglishEdit" aria-describedby="name" placeholder="Name">
                                                                <small id="name" class="form-text text-muted">Write context in English</small>
                                                            </div>
                                                            <div class="form-group mb-2">
                                                                <div class="form-row">
                                                                  <div class="col-md-1">
                                                                    <a class="record" id="start-recording-1-edit"><i class=" fas fa-microphone fa-3x"></i></a>
                                                                    <a class="record d-none" id="stop-recording-1-edit"><i class=" fas fa-microphone-slash
                         fa-3x"></i></a>
                                                                  </div>
                                                                  <div class="col-md-11">
                                                                    <audio class="audio-controls audio-sp" id="voice-note-1-edit" controls></audio>
                                                                  </div>
                                                                </div>
                                                                <small id="name" class="form-text text-muted">Record context in Spanish</small>
                                                            </div>
                                                            <div class="form-group mb-2">
                                                                <input type="file" accept=".wav" class="form-control" id="recordingMediaSpanishEdit" >
                                                                <small id="name" class="form-text text-muted">Or upload recorded file (.wav)</small>
                                                            </div>
                                                            <div class="form-group mb-2">
                                                                <div class="form-row">
                                                                  <div class="col-md-1">
                                                                    <a class="record" id="start-recording-2-edit"><i class=" fas fa-microphone fa-3x"></i></a>
                                                                    <a class="record d-none" id="stop-recording-2-edit"><i class=" fas fa-microphone-slash
                         fa-3x"></i></a>
                                                                  </div>
                                                                  <div class="col-md-11">
                                                                    <audio class="audio-controls audio-eng" id="voice-note-2-edit" controls></audio>
                                                                  </div>
                                                                </div>
                                                                <small id="name" class="form-text text-muted">Record context in English</small>
                                                            </div>
                                                            <div class="form-group mb-2">
                                                                <input type="file" accept=".wav" class="form-control" id="recordingMediaEnglishEdit" >
                                                                <small id="name" class="form-text text-muted">Or upload recorded file (.wav)</small>
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
                                                        <h4 class="modal-title" id="fullWidthModalLabel1">Phrase</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <input type="hidden" id="hidden_id_del">
                                                        <h6>Delete Phrase</h6>
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
@stop
@section('javascript')
<script>
  $(document).ready(function(){
    let recorder1;
    let recorder2;
    let recorder3;
    let recorder4;
    let audio;
    let fileName1;
    let blob1;
    let fileName2;
    let blob2;
    let fileName1Edit;
    let fileName2Edit;
    let blob1Edit;
    let blob2Edit;
    //start recording english
      $("#start-recording-2").on('click',function(){

        let device = navigator.mediaDevices.getUserMedia({audio:true})
        let chunks = []
        device.then(stream => {
          recorder2 = new MediaRecorder(stream)
          recorder2.ondataavailable = (e) => {
            chunks.push(e.data)
            if(recorder2.state == 'inactive'){
               blob2 = new Blob(chunks, {type: 'audio/webm;codecs=opus'})
               let rightNow = new Date();
               let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
               fileName2 = 'mre' + d + getRandomString(10) + '.webm'
              document.getElementById('voice-note-2').innerHTML = '<source src="' + URL.createObjectURL(blob2) + '" type="video/webm"/>'
              document.getElementById('voice-note-2').load()
              document.getElementById('voice-note-2').play()
              //const audio = new Audio(URL.createObjectURL(blob));
                //      audio.play();
            }
          }
          recorder2.start(1000)
        })
        $("#start-recording-2").addClass('d-none')
        $("#stop-recording-2").removeClass('d-none')
      })
      //start recording spanish
      $("#start-recording-1").on('click',function(){

        let device = navigator.mediaDevices.getUserMedia({audio:true})
        let chunks = []
        device.then(stream => {
          recorder1 = new MediaRecorder(stream)
          recorder1.ondataavailable = (e) => {
            chunks.push(e.data)
            if(recorder1.state == 'inactive'){
               blob1 = new Blob(chunks, {type: 'audio/webm;codecs=opus'})
               let rightNow = new Date();
               let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
               fileName1 = 'mrs' + d + getRandomString(10) + '.webm'
              document.getElementById('voice-note-1').innerHTML = '<source src="' + URL.createObjectURL(blob1) + '" type="video/webm"/>'
              document.getElementById('voice-note-1').load()
              document.getElementById('voice-note-1').play()
              //const audio = new Audio(URL.createObjectURL(blob));
                //      audio.play();
            }
          }
          recorder1.start(1000)
        })
        $("#start-recording-1").addClass('d-none')
        $("#stop-recording-1").removeClass('d-none')
      })
      //stop recording
      $("#stop-recording-2").on('click',function(){
        setTimeout(()=>{
          recorder2.stop()
        })
        $("#stop-recording-2").addClass('d-none')
        $("#start-recording-2").removeClass('d-none')
      })
      $("#stop-recording-1").on('click',function(){
        setTimeout(()=>{
          recorder1.stop()
        })
        $("#stop-recording-1").addClass('d-none')
        $("#start-recording-1").removeClass('d-none')
      })
      //save form data
      $("#submit").on('click',function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        const text_english = $("#nametextEnglish").val()
        const text_spanish = $("#nametextSpanish").val()
        const audio_english = $("#recordingMediaEnglish").prop('files')[0]
        const audio_spanish = $("#recordingMediaSpanish").prop('files')[0]
        if(text_english != '' && text_spanish != '' && ((blob1 && blob2) || (audio_english && audio_spanish) || (blob1 && audio_english) || (audio_english && blob1) || (blob2 && audio_spanish) || (audio_spanish && blob2)))
        {//blob1 == spanish
          let fd = new FormData()
          fd.append('text_english',text_english)
          fd.append('text_spanish',text_spanish)
          if(!blob1)
          {
            let rightNow = new Date();
            let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
            fileName1 = 'mrs' + d + getRandomString(10) + '.wav'
            fd.append('fileName1',fileName1)
            fd.append('file1',audio_spanish)
          }else{
            fd.append('file1',blob1)
            fd.append('fileName1',fileName1)
          }
          if(!blob2)
          {
            let rightNow = new Date();
            let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
            fileName2 = 'mre' + d + getRandomString(10) + '.wav'
            fd.append('fileName2',fileName2)
            fd.append('file2',audio_english)
          }else{
            fd.append('file2',blob2)
            fd.append('fileName2',fileName2)
          }
          console.log(blob1)
          console.log(blob2)
          fd.append("_token", "{{ csrf_token() }}")
          $.ajax({
              type:'post',
              url:'{{url('')}}/admin/phrase',
              contentType: false,
                    cache: false,
                    processData: false,
              data:fd,
              beforeSend() {
                            $('.gifimg').removeClass('d-none');
                            $('.overlay').removeClass('d-none');
                        },
              success: (res)=>{
                //console.log(res)
                $('.gifimg').addClass('d-none');
                $('.overlay').addClass('d-none');
                window.location.reload()
              },
              error: (res)=>{
                $('.gifimg').addClass('d-none');
                $('.overlay').addClass('d-none');
                console.log(res)
              }
            })
        }
        else{
          const error = `<ul>
          <li>
          Write Context
          </li>
          <li>
          Record Context
          </li>
          </ul>`
          $("#errors").html(error)
          $("#errors").removeClass('d-none')
        }
      })
      /**function download(blobData) {
  const url = window.URL.createObjectURL(blobData);
  const a = document.createElement('a');
  a.style.display = 'none';
  a.href = url;
  a.download = 'test.webm';
  document.body.appendChild(a);
  a.click();
  setTimeout(() => {
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
  }, 100);
}*/
function getRandomString(length) {
    var randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var result = '';
    for ( var i = 0; i < length; i++ ) {
        result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
    }
    return result;
}
//show delete modal
$("#dataTable").on('click','.deleteBtn',function(){
  let id = $(this).attr('data-id')
  let name = $(this).attr('data-name')
  $("#delete-text").html("<p>Are you sure want to delete <span class='text-bold'>'" + name + "'</span>? </p>")
            $("#hidden_id_del").val(id)
                    $("#full-width-modal1").modal('show')
})
//delete request
//delete vocabulary
$("#yesDelete").on('click',function(){
  let id = $("#hidden_id_del").val()
  $.ajax({
    type:'post',
    url: '{{url("")}}/admin/phrase/delete',
    data:{
      "_token": "{{csrf_token()}}",
      "id": id
    },
    beforeSend() {
                  $('.gifimg').removeClass('d-none');
                  $('.overlay').removeClass('d-none');
                  $("#full-width-modal1").modal('hide')
              },
    success: (res)=>{
      if(res == 'error'){
        $('.gifimg').removeClass('d-none');
        $('.overlay').removeClass('d-none');
        alert('enable to delete')
      }else{
        $('.gifimg').addClass('d-none');
        $('.overlay').addClass('d-none');
      window.location.reload()
      }
    },
    error: (res)=>{
      console.log(res)
    }
  })
})
//show edit modal
$("#dataTable").on('click','.updateBtn',function(){
  let name_spanish = $(this).attr('data-name-sp')
  let name_english = $(this).attr('data-name-eng')
  let id = $(this).attr('data-id')
  $("#nametextSpanishEdit").val(name_spanish)
  $("#nametextEnglishEdit").val(name_english)
  $("#hidden_id_edit").val(id)
})
//start recording english -edit
  $("#start-recording-2-edit").on('click',function(){

    let device = navigator.mediaDevices.getUserMedia({audio:true})
    let chunks = []
    device.then(stream => {
      recorder3 = new MediaRecorder(stream)
      recorder3.ondataavailable = (e) => {
        chunks.push(e.data)
        if(recorder3.state == 'inactive'){
           blob2Edit = new Blob(chunks, {type: 'audio/webm;codecs=opus'})
           let rightNow = new Date();
           let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
           fileName2Edit = 'mreE' + d + getRandomString(10) + '.webm'
          document.getElementById('voice-note-2-edit').innerHTML = '<source src="' + URL.createObjectURL(blob2Edit) + '" type="video/webm"/>'
          document.getElementById('voice-note-2-edit').load()
          document.getElementById('voice-note-2-edit').play()
          //const audio = new Audio(URL.createObjectURL(blob));
            //      audio.play();
        }
      }
      recorder3.start(1000)
    })
    $("#start-recording-2-edit").addClass('d-none')
    $("#stop-recording-2-edit").removeClass('d-none')
  })
  //start recording spanish -edit
  $("#start-recording-1-edit").on('click',function(){

    let device = navigator.mediaDevices.getUserMedia({audio:true})
    let chunks = []
    device.then(stream => {
      recorder4 = new MediaRecorder(stream)
      recorder4.ondataavailable = (e) => {
        chunks.push(e.data)
        if(recorder4.state == 'inactive'){
           blob1Edit = new Blob(chunks, {type: 'audio/webm;codecs=opus'})
           let rightNow = new Date();
           let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
           fileName1Edit = 'mrsE' + d + getRandomString(10) + '.webm'
          document.getElementById('voice-note-1-edit').innerHTML = '<source src="' + URL.createObjectURL(blob1Edit) + '" type="video/webm"/>'
          document.getElementById('voice-note-1-edit').load()
          document.getElementById('voice-note-1-edit').play()
          //const audio = new Audio(URL.createObjectURL(blob));
            //      audio.play();
        }
      }
      recorder4.start(1000)
    })
    $("#start-recording-1-edit").addClass('d-none')
    $("#stop-recording-1-edit").removeClass('d-none')
  })
  //stop recording edit
  $("#stop-recording-2-edit").on('click',function(){
    setTimeout(()=>{
      recorder3.stop()
    })
    $("#stop-recording-2-edit").addClass('d-none')
    $("#start-recording-2-edit").removeClass('d-none')
  })
  $("#stop-recording-1-edit").on('click',function(){
    setTimeout(()=>{
      recorder4.stop()
    })
    $("#stop-recording-1-edit").addClass('d-none')
    $("#start-recording-1-edit").removeClass('d-none')
  })
//edit request
$("#yesEdit").on('click',function(){
  let text_spanish = $("#nametextSpanishEdit").val()
  let text_english = $("#nametextEnglishEdit").val()
  let id = $("#hidden_id_edit").val()
  const audio_english = $("#recordingMediaEnglishEdit").prop('files')[0]
  const audio_spanish = $("#recordingMediaSpanishEdit").prop('files')[0]
  if(text_english != '' && text_spanish != '')
  {
    let fd = new FormData()
    fd.append("text_english", text_english)
    fd.append("text_spanish", text_spanish)
    if(!blob1Edit)
    {
      let rightNow = new Date();
      let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
      fileName1Edit = 'mrsE' + d + getRandomString(10) + '.wav'
      fd.append("fileNameEdit1",fileName1Edit)
      fd.append("blobEdit1",audio_spanish)
    }else{
      fd.append("blobEdit1",blob1Edit)
      fd.append("fileNameEdit1",fileName1Edit)
    }
    if(!blob2Edit)
    {
      let rightNow = new Date();
      let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
      fileName2Edit = 'mreE' + d + getRandomString(10) + '.wav'
      fd.append("fileNameEdit2",fileName2Edit)
      fd.append("blobEdit2",audio_english)
    }else{
      fd.append("blobEdit2",blob2Edit)
      fd.append("fileNameEdit2",fileName2Edit)
    }
    fd.append("id",id)
    fd.append("_token","{{csrf_token()}}")
    $.ajax({
      type: 'post',
      url: '{{url("")}}/admin/phrase/edit',
      contentType: false,
      cache: false,
      processData: false,
      data: fd,
      beforeSend() {
                    $('.gifimg').removeClass('d-none');
                    $('.overlay').removeClass('d-none');
                    $("#full-width-modal").modal('hide')
                },
      success: (res)=>{
        if(res == 'success')
        {
          window.location.reload()
        }
        else if(res == 'error')
        {
          $('.gifimg').addClass('d-none');
          $('.overlay').addClass('d-none');
          alert('error editing.')
        }
      },
      error: (res)=>{
        $('.gifimg').addClass('d-none');
        $('.overlay').addClass('d-none');
        console.log(res)
      },
    })
  }else{
    let k = `<ul>
      <li>
        Text Spanish cannot be empty
        </li>
        <li>
          Text English cannot be empty
          </li>
    </ul>`
    $("#editError").html(k)
    $("#editError").removeClass('d-none')
  }
})

  })

</script>
@stop
