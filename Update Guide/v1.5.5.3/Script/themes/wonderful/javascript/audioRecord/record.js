jQuery(document).ready(function($) {
  try {
    window.AudioContext    = window.AudioContext || window.webkitAudioContext;
    navigator.getUserMedia = navigator.getUserMedia 
                            || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    window.URL             = window.URL || window.webkitURL;
    audio_context          = new AudioContext;
  }
  catch (e) {
    console.log('There is no support audio in this browser');
  }
  $(document).on('click',"#recordPostAudio",function(event) {
    var _SELF = $(this);
    if (!localstream) {
      Wo_CreateUserMedia();
    }
    Wo_Delay(function(){
      if(localstream && recorder && _SELF.attr('data-record') == 0 && Wo_IsRecordingBufferClean()) {
        Wo_CleanRecordNodes();
        recording_time = $('#postRecordingTime');
        recording_node = "post";
        _SELF.attr('data-record','1').html('<i class="zmdi zmdi-stop zmdi-hc-fw main"></i>');
        Wo_startRecording();
      }
      else if(localstream && recorder && _SELF.attr('data-record') == 1 && $("[data-record='1']").length == 1){
        Wo_stopRecording();
        _SELF.html('<i class="zmdi zmdi-close zmdi-hc-fw"></i>').attr('data-record','2');    
      }
      else if(localstream && recorder && _SELF.attr('data-record') == 2){
        Wo_CleanRecordNodes();
        Wo_StopLocalStream();
        _SELF.html('<i class="zmdi zmdi-mic zmdi-hc-fw"></i>').attr('data-record','0');    
      }
      else{
        return false;
      }
    },500);
  });

  $(document).on('click',".record-comment-audio",function(event) {
    var _SELF = $(this);
    if (!localstream) {
      Wo_CreateUserMedia();  
    }
    Wo_Delay(function(){
      if(recorder && _SELF.attr('data-record') == 0 && Wo_IsRecordingBufferClean()) {
        Wo_CleanRecordNodes();
        recording_time = $("span[data-comment-rtime='" + _SELF.attr('id') + "']");
        recording_node = "comm";
        comm_field     = _SELF.attr('id');
        _SELF.attr('data-record','1').html('<i class="zmdi zmdi-stop main" style="font-size: 20px;"></i>');  
        Wo_startRecording();
      }

      else if(recorder && _SELF.attr('data-record') == 1 && $("[data-record='1']").length == 1){
       Wo_stopRecording();
       _SELF.html('<i class="zmdi zmdi-close" style="font-size: 20px;"></i>').attr('data-record','2');     
      }

      else if(recorder && _SELF.attr('data-record') == 2){
       Wo_CleanRecordNodes();
       Wo_StopLocalStream();
       _SELF.html('<i class="zmdi zmdi-mic" style="font-size: 20px;"></i>').attr('data-record','0');  
      }

      else{
        return false;
      }
    },500);
    
  });

  $(document).on('click',".record-chat-audio",function(event) {
    var _SELF = $(this);
    if (!localstream) {
      Wo_CreateUserMedia(); 
    }
    Wo_Delay(function(){
      if(recorder && _SELF.attr('data-record') == 0 && Wo_IsRecordingBufferClean() && $("[data-record='1']").length == 0) {
        Wo_CleanRecordNodes();
        recording_time = $("span[data-chat-rtime='" + _SELF.attr('data-chat-tab') + "']");
        recording_node = "chat";
        chat_tab       = _SELF.attr('data-chat-tab');
        _SELF.attr('data-record','1').html('<i class="fa fa-stop main" aria-hidden="true"></i>');  
        Wo_startRecording();
      }

      else if(recorder && _SELF.attr('data-record') == 1 && $("[data-record='1']").length == 1){
       Wo_stopRecording();
       _SELF.html('<i class="fa fa-times-circle-o" aria-hidden="true"></i>').attr('data-record','2');     
      }

      else if(recorder && _SELF.attr('data-record') == 2){
       Wo_CleanRecordNodes();
       Wo_StopLocalStream();
       _SELF.html('<i class="fa fa-microphone"></i>').attr('data-record','0');  
      }

      else{
        return false;
      }
    },500);
    
  });

  $(document).on('click',"#messages-record",function(event) {
    var _SELF = $(this);
    if (!localstream) {
      Wo_CreateUserMedia(); 
    }
    Wo_Delay(function(){
      if(recorder && _SELF.attr('data-record') == 0 && Wo_IsRecordingBufferClean() && $("[data-record='1']").length == 0) {
        Wo_CleanRecordNodes();
        recording_time = $("span.messages-rtime");
        recording_node = "msg";
        _SELF.attr('data-record','1').html('<i class="fa fa-stop" aria-hidden="true"></i>');  
        Wo_startRecording();
      }

      else if(recorder && _SELF.attr('data-record') == 1 && $("[data-record='1']").length == 1){
       Wo_stopRecording();
       _SELF.html('<i class="fa fa-times-circle-o" aria-hidden="true"></i>').attr('data-record','2');     
      }

      else if(recorder && _SELF.attr('data-record') == 2){
       Wo_CleanRecordNodes();
       Wo_StopLocalStream();
       _SELF.html('<i class="fa fa-microphone"></i>').attr('data-record','0');  
      }

      else{
        return false;
      }
    },500);
    
  });
});

