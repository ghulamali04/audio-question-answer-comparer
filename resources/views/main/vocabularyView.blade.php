@extends('main/panel')
@section('content')
@if(count($vocabulary) > 0 && $vocabulary1 )
<div class="row mx-0 mb-5" style="min-height: 100vh;">
  <div class="col-lg-12">
      <div class="container py-1 mt-5 ">
          <div class="d-flex justify-content-center" id="vocabulary">

            @foreach($vocabulary as $voc)
            <a class="px-1 phrase" data-id="{{$voc->id}}"><i class="fas fa-question-circle fa-2x"></i></a>
            @endforeach

            <!--
            <a class="px-1"><i class="fas fa-check-circle fa-2x text-success"></i></a>
            <a class="px-1"><i class="fas fa-times-circle fa-2x text-danger"></i></a>-->
          </div>
      </div>
      <div class="container py-1 mt-1 ">
        <div class="card mb-3">
  <div class="card-body">
        <div class="d-flex justify-content-center text-center " >
          <img src="{{asset('public/images')}}/{{$vocabulary1->image}}" id="voc-img" class="img-fluid" style="width: 211px; height: 211px;"/>
        </div>
      </div>
    </div>
        <div class="justify-content-center mt-1">
          <div id="show-transcription" class="justify-content-center text-center">
            <button class="btn_submit" data-container="body" title="Spanish Transcription" data-toggle="popover" data-placement="top" data-content="{{$vocabulary1->text_spanish}}">Spanish</button>
          </div>
            <input type="hidden" id="text-english" value="{{$vocabulary1->text_english}}"/>
          </div>
          <div class="row justify-content-center text-center mt-1">
              <div class="col-xl-4 justify-content-center text-center">
                <p>
                  Record Your Answer
                  </p>
                  <a class="record" id="start-recording"><i class=" fas fa-microphone-alt fa-2x text-danger "></i></a>
                  <a class="record d-none" id="stop-recording"><i class=" fas fa-microphone-alt-slash fa-2x text-danger
"></i></a>
                  <div id="output"></div>
                  <input type="hidden" id="speech-recognized"/>
                </div>
            </div>
        </div>
      <div class="container py-1 pt-3" id="action">

          <div id="correct" class="d-none">
            <div class="row">
              <div class="col-md-4 col-align">
                <p class="text-success pt-1">
                  - You got it!
                </p>
              </div>
              <div class="col-md-6 col-align">
                <div id="green-transcript" >
                  <p class="pt-1">
                    <i class="fas fa-check text-success"></i>
                    <span  class="text-success pl-1">{{$vocabulary1->text_english}}</span>
                  </p>
                </div>
              </div>
              <div class="col-md-2 col-align">
                <button class="btn btn_bar btn-success float-right next" id="green-next">Next</button>
                <button class="btn btn_bar btn-success float-right d-none finish" id="green-finish">Finish</button>
              </div>
            </div>
          </div>

          <div id="partialcorrect" class="d-none">
            <div class="row">
              <div class="col-md-4 col-align">
                <p class="text-warning pt-1">
                  - You are almost right!
                </p>
              </div>
              <div class="col-md-6 col-align">
                  <div class="row">
                    <div class="col-md-4 col-align">
                      <button class="btn_bar btn btn-warning answer-play-red float-right" id="makeWrongRedPlay"><i class="fas fa-play "></i> Play</button>
                      <button class="btn_bar btn btn-warning answer-pause-red d-none float-right"><i class="fas fa-pause "></i> Pause</button>
                      <audio class="audio-controls audio-controls-table d-none" style="width: 250px;" id="english-play" controls>
                        <source src="{{url('/public/adminRecordings')}}/{{$vocabulary1->audio_english}}" type="video/webm"/>
                      </audio>
                    </div>
                    <div class="col-md-8 col-align">
                      <div id="show-transcription-orange" class="justify-content-center text-center">
                        <button class="btn btn-warning btn_bar float-left" id="makeWrongRedTranscript" data-container="body" title="English Transcription" data-toggle="popover" data-placement="top" data-content="{{$vocabulary1->text_english}}">Let me see it</button>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="col-md-2 col-align">
                <button class="btn btn_bar btn-warning float-right next" id="orange-next">Next</button>
                <button class="btn btn_bar btn-warning float-right d-none finish" id="orange-finish">Finish</button>
              </div>
            </div>
          </div>

          <div id="incorrect" class="d-none">
            <div class="row">
              <div class="col-md-4 col-align">
                <p class="text-danger pt-1">
                  - Keep Trying!
                </p>
              </div>
              <div class="col-md-6 col-align">
                <div class="row">
                  <div class="col-md-4 col-align">
                    <button class="btn_bar btn- btn-danger answer-play-red float-right" id="makeWrongOrangePlay"><i class="fas fa-play "></i></button>
                    <button class="btn_bar btn btn-danger answer-pause-red d-none float-right"><i class="fas fa-pause "></i></button>
                    <audio class="audio-controls audio-controls-table d-none" style="width:250px;" id="english-play-red" controls>
                      <source src="{{url('/public/adminRecordings')}}/{{$vocabulary1->audio_english}}" type="video/webm"/>
                    </audio>
                  </div>
                  <div class="col-md-8 col-align">
                    <div id="show-transcription-red" class="justify-content-center text-center">
                      <button class="btn btn-danger btn_bar float-left" id="makeWrongOrangeTranscript" data-container="body" title="English Transcription" data-toggle="popover" data-placement="top" data-content="{{$vocabulary1->text_english}}">Let me see it</button>
                    </div>
                    </div>
                </div>
              </div>
              <div class="col-md-2 col-align">
                <button class="btn btn_bar btn-danger float-right next" id="red-next">Next</button>
                <button class="btn btn_bar btn-danger float-right d-none finish" id="red-finish">Finish</button>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
