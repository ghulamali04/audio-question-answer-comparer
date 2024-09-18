@extends('admin/layout')
@section('css')
<link href="{{asset('public/admin/dist/css/player.css')}}" rel="stylesheet"/>
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
                                <h4 class="card-title">MAKE A VOCABULARY</h4>
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
                                      <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputGroupFile0">
                                            <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                        </div>
                                        <small id="name" class="form-text text-muted">Upload related Image</small>
                                    </div>
                                    <div class="form-group mb-2">
                                        <div class="form-row">
                                          <div class="col-md-1">
                                            <a class="record" id="start-recording"><i class="fas fa-microphone fa-3x"></i></a>
                                            <a class="record d-none" id="stop-recording"><i class="fas fa-microphone-slash
 fa-3x"></i></a>
                                          </div>
                                          <div class="col-md-11">
                                            <audio class="audio-controls audio-eng" id="voice-note" controls></audio>
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
                    <h4 class="card-title">PREVIOUS VOCABULARY</h4>
                    <h6 class="card-subtitle">Click <code>edit</code> button to modify & click on <code>delete</code> button to delete.</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Word</th>
                                <th scope="col">
                                  Audio English
                                </th>
                                <th scope="col">
                                  Image
                                </th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                            @if($vocabulary)
                              @foreach($vocabulary as $v)
                              <tr>
                                  <th scope="row">{{$loop->iteration}}</th>
                                  <td>{{$v->text_spanish}}</td>
                                  <td>
                                    <audio class="audio-controls audio-controls-table" controls>
                                      <source src="{{url('/public/adminRecordings')}}/{{$v->audio_english}}" type="video/webm"/>
                                    </audio>
                                  </td>
                                  <td>
                                    <div class="container-fluid">
                                      <img class="img-fluid" style="width:50px;height:50px;" src="{{asset('public/images')}}/{{$v->image}}"/>
                                    </div>
                                  </td>
                                  <td>
                                    <a data-id="{{$v->id}}" data-img="{{$v->image}}" data-audio="{{$v->audio_english}}" data-name-sp="{{$v->text_spanish}}" data-name-eng="{{$v->text_english}}" class="updateBtn" data-toggle="modal" data-target="#full-width-modal" style="cursor:  pointer;"><i class="fas fa-edit text-warning"></i></a>

                                    <a data-name="{{$v->text_spanish}}" data-id="{{$v->id}}" class="deleteBtn" data-toggle="modal" data-target="#full-width-modal1" style="cursor:  pointer;"><i class=" fas fa-trash-alt
 text-danger"></i></a>
                                  </td>
                              </tr>
                              @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                                    <tr class="active">
                                        <td colspan="5">
                                            <div class="dataTables_paginate paging_simple_numbers">
                                            @if ($vocabulary->lastPage() > 1)