function Wo_IsRecordingBufferClean(){
  return $("[data-record='1']").length == 0; 
}

function Wo_CreateUserMedia(){
  navigator.getUserMedia({audio: true}, Wo_startUserMedia, function(e) {
    console.log('Could not get input or something went wrong: ' + e);
  }); 
}
function Wo_CleanRecordNodes(){
  $(".record-comment-audio").each(function(index, el) {
    $(el).html('<i class="zmdi zmdi-mic" style="font-size: 20px;"></i>').attr('data-record', '0');
    $('[data-comment-rtime="'+$(el).attr('id')+'"]').text('00:00').addClass('hidden');
  });

  $(".record-chat-audio").each(function(index, el) {
    $(el).html('<i class="fa fa-microphone"></i>').attr('data-record', '0');
    $('[data-chat-rtime="'+$(el).attr('data-chat-tab')+'"]').text('00:00').addClass('hidden');
  });

  recorder    &&         recorder.clear();
  recorder    && clearTimeout(wo_timeout);
  Wo_clearPRecording();
  Wo_clearMRecording();
}

function Wo_ClearTimeout(){
  clearTimeout(wo_timeout);
}
function Wo_ShowRecordingTime(self) {
  var time      = self.text();
  var seconds   = time.split(":");
  var date      = new Date();
  date.setHours(0);
  date.setMinutes(seconds[0]);
  date.setSeconds(seconds[1]);
  var __date    = new Date(date.valueOf() + 1000);
  var temp      = __date.toTimeString().split(" ");
  var timeST    = temp[0].split(":");
  if (timeST[1] >= 10) {
    Wo_ClearTimeout();
    Wo_stopRecording();
  }
  else{
    self.text(timeST[1]+":"+timeST[2]);
    wo_timeout    = setTimeout(Wo_ShowRecordingTime,1000,recording_time)  
  }

}
  var audio_context,recorder,recording_time,wo_timeout,localstream,recording_node,chat_tab,comm_field;
function Wo_startUserMedia(stream) {
  localstream   = stream;
  var input     = audio_context.createMediaStreamSource(stream);
  if (input) {
    recorder    = new Recorder(input,{bufferLen:16384});
  }
  else{
    console.log('Could not initialize media stream');
  }
}

function Wo_startRecording() {
  recorder     &&    recorder.record();
  recording_time.removeClass('hidden');
  recorder     && recorder.exportWAV(function(blob){});
  recorder     && setTimeout(Wo_ShowRecordingTime,1000,recording_time);
  //console.log('recording started');
}

function Wo_stopRecording() {
  recorder     &&          recorder.stop();
  wo_timeout   && clearTimeout(wo_timeout);
  //recorder     && console.log('recording sotopped');
}