<audio src="{{asset('public/main/sound/ring.wav')}}" type="audio/wav" class="d-none" id="play-ring"></audio>
<audio src="{{asset('public/main/sound/green-ring.wav')}}" type="audio/wav" class="d-none" id="play-ring-green"></audio>
@else
<div class="row mt-5">
  <div class="col-md-12 justify-content-center text-center">
    <p class="justify-content-center text-center">
      No Vocabulary is Assigned to you yet!
    </p>
  </div>
</div>
@endif

@stop
@section('javascript')
<script src="//unpkg.com/string-similarity/umd/string-similarity.min.js"></script><script>
    let mainList = {{$vocabularyIdList}}
    let activeList = []
    let redList = []
    let greenList = []
    let orangeList = []
    let answers_temp = []
    let answers_main = []
    let assignment = '';
    @if($assignment)
    assignment = {{$assignment->id}}
    @endif
    activeList.push(mainList[0])
    let end = 0
$("[data-toggle=popover]").popover();


    let SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition
    let recognition = new SpeechRecognition()
    let verify = null
    let makeWrong = 'no'
    let noteContent = ''
    let noteInput = $("#speech-recognized")
    let output = document.getElementById('output')
    recognition.continuous = true
    recognition.onresult = function(event) {
    // event is a SpeechRecognitionEvent object.
    // It holds all the lines we have captured so far.
    // We only need the current one.
    let current = event.resultIndex;
    // Get a transcript of what was said.
    let transcript = event.results[current][0].transcript;
    let mobileRepeatBug = (current == 1 && transcript == event.results[0][0].transcript);
    if(!mobileRepeatBug) {
      noteContent += transcript;
      noteInput.val(noteContent);
      noteInput.trigger('input')
      output.textContent = noteContent
    }
    }
    $("#start-recording").on('click',()=>{
      $("#start-recording").addClass('d-none')
      $("#stop-recording").removeClass('d-none')
      if (noteContent.length) {
        noteContent = '';
      }
      recognition.start()
    })
    $("#stop-recording").on('click',()=>{
      $("#stop-recording").addClass('d-none')
      $("#start-recording").removeClass('d-none')
      recognition.stop()
    })

    // Sync the text inside the text area with the noteContent variable.
    noteInput.on('input', function() {
      noteContent = $(this).val()
      if(!noteContent.length)
      {
        console.log('could not save')
      }else{
        let text_original = $("#text-english").val()
        answers_temp.push(noteContent)
        //let regex = /[.,]/g;
        //text_original = text_original.replace(regex, '');
        let similarity = stringSimilarity.compareTwoStrings(text_original.trim().toLowerCase(), noteContent.trim().toLowerCase())
        recognition.stop()
        if(noteContent.length > 3)
        {
          if((similarity*100) >= 90)
          {
            correct()
          }else if((similarity*100) >= 40 && (similarity*100) < 90){
            partialcorrect()
          }else if((similarity*100) < 40){
            incorrect()
          }
        }else{
          let sim = text_original.localeCompare(noteContent);

          /* Expected Returns:

0:  exact match

-1:  string_a < string_b

1:  string_a > string_b

*/
          if(sim == 0)
          {
            correct()
          }else{
            incorrect()
          }
        }
      }
    })

    function correct(){
        $("#correct").removeClass("d-none")
        $("#incorrect").addClass("d-none")
        $("#partialcorrect").addClass("d-none")
        $("#action").addClass("border")
        document.getElementById('play-ring-green').load()
        document.getElementById('play-ring-green').play()
        if(makeWrong == 'no')
        {
            verify = 1
        }else{
            verify = 0
        }
    }
    function incorrect(){
        $("#incorrect").removeClass("d-none")
        $("#partialcorrect").addClass("d-none")
        $("#correct").addClass("d-none")
        $("#action").addClass("border")
        document.getElementById('play-ring').load()
        document.getElementById('play-ring').play()
        verify = 0
    }
    function partialcorrect(){
        $("#partialcorrect").removeClass("d-none")
        $("#correct").addClass("d-none")
        $("#incorrect").addClass("d-none")
        $("#action").addClass("border")
        if(makeWrong == 'no')
        {
            verify = -1
        }else{
            verify = 0
        }
    }
    //if student study transcript or play audio make attempt wrong
    $("#makeWrongRedPlay").on('click',function(){
      makeWrong = 'yes'
    })
    $(document).on('click','#makeWrongRedTranscript',function(){
      makeWrong = 'yes'
    })
    $("#makeWrongOrangePlay").on('click',function(){
      makeWrong = 'yes'
    })
    $(document).on('click','#makeWrongOrangeTranscript',function(){
      makeWrong = 'yes'
    })
    $(document).on('click',".next",()=>{
      let difference = mainList.filter(x => !activeList.includes(x))
      if(difference.length != 0){
        $.ajax({
          type:'get',
          url:'{{url("/user/panel/vocabulary/next")}}',
          data:{
            "_token": "{{ csrf_token() }}",
            "id": difference[0]
          },
          beforeSend() {
            $('.gifimg').removeClass('d-none');
            $('.overlay').removeClass('d-none');
          },
          success:(res)=>{
            $('.gifimg').addClass('d-none');
            $('.overlay').addClass('d-none');
            //hide bottom bar
            $("#incorrect").addClass("d-none")
            $("#partialcorrect").addClass("d-none")
            $("#correct").addClass("d-none")
            $("#action").removeClass('border')
            $("#output").html('')
            //update data
            $("[data-toggle=popover]").popover('hide')
            let transcription = '<button class="btn_submit" data-container="body" title="Spanish Transcription" data-toggle="popover" data-placement="top" data-content="'+ res.text_spanish +'">See Transcription</button>'
            $("#show-transcription").html(transcription)
            $("[data-toggle=popover]").popover()
            $("#text-english").val(res.text_english)
            $("#voc-img").attr("src","{{asset('public/images')}}/"+res.image+"")
            document.getElementById('english-play').innerHTML = '<source src={{url("/public/adminRecordings")}}/' + res.audio_english + ' type="video/webm"/>'
            document.getElementById('english-play').load()
            document.getElementById('english-play-red').innerHTML = '<source src={{url("/public/adminRecordings")}}/' + res.audio_english + ' type="video/webm"/>'
            document.getElementById('english-play-red').load()
            $("[data-toggle=popover]").popover('hide')
            document.getElementById('show-transcription-red').innerHTML = '<button class="btn btn-danger btn_bar float-left" id="makeWrongRedTranscript" data-container="body" title="English Transcription" data-toggle="popover" data-placement="top" data-content="'+res.text_english+'">Let me see it</button>'
            $("[data-toggle=popover]").popover()
            $("[data-toggle=popover]").popover('hide')
            document.getElementById('show-transcription-orange').innerHTML = '<button class="btn btn-warning btn_bar float-left" id="makeWrongOrangeTranscript" data-container="body" title="English Transcription" data-toggle="popover" data-placement="top" data-content="'+res.text_english+'">Let me see it</button>'
            $("[data-toggle=popover]").popover()
            document.getElementById('green-transcript').innerHTML = '<p><i class="fas fa-check text-success"></i><span  class="text-success pl-1">' + res.text_english + '</span></p>'
            //update top panel
            let k=''
            let last_active = parseInt((activeList.length)-1)
            if(verify === 1)
            {
              greenList.push(activeList[last_active])
            }
            if(verify === 0)
            {
              redList.push(activeList[last_active])
            }
            if(verify === -1)
            {
              orangeList.push(activeList[last_active])
            }
            for(let a=0;a<activeList.length;a++)
            {
                for(let g=0;g<greenList.length;g++)
                {
                  if(activeList[a] == greenList[g])
                  {
                    k+='<a class="px-1 phrase" data-id="'+ activeList[a] +'"><i class="fas fa-check-circle text-success fa-2x"></i></a>'
                  }
                }
                for(let r=0;r<redList.length;r++)
                {
                  if(activeList[a] == redList[r])
                  {
                    k+='<a class="px-1 phrase" data-id="'+ activeList[a] +'"><i class="fas fa-times-circle text-danger fa-2x"></i></a>'
                  }
                }
                for(let o=0;o<orangeList.length;o++)
                {
                  if(activeList[a] == orangeList[o])
                  {
                    k+='<a class="px-1 phrase" data-id="'+ activeList[a] +'"><i class="fas fa-check-circle text-warning fa-2x"></i></a>'
                  }
                }
            }
            for(let i=0;i<difference.length;i++)
            {
              k+='<a class="px-1 phrase" data-id="'+ difference[i] +'"><i class="fas fa-question-circle fa-2x"></i></a>'
            }
            activeList.push(difference[0])
            let latest_answer = parseInt((answers_temp.length)-1)
            answers_main.push(answers_temp[latest_answer])
            $("#vocabulary").html(k)
            //show recording button
            $("#start-recording").removeClass('d-none')
            makeWrong = 'no'
            verify = null
          },
          error:(res)=>{
            console.log(res)
          }
        })
      }else{
        let k=''
        let last_active = parseInt((activeList.length)-1)
        if(verify === 1)
        {
          greenList.push(activeList[last_active])
        }

        if(verify === 0)
        {
          redList.push(activeList[last_active])
        }
        if(verify === -1)
        {
          orangeList.push(activeList[last_active])
        }
        for(let a=0;a<activeList.length;a++)
        {
            for(let g=0;g<greenList.length;g++)
            {
              if(activeList[a] == greenList[g])
              {
                k+='<a class="px-1 phrase" data-id="'+ activeList[a] +'"><i class="fas fa-check-circle text-success fa-2x"></i></a>'
              }
            }
            for(let r=0;r<redList.length;r++)
            {
              if(activeList[a] == redList[r])
              {
                k+='<a class="px-1 phrase" data-id="'+ activeList[a] +'"><i class="fas fa-times-circle text-danger fa-2x"></i></a>'
              }
            }
            for(let o=0;o<orangeList.length;o++)
            {
              if(activeList[a] == orangeList[o])
              {
                k+='<a class="px-1 phrase" data-id="'+ activeList[a] +'"><i class="fas fa-check-circle text-warning fa-2x"></i></a>'
              }
            }
        }
        $("#vocabulary").html(k)
        $("#green-next").addClass('d-none')
        $("#orange-next").addClass('d-none')
        $("#red-next").addClass('d-none')
        let latest_answer = parseInt((answers_temp.length)-1)
        answers_main.push(answers_temp[latest_answer])
        if(answers_main.length == mainList.length)
        {
          let fd = new FormData()
          fd.append("_token" , "{{ csrf_token() }}")
          fd.append("questions_id" , JSON.stringify(mainList))
          fd.append("answers" , JSON.stringify(answers_main))
          fd.append("redList",JSON.stringify(redList))
          fd.append("greenList",JSON.stringify(greenList))
          fd.append("orangeList",JSON.stringify(orangeList))
          fd.append("assignment", assignment)
          $.ajax({
            type:'post',
            url: '{{url("")}}/user/panel/vocabulary/answer-save',
            contentType: false,
                  cache: false,
                  processData: false,
            data:fd,
            success: (res)=>{
              //window.location.reload()
              $("#green-finish").removeClass('d-none')
              $("#orange-finish").removeClass('d-none')
              $("#red-finish").removeClass('d-none')
            },
            error: (res)=>{
              console.log(res)
            }
          })
        }
        //setTimeout(location.reload.bind(location), 4000);
        console.log(answers_main)
      }
    })
    $(".finish").on('click',function(){
      window.location.href = '{{url("")}}/user/panel'
    })
    //
    $(".answer-play-red").on('click',function(){
      $(this).addClass('d-none')
      $(".answer-pause-red").removeClass('d-none')
      $("#english-play-red").trigger('play')
    })
    $(".answer-pause-red").on('click',function(){
      $(this).addClass('d-none')
      $(".answer-play-red").removeClass('d-none')
      $("#english-play-red").trigger('pause')
    })
    $("#english-play-red").on('ended', function(){
      $(".answer-pause-red").addClass('d-none')
      $(".answer-play-red").removeClass('d-none')
})

    $(".answer-play-orange").on('click',function(){
      $("#english-play").trigger('play')
    })
    $(".answer-pause-orange").on('click',function(){
      $("#english-play").trigger('pause')
    })
    $(".answer-pause-orange").on('click',function(){
      $(".answer-pause-orange").addClass('d-none')
      $(".answer-play-orange").removeClass('d-none')
      $("#english-play").trigger('pause')
    })
</script>
@stop