<ul class="pagination">
    @if($vocabulary->currentPage() != 1 && $vocabulary->lastPage() >= 5)
    <li class="paginate_button page-item">
        <a href="{{ $vocabulary->url($vocabulary->url(1)) }}" class="page-link" aria-label="Previous">
            <span aria-hidden="true">First</span>
        </a>
    </li>
    @endif
    @if($vocabulary->currentPage() != 1)
    <li class="paginate_button page-item">
        <a href="{{ $vocabulary->url($vocabulary->currentPage()-1) }}" class="page-link" aria-label="Previous">
            <span aria-hidden="true">&#x3C;</span>
        </a>
    </li>
    @endif
    @for($i = max($vocabulary->currentPage()-2, 1); $i <= min(max($vocabulary->currentPage()-2, 1)+4,$vocabulary->lastPage()); $i++)
        @if($vocabulary->currentPage() == $i)
        <li class="paginate_button page-item active">
            @else
        <li class="paginate_button page-item ">
            @endif
            <a href="{{ $vocabulary->url($i) }}" class="page-link">{{ $i }}</a>
        </li>
        @endfor
        @if ($vocabulary->currentPage() != $vocabulary->lastPage())
        <li class="paginate_button page-item ">
            <a href="{{ $vocabulary->url($vocabulary->currentPage()+1) }}" class="page-link" aria-label="Next">
                <span aria-hidden="true">&#x3E;</span>
            </a>
        </li>
        @endif
        @if ($vocabulary->currentPage() != $vocabulary->lastPage() && $vocabulary->lastPage() >= 5)
        <li class="paginate_button page-item ">
            <a href="{{ $vocabulary->url($vocabulary->lastPage()) }}" class="page-link" aria-label="Next">
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
                                                        <h4 class="modal-title" id="fullWidthModalLabel">Vocabulary</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <input type="hidden" id="hidden_id_edit">
                                                    <input type="hidden" id="audioEdit"/>
                                                    <input type="hidden" id="imageEdit"/>
                                                        <h6>Edit Vocabulary</h6>
                                                        <div class="alert alert-danger d-none" id="editError">

                                                        </div>
                                                        <form>
                                                          <div class="form-group mb-2">
                                                              <input type="text" class="form-control" id="nametextSpanishEdit" aria-describedby="name" placeholder="Name">
                                                              <small id="name" class="form-text text-muted">Write context in Spanish</small>
                                                          </div>
                                                          <div class="form-group mb-2">
                                                              <input type="text" class="form-control" id="nametextEnglishEdit" aria-describedby="name" placeholder="Name">
                                                              <small id="name" class="form-text text-muted">Write context in English</small>
                                                          </div>
                                                          <div class="form-group mb-2">
                                                            <div class="custom-file">
                                                                  <input type="file" class="custom-file-input" id="inputGroupFile0Edit">
                                                                  <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                                              </div>
                                                              <small id="name" class="form-text text-muted">Upload related Image</small>
                                                          </div>
                                                          <div class="form-group mb-2">
                                                              <div class="form-row">
                                                                <div class="col-1">
                                                                  <a class="record" id="start-recording-edit"><i class=" fas fa-microphone fa-3x"></i></a>
                                                                  <a class="record d-none" id="stop-recording-edit"><i class=" fas fa-microphone-slash
                       fa-3x"></i></a>
                                                                </div>
                                                                <div class="col-11">
                                                                  <audio class="audio-controls audio-eng" id="voice-note-edit" controls></audio>
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
                                                        <h4 class="modal-title" id="fullWidthModalLabel1">Vocabulary</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <input type="hidden" id="hidden_id_del">
                                                        <h6>Delete Vocabulary</h6>
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
    let audio;
    let fileName;
    let blob;
    let fileNameEdit;
    let blobEdit;
      //start recording spanish
      $("#start-recording").on('click',function(){

        let device = navigator.mediaDevices.getUserMedia({audio:true})
        let chunks = []
        device.then(stream => {
          recorder1 = new MediaRecorder(stream)
          recorder1.ondataavailable = (e) => {
            chunks.push(e.data)
            if(recorder1.state == 'inactive'){
               blob = new Blob(chunks, {type: 'audio/webm;codecs=opus'})
               let rightNow = new Date();
               let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
               fileName = 'mrv' + d + getRandomString(10) + '.webm'
              document.getElementById('voice-note').innerHTML = '<source src="' + URL.createObjectURL(blob) + '" type="video/webm"/>'
              document.getElementById('voice-note').load()
              document.getElementById('voice-note').play()
              //const audio = new Audio(URL.createObjectURL(blob));
                //      audio.play();
            }
          }
          recorder1.start(1000)
        })
        $("#start-recording").addClass('d-none')
        $("#stop-recording").removeClass('d-none')
      })
      //stop recording
      $("#stop-recording").on('click',function(){
        setTimeout(()=>{
          recorder1.stop()
        })
        $("#stop-recording").addClass('d-none')
        $("#start-recording").removeClass('d-none')
      })
      //save form data
      $("#submit").on('click',function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        const text_spanish = $("#nametextSpanish").val()
        const text_english = $("#nametextEnglish").val()
        const image = $("#inputGroupFile0").prop('files')[0]
        const audio_english = $("#recordingMediaEnglish").prop('files')[0]
        if(text_spanish != '' && text_english != '' && image && (blob || audio_english))
        {
                      var fileType = image.type;

                      var match = ['image/jpeg', 'image/png', 'image/jpg'];
                      if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]))) {
                        const error = `<ul>
                        <li>
                        Only Jpeg,Png & Jpg files are allowed to upload.
                        </li>
                        </ul>`
                        $("#errors").html(error)
                        $("#errors").removeClass('d-none')
                          $("#inputGroupFile0").val('');
                          return false;
                      }else{
                        let fd = new FormData()
                        fd.append('text_spanish',text_spanish)
                        fd.append('text_english',text_english)
                        if(!blob)
                        {
                          let rightNow = new Date();
                          let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
                          fileName = 'mrv' + d + getRandomString(10) + '.wav'
                          fd.append('file',audio_english)
                          fd.append('fileName',fileName)
                        }else{
                          fd.append('file',blob)
                          fd.append('fileName',fileName)
                        }
                        fd.append('image',image)
                        fd.append("_token", "{{ csrf_token() }}")
                          $.ajax({
                            type:'post',
                            url:'{{url('')}}/admin/vocabulary',
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


        }
        else{
          const error = `<ul>
          <li>
          Enter Word in Spanish
          </li>
          <li>
          Enter word in Enlish
          </li>
          <li>
          Record Answer
          </li>
          <li>
          Upload Image
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

//delete modal show
$("#dataTable").on('click','.deleteBtn',function(){
  let id = $(this).attr('data-id')
  let name = $(this).attr('data-name')
  $("#delete-text").html("<p>Are you sure want to delete <span class='text-bold'>'" + name + "'</span>? </p>")
            $("#hidden_id_del").val(id)
                    $("#full-width-modal1").modal('show')
})
//delete vocabulary
$("#yesDelete").on('click',function(){
  let id = $("#hidden_id_del").val()
  $.ajax({
    type:'post',
    url: '{{url("")}}/admin/vocabulary/delete',
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
      console.log(res)
      if(res == 'error'){
        $('.gifimg').addClass('d-none');
        $('.overlay').addClass('d-none');
        alert('enable to delete')

      }else{
      window.location.reload()
      }
    },
    error: (res)=>{
      $('.gifimg').addClass('d-none');
      $('.overlay').addClass('d-none');
      console.log(res)
    }
  })
})
//edit modal show
$("#dataTable").on('click','.updateBtn',function(){
  let name_spanish = $(this).attr('data-name-sp')
  let name_english = $(this).attr('data-name-eng')
  let image = $(this).attr('data-img')
  let audio = $(this).attr('data-audio')
  let id = $(this).attr('data-id')
  $("#nametextSpanishEdit").val(name_spanish)
  $("#nametextEnglishEdit").val(name_english)
  $("#imageEdit").val(image)
  $("#audioEdit").val(audio)
  $("#hidden_id_edit").val(id)
})
//edit english recorder
$("#start-recording-edit").on('click',function(){

  let device = navigator.mediaDevices.getUserMedia({audio:true})
  let chunks = []
  device.then(stream => {
    recorder2 = new MediaRecorder(stream)
    recorder2.ondataavailable = (e) => {
      chunks.push(e.data)
      if(recorder2.state == 'inactive'){
         blobEdit = new Blob(chunks, {type: 'audio/webm;codecs=opus'})
         let rightNow = new Date();
         let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
         fileNameEdit = 'mrvE' + d + getRandomString(10) + '.webm'
        document.getElementById('voice-note-edit').innerHTML = '<source src="' + URL.createObjectURL(blobEdit) + '" type="video/webm"/>'
        document.getElementById('voice-note-edit').load()
        document.getElementById('voice-note-edit').play()
        //const audio = new Audio(URL.createObjectURL(blob));
          //      audio.play();
      }
    }
    recorder2.start(1000)
  })
  $("#start-recording-edit").addClass('d-none')
  $("#stop-recording-edit").removeClass('d-none')
})
//stop recording
$("#stop-recording-edit").on('click',function(){
  setTimeout(()=>{
    recorder2.stop()
  })
  $("#stop-recording-edit").addClass('d-none')
  $("#start-recording-edit").removeClass('d-none')
})
//save edited vocabulary
$("#yesEdit").on('click',function(){
  let text_spanish = $("#nametextSpanishEdit").val()
  let text_english = $("#nametextEnglishEdit").val()
  let id = $("#hidden_id_edit").val()
  let image = $("#inputGroupFile0Edit").prop('files')[0]
  const audio_english = $("#recordingMediaEnglishEdit").prop('files')[0]
  let valid = 0
  if(image != '' && image != undefined && image != null)
  {
    console.log(image)
    var fileType = image.type;

    var match = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]))) {
      const error = `<ul>
      <li>
      Only Jpeg,Png & Jpg files are allowed to upload.
      </li>
      </ul>`
      $("#editError").html(error)
      $("#editError").removeClass('d-none')
        $("#inputGroupFile0Edit").val('');
        return false;
    }else{
      valid = 1
    }
  }else{
    valid = 1
  }
  /**
  if(!blobEdit && !fileNameEdit)
  {
    fileNameEdit = $("#audioEdit").val()
  }*/
  if(text_english != '' && text_spanish != '' && valid == 1)
  {
    let fd = new FormData()
    fd.append("text_english", text_english)
    fd.append("text_spanish", text_spanish)
    fd.append("image", image)
    if(!blobEdit)
    {
      let rightNow = new Date();
      let d = rightNow.toISOString().slice(0,10).replace(/-/g,"");
      fileNameEdit = 'mrvE' + d + getRandomString(10) + '.wav'
      fd.append('blobEdit',audio_english)
      fd.append('fileNameEdit',fileNameEdit)
    }else{
      fd.append("blobEdit", blobEdit)
      fd.append("fileNameEdit", fileNameEdit)
    }
    fd.append("id",id)
    fd.append("_token","{{csrf_token()}}")
    $.ajax({
      type: 'post',
      url: '{{url("")}}/admin/vocabulary/edit',
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
          $('.gifimg').addClass('d-none');
          $('.overlay').addClass('d-none');
          window.location.reload()
        }
        else if(res == 'error')
        {
          $('.gifimg').addClass('d-none');
          $('.overlay').addClass('d-none');
          alert('enable to edit')
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