function Wo_StopLocalStream(){
  localstream  && localstream.getTracks().forEach(function(track) { track.stop() });
  localstream    = false;
  recording_node = false;
  delete(recorder);
}

function Wo_clearPRecording(){
  recorder       &&                  recorder.clear();
  recording_time &&      recording_time.text('00:00');
  recorder       &&          clearTimeout(wo_timeout);
  recording_time && recording_time.addClass('hidden');
  $("#recordPostAudio").html('<i class="zmdi zmdi-mic zmdi-hc-fw"></i>').attr('data-record','0');
}

function Wo_clearMRecording(){
  recorder       &&                  recorder.clear();
  recording_time &&      recording_time.text('00:00');
  recorder       &&          clearTimeout(wo_timeout);
  recording_time && recording_time.addClass('hidden');
  $("#messages-record").html('<i class="fa fa-microphone" aria-hidden="true"></i>').attr('data-record','0');
}

function Wo_GetPRecordLink() {
  if (recorder && recording_node == "post") {
    recorder.exportWAV(function(blob) {
      if (blob instanceof Blob && blob.size > 50) {
        var fileName   = (new Date).toISOString().replace(/:|\./g, '-');
        var file       = new File([blob], 'wo-' + fileName + '.wav', {type: 'audio/wav'});
        var dataForm   = new FormData();
        dataForm.append('audio-filename', file.name);
        dataForm.append('audio-blob', file);
        Wo_RegisterPost(dataForm);
      }
      else{$('form.post').submit()}

    });
  }
  else{$('form.post').submit()}
}

function Wo_GetMRecordLink() {
  if (recorder && recording_node == "msg") {
    recorder.exportWAV(function(blob) {
      if (blob instanceof Blob && blob.size > 50) {
        var fileName   = (new Date).toISOString().replace(/:|\./g, '-');
        var file       = new File([blob], 'AU-' + fileName + '.wav', {type: 'audio/wav'});
        var dataForm   = new FormData();
        dataForm.append('audio-filename', file.name);
        dataForm.append('audio-blob', file);
        Wo_RegisterMessage(dataForm);
      }
      else{$('form.sendMessages').submit()}

    });
  }
  else{$('form.sendMessages').submit()}
}

function Wo_RegisterTabMessage(id) {

  if (!id) {
    return false;
  }
  
  if (recorder && recording_node == "chat" && id == chat_tab) {
    recorder.exportWAV(function(blob) {
      if (blob instanceof Blob && blob.size > 50) {
        var fileName   = (new Date).toISOString().replace(/:|\./g, '-');
        var file       = new File([blob], 'AU-' + fileName + '.wav', {type: 'audio/wav'});
        var dataForm   = new FormData();
        dataForm.append('audio-filename', file.name);
        dataForm.append('audio-blob', file);
        Wo_RegisterTabMessageRecord(dataForm,id);
      }
      else{$('form.chat-sending-form-'+id).submit();}

    });
  }
  else{$('form.chat-sending-form-'+id).submit();}
}

function Wo_RegisterTabMessageRecord(dataForm,id){
  if (dataForm && id) {
    $.ajax({
        url: Wo_Ajax_Requests_File() + "?f=chat&s=register_message_record",
        type:       'POST',
        cache:       false,
        dataType:   'json',
        data:   dataForm,
        processData: false,
        contentType: false,
    }).done(function(data) {
      if(data.status == 200){
        Wo_stopRecording();
        Wo_CleanRecordNodes();
        Wo_StopLocalStream();
        $('form.chat-sending-form-'+id).find('input.message-record').val(data.url);
        $('form.chat-sending-form-'+id).find('input.media-name').val(data.name);
        $('form.chat-sending-form-'+id).submit();
      }
    });
  }
}

function Wo_RegisterPost(dataForm){
  if (dataForm) {
    $.ajax({
        url: Wo_Ajax_Requests_File() + "?f=posts&s=register_post_record",
        type:       'POST',
        cache:       false,
        dataType:   'json',
        data:   dataForm,
        processData: false,
        contentType: false,
    }).done(function(data) {
      if(data.status == 200){
        Wo_stopRecording();
        Wo_clearPRecording();
        Wo_StopLocalStream();
        $("#postRecord").val(data.url)
        $('form.post').submit()
      }
    });
  }
}

function Wo_RegisterMessage(dataForm){
  if (dataForm) {
    $.ajax({
        url: Wo_Ajax_Requests_File() + "?f=messages&s=upload_record",
        type:       'POST',
        cache:       false,
        dataType:   'json',
        data:   dataForm,
        processData: false,
        contentType: false,
    }).done(function(data) {
      if(data.status == 200){
        Wo_stopRecording();
        Wo_clearMRecording();
        Wo_StopLocalStream();
        $("#message-record-file").val(data.url);
        $("#message-record-name").val(data.name);
        $('form.sendMessages').submit();
      }
    });
  }
}

function Wo_RegisterComment(text, post_id, user_id, event, page_id, type) {
  if(event.keyCode == 13 && event.shiftKey == 0 && recording_node == "comm") {
    Wo_stopRecording(); 
    if (recorder) { 
      recorder.exportWAV(function(blob){
        var comment_src_image = $('#post-' + post_id).find('#comment_src_image');
        var comment_image = '';
        if (comment_src_image.length > 0) {
          comment_image = comment_src_image.val();
        }       
        var dataForm = new FormData();                    
        dataForm.append('post_id',            post_id);
        dataForm.append('text',                  text);
        dataForm.append('user_id',            user_id);
        dataForm.append('page_id',            page_id);
        dataForm.append('comment_image',comment_image);
        if (blob.size > 50) {
          var fileName   = (new Date).toISOString().replace(/:|\./g, '-');
          var file       = new File([blob], 'wo-' + fileName + '.wav', {type: 'audio/wav'});
          dataForm.append('audio-filename', file.name);
          dataForm.append('audio-blob', file);
        }
        Wo_InsertComment(dataForm,post_id);
      });
    }

    else{
        var comment_src_image = $('#post-' + post_id).find('#comment_src_image');
        var comment_image = '';
        if (comment_src_image.length > 0) {
          comment_image = comment_src_image.val();
        }       
        var dataForm = new FormData();                    
        dataForm.append('post_id',            post_id);
        dataForm.append('text',                  text);
        dataForm.append('user_id',            user_id);
        dataForm.append('page_id',            page_id);
        dataForm.append('comment_image',comment_image); 
        Wo_InsertComment(dataForm,post_id);
    }
  }
}

function Wo_InsertComment(dataForm,post_id){
    if (!dataForm) { return false;}
    post_wrapper = $('[id=post-' + post_id + ']');
    comment_textarea = post_wrapper.find('.post-comments');
    comment_btn = comment_textarea.find('.emo-comment');
    textarea_wrapper = comment_textarea.find('.textarea');
    comment_list = post_wrapper.find('.comments-list');   
    //event.preventDefault();
    textarea_wrapper.val('');
    Wo_progressIconLoader(comment_btn);
    $.ajax({
        url: Wo_Ajax_Requests_File() + '?f=posts&s=register_comment',
        type:       'POST',
        cache:       false,
        dataType:   'json',
        data:   dataForm,
        processData: false,
        contentType: false,
    }).done(function(data) {
      if(data.status == 200) {
        Wo_CleanRecordNodes();
        post_wrapper.find('.post-footer .comment-container:first-child').before(data.html);
        post_wrapper.find('.lightbox-post-footer .comment-container:first').before(data.html);
        post_wrapper.find('[id=comments]').html(data.comments_num);
        post_wrapper.find('.lightbox-no-comments').remove();
        Wo_StopLocalStream();
      }
      $('#post-'+ post_id).find('.comment-image-con').empty().addClass('hidden');
      $('#post-'+ post_id).find('#comment_src_image').val('');
      Wo_progressIconLoader(comment_btn);
      if (data.can_send == 1) {
        Wo_SendMessages();
      }
    });
   
}


