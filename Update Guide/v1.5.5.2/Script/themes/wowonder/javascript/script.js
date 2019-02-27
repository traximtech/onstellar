current_notification_number = 0;
current_notification_number = 0;
current_messages_number = 0;
current_follow_requests_number = 0;

current_width = $(window).width();
document_title = document.title;

$(function () {
  
  $(window).on("dragover",function(e){
    e.preventDefault();
  },false);

  $(window).on("drop",function(e){
    e.preventDefault();
  },false);

  $('.postText').autogrow({vertical: true, horizontal: false, height: 200});
  $('#movies-comment').autogrow({vertical: true, horizontal: false, height: 200});
  $('#blog-comment').autogrow({vertical: true, horizontal: false, height: 200});
  var api = $('#api').val();
  var hash = $('.main_session').val();
  $.ajaxSetup({ 
    data: {
        hash: hash
    },
    cache: false 
  });
  $(document).on("click",".mfp-arrow",function(event) {
    Wo_StoryProgress();
  });
  $(document).on('click', '.messages-recipients-list', function(event) {
    scrollToTop();
  });
  $('[data-toggle="tooltip"]').tooltip();
  // open last active tab
  var url = document.location.toString();
  if(url.match('#')) {
    var val_hash = url.split('#')[1];
    $('.nav-tabs a[href="#' + val_hash + '"]').tab('show');
  }
  $('.nav-tabs a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
    $('body').scrollTop(0);
  });
   $('.filterby li.filter-by-li').on('click', function (e) {
    $('.filterby li.filter-by-li').each(function(){
      $(this).removeClass('avtive');
      $(this).find('i').addClass('hidden');
    });
     $(this).find('i').removeClass('hidden');
     $(this).addClass('avtive');
   });
    intervalUpdates = setTimeout(Wo_intervalUpdates, 6000);
    setTimeout(Wo_UpdateLastSeen, 40000);
    setTimeout(Wo_IsLogged, 30000);

  //  dropdown won't close on click
  $('.dropdown-menu.request-list, .dropdown-menu.post-recipient, .dropdown-menu.post-options').click(function (e) {
    e.stopPropagation();
  }); 
  scrolled = 0;
  // Stick the home side bar if the screen width is > than 900
  if(current_width > 900 || api) {
    $(window).scroll(function () {
      if($('.footer-wrapper').scrollTop() > 500) {
        $('.footer-wrapper .dropdown-menu').css('bottom', 'auto');
      } else {
        $('.footer-wrapper .dropdown-menu').css('bottom', '100%');
      }
      if($(document).scrollTop() > 200) {
        $('.sidebar-sticky').addClass('Stick');
      } else {
        $('.sidebar-sticky').removeClass('Stick');
      }
      var nearToBottom = 100;
      if($('#posts').length > 0) {
          if ($(window).scrollTop() + $(window).height() > $(document).height() - nearToBottom) {
            if (scrolled == 0) {
               scrolled = 1;
               Wo_GetMorePosts();
            }
          }
      }
    });
  }
});


function Wo_CloseModels() {
  $('.modal').modal('hide');
}
// update user last seen
function Wo_UpdateLastSeen() {
  $.get(Wo_Ajax_Requests_File(), {
    f: 'update_lastseen'
  }, function () {
    setTimeout(Wo_UpdateLastSeen, 40000);
  });
}
// js function
function Wo_CheckUsername(username) {
  var check_container = $('.checking');
  var check_input = $('#username').val();
  if(check_input == '') {
    check_container.empty();
    return false;
  }
  check_container.removeClass('unavailable').removeClass('available').html('<i class="fa fa-clock-o"></i><span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
  $.get(Wo_Ajax_Requests_File(), {
    f: 'check_username',
    username: username
  }, function (data) {
    if(data.status == 200) {
      check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass('unavailable').addClass('available');
    } else if(data.status == 300) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    } else if(data.status == 400) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    } else if(data.status == 500) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    } else if(data.status == 600) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    }
  });
}

function Wo_CheckPagename(pagename, page_id) {
  var check_container = $('.checking');
  var check_input = $('#page_name').val();
  if(check_input == '') {
    check_container.empty();
    return false;
  }
  check_container.removeClass('unavailable').removeClass('available').html('<i class="fa fa-clock-o"></i><span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
  $.get(Wo_Ajax_Requests_File(), {
    f: 'check_pagename',
    pagename: pagename,
    page_id: page_id
  }, function (data) {
    if(data.status == 200) {
      check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass('unavailable').addClass('available');
    } else if(data.status == 300) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    } else if(data.status == 400) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    } else if(data.status == 500) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    } else if(data.status == 600) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    }
  });
}

function Wo_CheckGroupname(groupname, group_id) {
  var check_container = $('.checking');
  var check_input = $('#group_name').val();
  if(check_input == '') {
    check_container.empty();
    return false;
  }
  check_container.removeClass('unavailable').removeClass('available').html('<i class="fa fa-clock-o"></i><span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
  $.get(Wo_Ajax_Requests_File(), {
    f: 'check_groupname',
    groupname: groupname,
    group_id:group_id
  }, function (data) {
    if(data.status == 200) {
      check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass('unavailable').addClass('available');
    } else if(data.status == 300) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    } else if(data.status == 400) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    } else if(data.status == 500) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    } else if(data.status == 600) {
      check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
    }
  });
}

// scroll to top function
function scrollToTop() {
  verticalOffset = typeof (verticalOffset) != 'undefined' ? verticalOffset : 0;
  element = $('html');
  offset = element.offset();
  offsetTop = offset.top;
  $('html, body').animate({
    scrollTop: offsetTop
  }, 300, 'linear');
}

// check if user is logged in function
function Wo_IsLogged() {
  $.post(Wo_Ajax_Requests_File() + '?f=session_status', function (data) {
    setTimeout(Wo_UpdateLastSeen, 30000);
    if(data.status == 200) {
      $('#logged-out-modal').modal({
        show: true
      });
    }
  });
}

// side bar users
function Wo_ReloadSideBarUsers() {
  Wo_progressIconLoader($('#sidebar-user-list-container').find('.refresh'));
  $.get(Wo_Ajax_Requests_File(), {
    f: 'update_sidebar_users'
  }, function (data) {
    if(data.status == 200) {
      $('.sidebar-users-may-know-container').html(data.html);
    }
    Wo_progressIconLoader($('#sidebar-user-list-container').find('.refresh'));
  });
}

function Wo_ReloadSideBarGroups() {
  Wo_progressIconLoader($('#sidebar-group-list-container').find('.refresh'));
  $.get(Wo_Ajax_Requests_File(), {
    f: 'update_sidebar_groups'
  }, function (data) {
    if(data.status == 200) {
      $('.sidebar-group-may-know-container').html(data.html);
    }
    Wo_progressIconLoader($('#sidebar-group-list-container').find('.refresh'));
  });
}

// side bar pages
function Wo_ReloadSideBarPages() {
  Wo_progressIconLoader($('#sidebar-page-list-container').find('.refresh'));
  var page_id = $('.sidebar-pages-may-know-container').find('.sidebar-page-data').attr('data-page-id');
  if (page_id == 'undefined') {
      page_id = '';
  }
  $.get(Wo_Ajax_Requests_File(), {
    f: 'pages',
    s: 'get_next_page',
    page_id: page_id
  }, function (data) {
    if(data.status == 200) {
      if (data.html.length == 0) {
        $('.sidebar-pages-may-know-container').html('<h2><div class="no-more-pages text-center">No more pages to like</div></h2>');
      } else {
        $('.sidebar-pages-may-know-container').hide().html(data.html).fadeIn(300);
      }
    }
    Wo_progressIconLoader($('#sidebar-page-list-container').find('.refresh'));
  });
}

// get new notifications
function Wo_OpenNotificationsMenu() {
  notification_container = $('.notification-container');
  notification_list = $('#notification-list');
  notification_container.find('.new-update-alert').addClass('hidden');
  Wo_progressIconLoader(notification_container.find('.notification-loading-progress'));
  $.get(Wo_Ajax_Requests_File(), {
    f: 'get_notifications'
  }, function (data) {
    if(data.status == 200) {
      if(data.html.length == 0) {
        notification_list.html('<span class="center-text padding-10"><svg style="width: 100%;color: #03A9F4;margin: 15px 0px 15px 0px;" xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.56 2.9A7 7 0 0 1 19 9v4m-2 4H2a3 3 0 0 0 3-3V9a7 7 0 0 1 .78-3.22M13.73 21a2 2 0 0 1-3.46 0"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>' + data.message + '</span>');
      } else {
        notification_list.html(data.html);
        Wo_intervalUpdates();
      }
    }
    Wo_progressIconLoader(notification_container.find('.notification-loading-progress'));
  });
}
function Wo_OpenMessagesMenu() {
  messages_container = $('.messages-notification-container');
  messages_list = $('#messages-list');
  Wo_progressIconLoader(messages_container.find('.notification-loading-progress'));
  $.get(Wo_Ajax_Requests_File(), {
    f: 'get_messages'
  }, function (data) {
    if(data.status == 200) {
      if(data.html.length == 0) {
        messages_list.html('<span class="center-text padding-10"><svg style="width: 100%;color: #4caf50;margin: 15px 0px 15px 0px;" xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>' + data.message + '</span>');
      } else {
        messages_list.html(data.html);
        messages_list.append('<div class="show-message-link-container"><a href="' + data.messages_url + '" class="show-message-link" data-ajax="?link1=messages">' + data.messages_text + '</a></div>');
        //Wo_intervalUpdates();
      }
    }
    Wo_progressIconLoader(messages_container.find('.notification-loading-progress'));
  });
}
// get new friend requests
function Wo_OpenRequestsMenu() {
  requests_container = $('.requests-container');
  requests_List = $('#requests-list');
  Wo_progressIconLoader(requests_container.find('.requests-loading'));
  $.get(Wo_Ajax_Requests_File(), {
    f: 'get_follow_requests'
  }, function (data) {
    if(data.status == 200) {
      if(data.html.length == 0) {
        requests_List.html('<span class="center-text"><svg style="width: 100%;color: #3f51b5;margin: 15px 0px 15px 0px;" xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg>' + data.message + '</span>');
      } else {
        requests_List.html(data.html);
        Wo_intervalUpdates();
      }
    }
    Wo_progressIconLoader(requests_container.find('.requests-loading'));
  });
}

// Notifications & follow requests updates
function Wo_intervalUpdates() {
  var check_posts = true;
  var hash_posts = true;
  if ($('.posts-hashtag-count').length == 0) {
     hash_posts = false;
  }
  var api = $('#api').val();
  if (api) {
     return false;
  }
  if ($('.posts-count').length == 0 || hash_posts == true) {
     check_posts = false;
  }
  if(typeof ($('#posts').attr('data-story-user')) == "string") {
    user_id = $('#posts').attr('data-story-user');
  } else {
    user_id = 0;
  }
  before_post_id = 0;
  if($('.post-container').length > 0) {
    var before_post_id = $('.post-container  > .post:not(.boosted)').attr('data-post-id');
  }
  var notification_container = $('.notification-container');
  var messages_notification_container = $('.messages-notification-container');
  var follow_requests_container = $('.requests-container');
  var ajax_request = {
    f: 'update_data',
    user_id: user_id,
    before_post_id: before_post_id,
    check_posts:check_posts,
    hash_posts:hash_posts
  };
  if (hash_posts == true) {
     ajax_request['hashtagName'] = $('#hashtagName').val();
  }
  $.get(Wo_Ajax_Requests_File(), ajax_request, function (data) {
    clearTimeout(intervalUpdates);
    intervalUpdates = setTimeout(Wo_intervalUpdates, 5000);
    if (hash_posts == true) {
        if (data.count_num > 0) {
          $('.posts-count').html(data.count);
        }
    } else {
        if (data.count_num > 0 && $('.filter-by-more').attr('data-filter-by') == 'all') {
          $('.posts-count').html(data.count);
        }
    }
    if(typeof (data.notifications) != "undefined" && data.notifications > 0) {
      notification_container.find('.new-update-alert').removeClass('hidden');
      notification_container.find('.sixteen-font-size').addClass('unread-update');
      notification_container.find('.new-update-alert').text(data.notifications).show();
      if(current_width > 800 && data.pop == 200) {
        Wo_NotifyMe(data.icon, decodeHtml(data.title), decodeHtml(data.notification_text), data.url);
      }
      if(data.notifications != current_notification_number) {
        if (data.notifications_sound == 0) {
           document.getElementById('notification-sound').play();
        }
        current_notification_number = data.notifications;
      }
    } else {
      notification_container.find('.new-update-alert').hide();
      notification_container.find('.sixteen-font-size').removeClass('unread-update');
      current_notification_number = 0;
    }
    if(typeof (data.messages) != "undefined" && data.messages > 0) {
      messages_notification_container.find('.new-update-alert').removeClass('hidden');
      messages_notification_container.find('.sixteen-font-size').addClass('unread-update');
      messages_notification_container.find('.new-update-alert').text(data.messages).show();
      if(data.messages != current_messages_number) {
        if (data.notifications_sound == 0) {
          document.getElementById('message-sound').play();
        }
        current_messages_number = data.messages;
      }
    } else {
      messages_notification_container.find('.new-update-alert').hide();
      messages_notification_container.find('.sixteen-font-size').removeClass('unread-update');
      current_messages_number = 0;
    }
    if(typeof (data.followRequests) != "undefined" && data.followRequests > 0) {
      follow_requests_container.find('.new-update-alert').removeClass('hidden');
      follow_requests_container.find('.sixteen-font-size').addClass('unread-update');
      follow_requests_container.find('.new-update-alert').text(data.followRequests).show();
      if(data.followRequests != current_follow_requests_number) {
        current_follow_requests_number = data.followRequests;
      }
    } else {
      follow_requests_container.find('.new-update-alert').hide();
      follow_requests_container.find('.sixteen-font-size').removeClass('unread-update');
      current_follow_requests_number = 0;
    }

    if(typeof (data.messages) != "undefined" && data.messages > 0 || typeof (data.notifications) != "undefined" && data.notifications > 0 || typeof (data.followRequests) != "undefined" && data.followRequests > 0) {
      title = Number(data.notifications) + Number(data.messages) + Number(data.followRequests);
      document.title = '(' + title + ') ' + document_title;
    } else {
      document.title = document_title;
    }
    if (data.calls == 200 && $('#re-calling-modal').length == 0 && $('#re-talking-modal').length == 0) {
         if ($('#calling-modal').length == 0) {
          $('body').append(data.calls_html);
          if (!$('#re-calling-modal').hasClass('calling')) {
            $('#re-calling-modal').modal({
             show: true
            });
            Wo_PlayVideoCall('play');
          }
          document.title = 'New video call..';
          setTimeout(function () {
            Wo_CloseModels();
            $('#re-calling-modal').addClass('calling');
            Wo_PlayVideoCall('stop');
            document.title = document_title;
            setTimeout(function () {
              $( '#re-calling-modal' ).remove();
              $( '.modal-backdrop' ).remove();
              $( 'body' ).removeClass( "modal-open" );
            }, 3000)
          }, 40000);
         } 
    } else if (data.audio_calls == 200 && $('#re-calling-modal').length == 0 && $('#re-talking-modal').length == 0) {
      if ($('#calling-modal').length == 0) {
          $('body').append(data.audio_calls_html);
          if (!$('#re-calling-modal').hasClass('calling')) {
            $('#re-calling-modal').modal({
             show: true
            });
            Wo_PlayVideoCall('play');
          }
          document.title = 'New audio call..';
          setTimeout(function () {
            if ($('#re-talking-modal').length == 0) {
               Wo_CloseModels();
               $('#re-calling-modal').addClass('calling');
               Wo_PlayVideoCall('stop');
               document.title = document_title;
               setTimeout(function () {
                 $( '#re-calling-modal' ).remove();
                // $( '.modal-backdrop' ).remove();
                 $( 'body' ).removeClass( "modal-open" );
               }, 3000)
            }
          }, 40000);
         } 
    } else if (data.is_audio_call == 0 && data.is_call == 0 && ($('#re-calling-modal').length > 0 || $('#re-talking-modal').length > 0)) {
        Wo_PlayVideoCall('stop');
        $( '#re-calling-modal' ).remove();
        //$( '.modal-backdrop' ).remove();
        $( 'body' ).removeClass( "modal-open" );
    }
  });
}
function Wo_GetNewHashTagPosts() {
  before_post_id = 0;
  if($('.post-container').length > 0) {
    var before_post_id = $('.post-container  > .post:not(.boosted)').attr('data-post-id');
  }
  var hashtagName = $('#hashtagName').val();
  if (!hashtagName) {
    return false;
  }
 var api = $('#api').val();
  var api_ = 0;
  if (api) {
    api_ = 1;
  }
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'get_new_hashtag_posts',
    before_post_id: before_post_id,
    hashtagName: hashtagName,
    api: api_
  }, function (data) {
    if(data.length > 0) {
      $('#posts_hashtag').find('.posts-container').remove();
      $('#posts_hashtag').prepend(data);
    }
     $('.posts-count').empty();
  });
}
// intervel new posts
function Wo_GetNewPosts() {
  var filter_by_more = $('#load-more-filter').find('.filter-by-more').attr('data-filter-by');
  if(filter_by_more != 'all') {
    return false;
  }
  if(typeof ($('#posts').attr('data-story-user')) == "string") {
    user_id = $('#posts').attr('data-story-user');
  } else {
    user_id = 0;
  }
  var api = $('#api').val();
  var api_ = 0;
  if (api) {
    api_ = 1;
  }
  before_post_id = 0;
  if($('.post-container').length > 0) {
    var before_post_id = $('.post-container  > .post:not(.boosted)').attr('data-post-id');
  }
  $('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'get_new_posts',
    before_post_id: before_post_id,
    user_id: user_id,
    api: api_
  }, function (data) {
    if(data.length > 0) {
      $('#posts').find('.posts-container').remove();
      $('#posts').prepend(data);
    }
     $('.posts-count').empty();
  });
}

// load more posts
function Wo_GetMorePosts() {
  var more_posts = $('#load-more-posts');
  var filter_by_more = $('#load-more-filter').find('.filter-by-more').attr('data-filter-by');
  var after_post_id = $('div.post:last').attr('data-post-id');
  var page_id = 0;
  var user_id = 0;
  var group_id = 0;
  var event_id = 0;
  var is_api = 0;
  var ad_id    = 0;
  var story_id = 0;
  var api = $('#api').val();
  if (api) {
    is_api = 1;
  }
  if(after_post_id != null) {
    more_posts.show();
  }
  if(typeof ($('#posts').attr('data-story-user')) == "string") {
    user_id = $('#posts').attr('data-story-user');
  } else if(typeof ($('#posts').attr('data-story-page')) == "string") {
    page_id = $('#posts').attr('data-story-page');
  } else if(typeof ($('#posts').attr('data-story-group')) == "string") {
    group_id = $('#posts').attr('data-story-group');
  } else if(typeof ($('#posts').attr('data-story-event')) == "string") {
    event_id = $('#posts').attr('data-story-event');
  }
  $('#posts').append('<div class="hidden loading-status loading-single"></div>');
  $('#load-more-posts').hide();
  $('.loading-status').hide().html('<div class="wo_loading_post"><div class="wo_loading_post_child2"></div></div>').removeClass('hidden').show();
  Wo_progressIconLoader($('#load-more-posts'));
  posts_count = 0;
  if ($('.post').length > 0) {
    posts_count = $('.post').length;
  }
  
  if ($(".user-ad-container").length > 0) {
    ad_id = $(".user-ad-container").last().attr('id');
  }

  if ($(".user-story-container").length > 0) {
    story_id = $(".user-story-container").last().attr('id');
  }
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'load_more_posts',
    filter_by_more: filter_by_more,
    after_post_id: after_post_id,
    user_id: user_id,
    page_id: page_id,
    group_id: group_id,
    event_id: event_id,
    posts_count: posts_count,
    is_api: is_api,
    ad_id: ad_id,
    story_id:story_id
  }, function (data) {
    if (data.length == 0) {
      $.get(Wo_Ajax_Requests_File(), {f: 'get_no_posts_name'}, function (data3) {
          $('#load-more-posts').html('<div class="white-loading list-group"><div class="cs-loader"><div class="no-more-posts-to-show">' + data3.name + '</div></div>');
      });
     } else {
      $('#posts').append(data);
    }
    $('#load-more-posts').show();
    $('.loading-status').remove();
    Wo_progressIconLoader($('#load-more-posts'));
    scrolled = 0;
  });
}
function Wo_LoadStory(type, user_id) {
  var filter_by_more = $('#load-more-filter').find('.filter-by-more');
  filter_by_more.attr("data-filter-by", 'story');
  var filter_by_progress_icon = $('.filter-container').find('.type-story');
  Wo_progressIconLoader(filter_by_progress_icon);
  var story = null;
  var user  = null;
  if (type == 'all') {
    story   = 'a';
    user    = 0;
  }
  else if(type == 'prof' && user_id){
    story   = 'p';
    user    = user_id;
  }
  $.ajax({
    url: Wo_Ajax_Requests_File(),
    type: 'GET',
    dataType: 'json',
    data: {f: 'status',s:story,id:user},
  })
  .done(function(data) {
    if (data.status == 200) {
      $('#posts').html(data.html);
    }
    else if(data.status == 404){
      $('#posts').html(data.html);
    }
    Wo_progressIconLoader(filter_by_progress_icon);
  })
  .fail(function() {
    console.log("error");
  })
  
}
// post filteration
function Wo_FilterPostBy(filter_by) {
  var more_posts = $('#load-more-posts');
  var filter_by_more = $('#load-more-filter').find('.filter-by-more');
  filter_by_more.attr("data-filter-by", filter_by);
  var filter_by_progress_icon = $('.filter-container').find('.type-' + filter_by);
  Wo_progressIconLoader(filter_by_progress_icon);
  var type = '';
  var id = 0;
  if(typeof ($('#posts').attr('data-story-user')) == "string") {
    id = $('#posts').attr('data-story-user');
    type = 'profile';
  } else if(typeof ($('#posts').attr('data-story-page')) == "string") {
    id = $('#posts').attr('data-story-page');
    type = 'page';
  } else if (typeof ($('#posts').attr('data-story-group')) == "string") {
    id = $('#posts').attr('data-story-group');
    type = 'group';
  } else if (typeof ($('#posts').attr('data-story-event')) == "string") {
    id = $('#posts').attr('data-story-event');
    type = 'event';
  }
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'filter_posts',
    filter_by: filter_by,
    id: id,
    type: type
  }, function (data) {
    if(data) {
      $('#posts').html(data);
      //more_posts.html('<span class="btn btn-default">' + data.text + '<span>');
      $('.filterby li.filter-by-li').each(function(){
         $(this).find('i').addClass('hidden');
      });
      Wo_progressIconLoader(filter_by_progress_icon);
    }
  });
}
// register post share
function Wo_RegisterShare(post_id) {
  var post = $('#post-' + post_id);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'register_share',
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      post.find("#share-button").addClass('active');
      post.find("[id^=shares]").text(data.shares);
    } else {
      post.find("#share-button").removeClass('active');
      post.find("[id^=shares]").text(data.shares);
    }
    if (data.can_send == 1) {
        Wo_SendMessages();
    }
  });
}

// open post share buttons
function Wo_OpenShareBtns(post_id) {
  post_wrapper = $('#post-' + post_id);
  post_wrapper.find('.post-share').slideToggle(200);
}
// register post comment
function Wo_RegisterCommentClick(text, post_id, user_id, page_id, type) {
    post_wrapper = $('[id=post-' + post_id + ']');
    comment_textarea = post_wrapper.find('.post-comments');
    comment_btn = comment_textarea.find('.emo-comment');
    textarea_wrapper = comment_textarea.find('.textarea');
    comment_list = post_wrapper.find('.comments-list');
    if(text == '') {
      return false;
    }
    textarea_wrapper.val('');
    Wo_progressIconLoader(comment_btn);
    $.post(Wo_Ajax_Requests_File() + '?f=posts&s=register_comment', {
      post_id: post_id,
      text: text,
      user_id: user_id,
      page_id: page_id
    }, function (data) {
      if(data.status == 200) {
        post_wrapper.find('.comment-container:first-child').before(data.html);
        post_wrapper.find('[id=comments]').html(data.comments_num);
      }
      Wo_progressIconLoader(comment_btn);
      if (data.can_send == 1) {
        Wo_SendMessages();
      }
    });
}
// register post comment
function Wo_LightBoxComment(text, post_id, user_id, event, page_id) {
  if(event.keyCode == 13 && event.shiftKey == 0) {
    post_wrapper = $('#lightbox-post-' + post_id);
    comment_textarea = post_wrapper.find('.post-comments');
    comment_btn = comment_textarea.find('.comment-btn');
    textarea_wrapper = comment_textarea.find('.textarea');
    comment_list = post_wrapper.find('.comments-list');
    if(textarea_wrapper.val() == '') {
      return false;
    }
    textarea_wrapper.val('');
    Wo_progressIconLoader(comment_btn);
    $.post(Wo_Ajax_Requests_File() + '?f=posts&s=register_comment', {
      post_id: post_id,
      text: text,
      user_id: user_id,
      page_id: page_id
    }, function (data) {
      if(data.status == 200) {
        post_wrapper.find('.comment-container:first-child').before(data.html);
        post_wrapper.find('#comments').html(data.comments_num);
      }
      Wo_progressIconLoader(comment_btn);
      if (data.can_send == 1) {
        Wo_SendMessages();
      }
    });
  }
}

// load all post comments
function Wo_loadAllComments(post_id) {
  main_wrapper = $('#post-' + post_id);
  view_more_wrapper = main_wrapper.find('.view-more-wrapper');
  Wo_progressIconLoader(view_more_wrapper);
  $('#post-' + post_id).find('.view-more-wrapper .ball-pulse').fadeIn(100);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'load_more_comments',
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      main_wrapper.find('.comments-list').html(data.html);
      view_more_wrapper.remove();
    }
  });
}
function Wo_loadAllCommentslightbox(post_id) {
  main_wrapper_light = $('#post-' + post_id);
  view_more_wrapper_light = main_wrapper_light.find('.view-more-wrapper');
  $('.lightpost-' + post_id).find('.view-more-wrapper .ball-pulse').fadeIn(100);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'load_more_comments',
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      $('.comments-list-lightbox').html(data.html);
      $('.view-more-wrapper').remove();
    }
  });
}

// show post comments
function Wo_ShowComments(post_id) {
  $('#post-comments-' + post_id).toggleClass('hidden');
}

// open post edit modal
function Wo_OpenPostEditBox(post_id) {
  var edit_box = $('#post-' + post_id).find('#edit-post');
  edit_box.modal({
    show: true
  });
}

// save edited post
function Wo_EditPost(post_id) {
  var post = $('#post-' + post_id);
  var edit_box = $('#post-' + post_id).find('#edit-post');
  var edit_textarea = post.find('.edit-textarea-' + post_id + ' textarea');
  var text = edit_textarea.val();
  var post_text = post.find('.post-description p');
  Wo_progressIconLoader(post.find('#edit-post-button'));
  $('#post-' + post_id).find('#edit-post .ball-pulse').fadeIn(100);
  $.post(Wo_Ajax_Requests_File() + '?f=posts&s=edit_post', {
    post_id: post_id,
    text: text
  }, function (data) {
    if(data.status == 200) {
      post_text.html(data.html);
      edit_box.modal('hide');
    }
    $('#post-' + post_id).find('#edit-post .ball-pulse').fadeOut(100);
    if (data.can_send == 1) {
        Wo_SendMessages();
    }
  });
}

// open delete post modal
function Wo_OpenPostDeleteBox(post_id) {
  var delete_box = $('#post-' + post_id).find('#delete-post');
  delete_box.modal({
    show: true
  });
}

// delete post
function Wo_DeletePost(post_id) {
  var delete_box = $('#post-' + post_id).find('#delete-post');
  var delete_button = delete_box.find('#delete-all-post');
  $('#post-' + post_id).find('#delete-post .ball-pulse').fadeIn(100);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'delete_post',
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      delete_box.modal('hide');
      setTimeout(function () {
        $('#post-' + post_id).slideUp(200, function () {
          $(this).remove();
        });
      }, 300);
    }
    $('#post-' + post_id).find('#delete-post .ball-pulse').fadeOut(100);
  });
}

// open comment textarea
function Wo_OpenCommentEditBox(comment_id) {
  comment = $('[id=comment_' + comment_id + ']');
  comment_text = comment.find('.comment-edit');
  comment_text.slideToggle(100);
}

// save edited comment
function Wo_EditComment(text, comment_id, event) {
  comment = $('[id=comment_' + comment_id + ']');
  comment_text = comment.find('.comment-text');
  if(event.keyCode == 13 && event.shiftKey == 0) {
    Wo_progressIconLoader(comment.find('#editComment'));
    $.post(Wo_Ajax_Requests_File() + '?f=posts&s=edit_comment', {
      comment_id: comment_id,
      text: text
    }, function (data) {
      if(data.status == 200) {
        comment_text.html(data.html);
        Wo_OpenCommentEditBox(comment_id);
      }
      Wo_progressIconLoader(comment.find('#editComment'));
    });
  }
}

// delete comment
function Wo_DeleteComment(comment_id) {
  var delete_box = $('[id=comment_' + comment_id + ']').find('#delete-comment');
  var delete_button = delete_box.find('#delete-all-post');
  delete_box.modal({
    show: true
  });
  var comment = $('[id=comment_' + comment_id + ']');
  delete_button.on('click', function () {
    $('[id=comment_' + comment_id + ']').find('#delete-comment .ball-pulse').fadeIn(100);
    $.get(Wo_Ajax_Requests_File(), {
      f: 'posts',
      s: 'delete_comment',
      comment_id: comment_id
    }, function (data) {
      if(data.status == 200) {
        delete_box.modal('hide');
        $('.modal').modal('hide');
        comment.fadeOut(300, function () {
          comment.remove();
        });
      }
    });
  });
}

function Wo_DeleteReplyComment(reply_id) {
  var delete_box = $('[id=comment_reply_' + reply_id + ']').find('#delete-comment-reply');
  var delete_button = delete_box.find('#delete-all-reply');
  delete_box.modal({
    show: true
  });
  var comment = $('[id=comment_reply_' + reply_id + ']');
  delete_button.on('click', function () {
    $('[id=comment_reply_' + reply_id + ']').find('#delete-comment-reply .ball-pulse').fadeIn(100);
    $.get(Wo_Ajax_Requests_File(), {
      f: 'posts',
      s: 'delete_comment_reply',
      reply_id: reply_id
    }, function (data) {
      if(data.status == 200) {
        delete_box.modal('hide');
        comment.fadeOut(300, function () {
          $(this).remove();
        });
      }
    });
  });
}

// register comment like
function Wo_RegisterCommentLike(comment_id) {
  var comment = $('[id=comment_' + comment_id + ']');
  comment_text = comment.find('div.comment-text').text();
  Wo_progressIconLoader(comment.find('#LikeComment'));
  $.post(Wo_Ajax_Requests_File() + '?f=posts&s=register_comment_like', {
    comment_id: comment_id,
    comment_text: comment_text
  }, function (data) {
    if(data.status == 200) {
      if (data.dislike == 1) {
          comment.find("#comment-wonders").text(data.wonders_c);
          comment.find("#WonderComment").html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-down"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path></svg>');
      }
      comment.find("#LikeComment").html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up active-like"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>').fadeIn(150);
      comment.find("#comment-likes").text(data.likes);
    } else {
      comment.find("#LikeComment").html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>').fadeIn(150);
      comment.find("#comment-likes").text(data.likes);
    }
  });
}

// register comment wonder
function Wo_RegisterCommentWonder(comment_id) {
  var comment = $('[id=comment_' + comment_id + ']');
  comment_text = comment.find('div.comment-text').text();
  Wo_progressIconLoader(comment.find('#WonderComment'));
  $.post(Wo_Ajax_Requests_File() + '?f=posts&s=register_comment_wonder', {
    comment_id: comment_id,
    comment_text: comment_text
  }, function (data) {
    if(data.status == 200) {
      if (data.dislike == 1) {
          comment.find("#comment-likes").text(data.likes_c);
          comment.find("#LikeComment").html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>');
      }
      comment.find("#WonderComment").html('<span class="active-wonder">' + data.icon + '</span>').fadeIn(150);
      comment.find("#comment-wonders").text(data.wonders);
    } else if (data.status == 300)  {
      comment.find("#WonderComment").html('' + data.icon + '').fadeIn(150);
      comment.find("#comment-wonders").text(data.wonders);
    }
  });
}

// register comment wonder
function Wo_RegisterCommentReplyWonder(reply_id) {
  var comment = $('[id=comment_reply_' + reply_id + ']');
  comment_text = comment.find('div.reply-text').text();
  Wo_progressIconLoader(comment.find('#WonderReplyComment'));
  $.post(Wo_Ajax_Requests_File() + '?f=posts&s=register_comment_reply_wonder', {
    reply_id: reply_id,
    comment_text: comment_text
  }, function (data) {
    if(data.status == 200) {
      if (data.dislike == 1) {
          comment.find("#comment-reply-likes").text(data.likes_r);
          comment.find("#LikeReplyComment").html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>');
      }
      comment.find("#WonderReplyComment").html('<span class="active-wonder">' + data.icon + '</span>').fadeIn(150);
      comment.find("#comment-reply-wonders").text(data.wonders);
    } else if (data.status == 300){
      comment.find("#WonderReplyComment").html('' + data.icon + '').fadeIn(150);
      comment.find("#comment-reply-wonders").text(data.wonders);
    }
  });
}

function Wo_RegisterCommentReplyLike(reply_id) {
  var comment = $('[id=comment_reply_' + reply_id + ']');
  comment_text = comment.find('div.reply-text').text();
  Wo_progressIconLoader(comment.find('#LikeReplyComment'));
  $.post(Wo_Ajax_Requests_File() + '?f=posts&s=register_comment_reply_like', {
    reply_id: reply_id,
    comment_text: comment_text
  }, function (data) {
    if(data.status == 200) {
      if (data.dislike == 1) {
          comment.find("#comment-reply-wonders").text(data.wonders_r);
          comment.find("#WonderReplyComment").html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-down"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path></svg>');
      }
      comment.find("#LikeReplyComment").html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up active-like"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>').fadeIn(150);
      comment.find("#comment-reply-likes").text(data.likes);
    } else if (data.status == 300){
      comment.find("#LikeReplyComment").html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>').fadeIn(150);
      comment.find("#comment-reply-likes").text(data.likes);
    }
  });
}
// save post
function Wo_SavePost(post_id) {
  var post = $('#post-' + post_id);
  post.find('.save-post').html('<div class="post_drop_menu_loading"><div class="ball-pulse"><div></div><div></div><div></div></div></div>');
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'save_post',
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      post.find('.save-post').html(data.text);
    } else if(data.status == 300) {
      post.find('.save-post').html(data.text);
    }
  });
}

// report post
function Wo_ReportPost(post_id) {
  var post = $('#post-' + post_id);
  post.find('.report-post').html('<div class="post_drop_menu_loading"><div class="ball-pulse"><div></div><div></div><div></div></div></div>');
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'report_post',
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      post.find('.report-post').html(data.text);
    } else if(data.status == 300) {
      post.find('.report-post').html(data.text);
    }
  });
}

function Wo_PinPost(post_id, id, type) {
  var post = $('#post-' + post_id);
  post.find('.pin-post').html('<div class="post_drop_menu_loading"><div class="ball-pulse"><div></div><div></div><div></div></div></div>');
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'pin_post',
    post_id: post_id,
    id: id,
    type: type
  }, function (data) {
    if(data.status == 200) {
      post.find('.pin-post').html(data.text);
    } else if(data.status == 300) {
      post.find('.pin-post').html(data.text);
    }
  });
}

function Wo_BoostPost(post_id) {
  var post = $('#post-' + post_id);
  post.find('.boost-post').html('<div class="post_drop_menu_loading"><div class="ball-pulse"><div></div><div></div><div></div></div></div>');
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'boost_post',
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      post.find('.boost-post').html(data.text);
    } else if(data.status == 300) {
      post.find('.boost-post').html(data.text);
    }
  });
}
// open post liked users
function Wo_OpenPostLikedUsers(post_id) {
  var post = $('#post-' + post_id);
  post_likes_container = post.find('.post-likes');
  post.find('.post-wonders').slideUp(200, function () {
    post.find('.post-wonders').empty();
  });
  if(!post_likes_container.is(':empty')) {
    post_likes_container.slideToggle(200, function () {
      post.find('.post-likes').empty();
    });
    return false;
  }
  Wo_progressIconLoader(post.find('.post-like-status'));
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'get_post_likes',
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      if(data.html.length == 0) {
        post_likes_container.html('<span class="view-more-wrapper">' + data.message + '</span>');
      } else {
        post_likes_container.html(data.html);
      }
      post_likes_container.slideToggle(200);
    }
    Wo_progressIconLoader(post.find('.post-like-status'));
  });
}

// open post wodered users
function Wo_OpenPostWonderedUsers(post_id) {
  var post = $('#post-' + post_id);
  post_wonders_container = post.find('.post-wonders');
  post.find('.post-likes').slideUp(200, function () {
    post.find('.post-likes').empty();
  });
  if(!post_wonders_container.is(':empty')) {
    post_wonders_container.slideToggle(200, function () {
      post.find('.post-wonders').empty();
    });
    return false;
  }
  Wo_progressIconLoader(post.find('.post-wonders-status'));
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'get_post_wonders',
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      if(data.html.length == 0) {
        post_wonders_container.html('<span class="view-more-wrapper">' + data.message + '</span>');
      } else {
        post_wonders_container.html(data.html);
      }
      post_wonders_container.slideToggle(200);
    }
    Wo_progressIconLoader(post.find('.post-wonders-status'));
  });
}

// add emo to input
function Wo_AddEmo(code, input) {
  inputTag = $(input);
  inputVal = inputTag.val();
  if(typeof (inputTag.attr('placeholder')) != "undefined") {
    inputPlaceholder = inputTag.attr('placeholder');
    if(inputPlaceholder == inputVal) {
      inputTag.val('');
      inputVal = inputTag.val();
    }
  }
  if(inputVal.length == 0) {
    inputTag.val(code + ' ');
  } else {
    inputTag.val(inputVal + ' ' + code);
  }
  inputTag.keyup();
}

// accept follow request
function Wo_AcceptFollowRequest(user_id) {
  var main_container = $('.user-follow-request-' + user_id);
  var follow_main_container = main_container.find('#accept-' + user_id);
  Wo_progressIconLoader(follow_main_container);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'accept_follow_request',
    following_id: user_id
  }, function (data) {
    if(data.status == 200) {
      main_container.find('.accept-btns').html(data.html);
    }
  });
}
function Wo_StartRepositioner() {
    $('.user-cover-reposition-w').hide();
    $('.user-reposition-container').show();
    $('.cover-resize-buttons').show();
    $('.default-buttons').hide();
    $('.when-notedit').hide();
    $('.when-edit').show();
    $('.user-reposition-dragable-container').show();
    $('.profile-cover-changer').show();
  $('.wo_user_profile .card.hovercard .pic-info-cont, .problackback').fadeOut();
    $('.screen-width').val($('.user-reposition-container').width());
    $('.user-reposition-container img').css('cursor', '-webkit-grab').draggable({
        scroll: false,
        axis: "y",
        cursor: "-webkit-grab",
        drag: function (event, ui) {
            y1 = $('.user-cover-reposition-container').height();
            y2 = $('.user-reposition-container').find('img').height();
            if (ui.position.top >= 0) {
                ui.position.top = 0;
            }else
                if (ui.position.top <= (y1-y2)) {
                    ui.position.top = y1-y2;
                }
            },
            stop: function(event, ui) {
                $('input.cover-position').val(ui.position.top);
            }
        });
}

function Wo_SubmitRepositioner() {
    if ($('input.cover-position').length == 1) {
        posY = $('input.cover-position').val();
        $('form.cover-position-form').submit();
    }
}

function Wo_StopRepositioner() {
    $('.when-notedit').show();
    $('.when-edit').hide();
    $('.user-cover-reposition-w').show();
    $('.user-reposition-container').hide();
    $('.cover-resize-buttons').hide();
    $('.default-buttons').show();
    $('input.cover-position').val(0);
  $('.wo_user_profile .card.hovercard .pic-info-cont, .problackback').fadeIn();
    $('.user-reposition-container img').draggable('destroy').css('cursor','default');
}
// delete follow request
function Wo_DeleteFollowRequest(user_id) {
  var main_container = $('.user-follow-request-' + user_id);
  var follow_main_container = main_container.find('#delete-' + user_id);
  Wo_progressIconLoader(follow_main_container);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'delete_follow_request',
    following_id: user_id
  }, function (data) {
    if(data.status == 200) {
      main_container.find('.accept-btns').html(data.html);
    }
  });
}

// update post privacy
function Wo_UpdatePostPrivacy(post_id, privacy_type, event) {
  var post = $('#post-' + post_id);
  event.preventDefault();
  var post_privacy_container = post.find('.post-privacy');
  Wo_progressIconLoader(post_privacy_container);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'update_post_privacy',
    post_id: post_id,
    privacy_type: privacy_type
  }, function (data) {
    if(data.status == 200) {
      if(data.privacy_type == 0) {
        post_privacy_container.html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>');
      } else if(data.privacy_type == 1) {
        post_privacy_container.html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>');
      } else if(data.privacy_type == 2) {
        post_privacy_container.html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>');
      } else if(data.privacy_type == 3) {
        post_privacy_container.html('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>');
      } else {
        return false;
      }
    }
  });
}

// open chat tab
function Wo_OpenChatTab(recipient_id, group_id) {

  if ($(".chat_"+recipient_id).length > 0) {
    return false;
  }
  if(group_id != 0){
    $("#group_tab_" + group_id).find('.group-lastseen').empty();
  }
  if (group_id == null) {
    group_id = 0;
  }
  $.get(Wo_Ajax_Requests_File(), {
    f: 'chat',
    s: 'is_chat_on',
    recipient_id: recipient_id
  }, function (data) {
    if(current_width < 720) {
       document.location = data.url;

       return false;
    }
    if(data.chat != 1 && group_id === 0) {
     // document.location = data.url;
      return false;
    }
  });
  if(current_width < 720) {
    $.get(Wo_Ajax_Requests_File(), {
      f: 'chat',
      s: 'close',
      recipient_id: recipient_id
    }, function (data) {
      console.log(data);
      //document.location = data.url;
    });
    return false;
  }
  placement = 1;
  if ($('.chat-wrapper').length == 1) {
    placement = 2;
  } else if ($('.chat-wrapper').length == 2) {
    placement = 3;
  }
  var loading_icon = '<div class="ball-pulse" style="display: block;"><div></div><div></div><div></div></div>';
  $('#online_' + recipient_id).find('.new-message-alert').hide();
  if (group_id) {
    var loading_div = $('.chat-container').find('#group_tab_' + group_id).find('.chat-loading-icon');
  }else{
    var loading_div = $('.chat-container').find('#online_' + recipient_id).find('.chat-loading-icon');
  }
  
  loading_div.html(loading_icon);
  chat_container = $('.chat-container');
  $(document.body).attr('data-chat-recipient-'+recipient_id, recipient_id);
  $('.chat-wrapper').show();
  /* var data_html = '<div class="chat-wrapper" id="chat_"><div class="online-toggle pointer" onclick="javascript:$(\'.chat-tab-container\').slideToggle(100);"><a style="color:#fff;" href=""><span class="chat-tab-status">......</span></a></div><div class="chat-tab-container"><div class="chat-messages-wrapper"><div class="chat-messages"></div><div class="clear"></div></div><div class="chat-textarea btn-group"><div class="emo-container"></div><form action="#" method="post" class="chat-sending-form"><textarea name="textSendMessage" disabled id="sendMessage" class="form-control" cols="10" rows="5" placeholder=""  onkeydown="Wo_SubmitChatForm(event);" onfocus="Wo_SubmitChatForm(event);" dir="auto"></textarea><input type="hidden" id="user-id" name="user_id" value="" /></form></div></div></div>';
  $('.chat-tab').append('<span class="chat_main chat_main_"></span>');
  $('.chat_main_').append(data_html); */
  $.get(Wo_Ajax_Requests_File(), {
    f: 'chat',
    s: 'load_chat_tab',
    recipient_id: recipient_id,
    placement:placement,
    group_id:group_id
  }, function (data) {
    if(data.status == 200) {
      if ($('.chat-wrapper').length == 3) {
         if ($('.chat_main_' + recipient_id).length == 0) {
            $('.chat_main:first-child').remove();
            $('.chat-tab').append('<span class="chat_main chat_main_' + recipient_id +'"></span>');
         } else {
            $('.chat_main_' + recipient_id).remove();
         }
         $('.chat_main_' + recipient_id).html(data.html);
      } else {
        if ($('.chat_main_' + recipient_id).length > 0) {
          $('.chat_main_' + recipient_id).html(data.html);
        } else {
          $('.chat-tab').append('<span class="chat_main chat_main_' + recipient_id +'"></span>');
          $('.chat_main_' + recipient_id).append(data.html);
        }
      }
      $('.chat-wrapper').show();
      loading_div.empty();
      $('.chat-textarea textarea').keyup();
      if (group_id == 0) {
        $.get(Wo_Ajax_Requests_File(), {
          f: 'chat',
          s: 'load_chat_messages',
          recipient_id: recipient_id
        }, function (data) {
          if (data.messages.length > 0) {
             $('.chat-tab').find('.chat_' + recipient_id).find('.chat-messages').html(data.messages);
          } else {
            $('.chat_' + recipient_id).find('.chat-user-desc').fadeIn(150);
          }
          setTimeout(function () {
            $('.chat-messages-wrapper').scrollTop($('.chat-messages-wrapper')[0].scrollHeight);
          }, 1000);
        });
      }
    }
  });
}

function Wo_OpenChatUsersTab() {
  
  $('.chat-container').toggleClass('full');
  $('.online-content-toggler').slideToggle(100);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'chat',
    s: 'open_tab'
  });
}

function Wo_SearchForPosts(query) {
  var type = '';
  if ($('.page-margin').attr('data-page') == "timeline") {
    type = 'user';
  } else if ($('.page-margin').attr('data-page') == "page"){
    type = 'page';
  } else if ($('.page-margin').attr('data-page') == "group") {
    type = 'group'
  } else {
    return false;
  }
  Wo_progressIconLoader($('.search-for-posts-container'));
  var id = $('.page-margin').attr('data-id');
  if (id == null || id == "undefined") {
    return false;
  }
  $.get(Wo_Ajax_Requests_File(), {f:'posts', s:'search_for_posts', id: id, search_query: query, type: type}, function (data) {
     if (data.status == 200) {
        $('#posts').html(data.html);
     }
     Wo_progressIconLoader($('.search-for-posts-container'));
  });
}

function Wo_Fetch(id, post_id) {
   var clickedOnBody = true;
   var user_from_post_id = '#post-' + post_id;
   var user_from_image = '#post-' + post_id + ' .post-heading';
   var div = '.user-fetch-post-' + post_id + '-user-' + id;
   var bla = user_from_post_id + ', ' + div;
   clearTimeout(timeout);
   $(div).fadeIn(200);  
   var timeout;
   function hidepanel() {
     $(div).fadeOut(0); 
   }
    $(div).mouseleave(doTimeout);
    $(user_from_image).mouseleave(doTimeout);
     function doTimeout(){
        clearTimeout(timeout);
        timeout = setTimeout(hidepanel, 0);
     }
}

function Wo_RequestVerification(id, type) {
  $.get(Wo_Ajax_Requests_File(), {f:'request_verification', id:id, type:type}, function(data) {
    if (data.status == 200) {
      $('#verification-request').html(data.html);
    }
  });
}

function Wo_DeleteUserVerification(id, type) {
  if (confirm("Are you sure ?") == false) {
      return false;
  }
  var verify_icon = $('form').find('div.verification');
  Wo_progressIconLoader(verify_icon);
  $.get(Wo_Ajax_Requests_File(), {f:'delete_verification', id:id, type:type}, function(data) {
    if (data.status == 200) {
      $('#verification-request').html(data.html);
    }
  });
}

function Wo_RemoveVerification(id, type) {
  $.get(Wo_Ajax_Requests_File(), {f:'remove_verification', id:id, type:type}, function(data) {
    if (data.status == 200) {
      $('#verification-request').html(data.html);
    }
  });
}

$('body').on('mouseenter', '.user-popover', function() {
    var popover = $(this);
    var type = popover.attr('data-type');
    var id = popover.attr('data-id');
    var placement = popover.attr('data-placement') || 'none';
    var placement_code = 'user-details not-profile';
    if (placement == 'profile') {
      placement_code = 'user-details';
    }
    var over_time = setTimeout(function() {
       var offset = popover.offset();
       var posY = (offset.top - $(window).scrollTop()) + popover.height();
       var posX = offset.left - $(window).scrollLeft();
       var available = $(window).width() - posX;
       if ($(window).width() > 800) {
       if (available < 400) {
         var right = available - popover.width();
         if (posY > 0) {
          $('body').append('<div class="' + placement_code + ' right" style="position: fixed; top: ' + posY + 'px; right:' + right + 'px"><div class="loading-user"><div class="fa fa-spinner fa-spin"></div></div></div>');
         }
       } else {
        if (posY > 0) {
         $('body').append('<div class="' + placement_code + '" style="position: fixed; top: ' + posY + 'px; left:' + posX + 'px"><div class="loading-user"><div class="fa fa-spinner fa-spin"></div></div></div>');
        }
       }
       }
       $.get(Wo_Ajax_Requests_File(), {f:'popover', id:id, type:type}, function(data) {
         if (data.status == 200) {
             $('.user-details').html(data.html);
         }
       });
    }, 1000);
    popover.data('timeout', over_time);
});

$('body').on('mouseleave', '.user-popover', function(e) {
    var to = e.toElement || e.relatedTarget;
      if (!$(to).is(".user-details")) {
        clearTimeout($(this).data('timeout'));
        $('.user-details').remove();
      }
});
$('body').on('mouseleave', '.user-details', function() {
    $('.user-details').remove();
});
function Wo_OpenAlbumLightBox(image_id, type) {
  $('body').append('<div class="lightbox-container"><div class="lightbox-backgrond" onclick="Wo_CloseLightbox();"></div><div class="lb-preloader" style="display:block"><svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#676d76" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div></div>');
  $.get(Wo_Ajax_Requests_File(), {f:'open_album_lightbox', image_id:image_id, type:type}, function(data) {
    if (data.status == 200) {
    document.body.style.overflow = 'hidden';
      $('.lightbox-container').html(data.html);
    }
    if (data.html.length == 0) {
       document.body.style.overflow = 'auto';
    }
  });
}
function Wo_CloseLightbox() {
  $('.lightbox-container').remove();
  document.body.style.overflow = 'auto';
}
function Wo_OpenLightBox(post_id) {
  $('#contnet').append('<div class="lightbox-container"><div class="lightbox-backgrond" onclick="Wo_CloseLightbox();"></div><div class="lb-preloader" style="display:block"><svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#676d76" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div></div>');
  $.get(Wo_Ajax_Requests_File(), {f:'open_lightbox', post_id:post_id}, function(data) {
    if (data.status == 200) {
    document.body.style.overflow = 'hidden';
      $('.lightbox-container').html(data.html);
    }
    if (data.html.length == 0) {
       document.body.style.overflow = 'auto';
    }
  });
}
function Wo_OpenMultiLightBox(url) {
  $('body').append('<div class="lightbox-container"><div class="lightbox-backgrond" onclick="Wo_CloseLightbox();"></div><div class="lb-preloader" style="display:block"><svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#676d76" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div></div>');
  $.post(Wo_Ajax_Requests_File() + '?f=open_multilightbox', {url:url}, function(data) {
    if (data.status == 200) {
    document.body.style.overflow = 'hidden';
      $('.lightbox-container').html(data.html);
    }
    if (data.html.length == 0) {
       document.body.style.overflow = 'auto';
    }
  });
}
function Wo_NextAlbumPicture(post_id, id) {
  Wo_CloseLightbox();
  $('body').append('<div class="lightbox-container"><div class="lightbox-backgrond" onclick="Wo_CloseLightbox();"></div><div class="lb-preloader" style="display:block"><svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#676d76" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div></div>');
  $.get(Wo_Ajax_Requests_File(), {f:'get_next_album_image', post_id:post_id, after_image_id:id}, function(data) {
    if (data.status == 200) {
  document.body.style.overflow = 'hidden';
      $('.lightbox-container').html(data.html);
      $( ".changer").fadeIn(200);
    }
    if (data.html.length == 0) {
       document.body.style.overflow = 'auto';
    }
  });
}
function Wo_PreviousAlbumPicture(post_id, id) {
  Wo_CloseLightbox();
  $('body').append('<div class="lightbox-container"><div class="lightbox-backgrond" onclick="Wo_CloseLightbox();"></div><div class="lb-preloader" style="display:block"><svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#676d76" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div></div>');
  $.get(Wo_Ajax_Requests_File(), {f:'get_previous_album_image', post_id:post_id, before_image_id:id}, function(data) {
    if (data.status == 200) {
    document.body.style.overflow = 'hidden';
      $('.lightbox-container').html(data.html);
      $( ".changer").fadeIn(200);
    }
    if (data.html.length == 0) {
       document.body.style.overflow = 'auto';
    }
  });
}

function Wo_NextPicture(post_id) {
  var id = 0;
  var type = 'none';
  if(typeof ($('[data-page=timeline]').attr('data-id')) == "string") {
    id = $('[data-page=timeline]').attr('data-id');
    type = 'profile';
  } else if(typeof ($('[data-page=page]').attr('data-id')) == "string") {
    id = $('[data-page=page]').attr('data-id');
    type = 'page';
  } else if (typeof ($('[data-page=group]').attr('data-id')) == "string") {
    id = $('[data-page=group]').attr('data-id');
    type = 'group';
  }
   Wo_CloseLightbox();
  $('body').append('<div class="lightbox-container"><div class="lightbox-backgrond" onclick="Wo_CloseLightbox();"></div><div class="lb-preloader" style="display:block"><svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#676d76" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div></div>');
  
  $.get(Wo_Ajax_Requests_File(), {f:'get_next_image', post_id:post_id, type:type, id:id}, function(data) {
    if (data.status == 200) {
    document.body.style.overflow = 'hidden';
      $('.lightbox-container').html(data.html);
      $( ".changer" ).fadeIn(200);
    }
    if (data.html.length == 0) {
       document.body.style.overflow = 'auto';
    }
  });
}

function Wo_PreviousPicture(post_id) {
  var id = 0;
  var type = 'none';
  if(typeof ($('[data-page=timeline]').attr('data-id')) == "string") {
    id = $('[data-page=timeline]').attr('data-id');
    type = 'profile';
  } else if(typeof ($('[data-page=page]').attr('data-id')) == "string") {
    id = $('[data-page=page]').attr('data-id');
    type = 'page';
  } else if (typeof ($('[data-page=group]').attr('data-id')) == "string") {
    id = $('[data-page=group]').attr('data-id');
    type = 'group';
  }
  Wo_CloseLightbox();
  $('body').append('<div class="lightbox-container"><div class="lightbox-backgrond" onclick="Wo_CloseLightbox();"></div><div class="lb-preloader" style="display:block"><svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#676d76" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div></div>');
  $.get(Wo_Ajax_Requests_File(), {f:'get_previous_image', post_id:post_id, type:type, id:id}, function(data) {
    if (data.status == 200) {
    document.body.style.overflow = 'hidden';
      $('.lightbox-container').html(data.html);
      $( ".changer" ).fadeIn(200);
    }
    if (data.html.length == 0) {
       document.body.style.overflow = 'auto';
    }
  });
}

function Wo_AcceptJoinGroup(user_id, group_id) {
  $.get(Wo_Ajax_Requests_File(), {f:'groups', s:'accept_request', user_id:user_id, group_id:group_id}, function(data) {
    if (data.status == 200) {
      $('#request-' + user_id).fadeOut(300, function () {
        $(this).remove();
      });
    }
  });
}

function Wo_DeleteJoinGroup(user_id, group_id) {
  $.get(Wo_Ajax_Requests_File(), {f:'groups', s:'delete_request', user_id:user_id, group_id:group_id}, function(data) {
    if (data.status == 200) {
      $('#request-' + user_id).fadeOut(300, function () {
        $(this).remove();
      });
    }
  });
}

function Wo_DeleteJoinedUser(user_id, group_id) {
  $.get(Wo_Ajax_Requests_File(), {f:'groups', s:'delete_joined_user', user_id:user_id, group_id:group_id}, function(data) {
    if (data.status == 200) {
      $('#member-' + user_id).fadeOut(300, function () {
        $(this).remove();
      });
    }
  });
}

function Wo_OpenReplyBox(id) {
  Wo_ViewMoreReplies(id);
   $('#comment_' + id).find('.comment-replies').slideDown(50, function () {
     $('#comment_' + id).find('.comment-reply').slideDown(50);
   });
}
// register post comment
function Wo_RegisterReply(text, comment_id, user_id, event, page_id, type) {
  if(event.keyCode == 13 && event.shiftKey == 0) {
    comment_wrapper = $('[id=comment_' + comment_id + ']');
    reply_textarea = comment_wrapper.find('.comment-replies');
    textarea_wrapper = reply_textarea.find('.textarea');
    reply_list = comment_wrapper.find('.comment-replies-text');
    if(text == '') {
      return false;
    }
    $.post(Wo_Ajax_Requests_File() + '?f=posts&s=register_reply', {
      comment_id: comment_id,
      text: text,
      user_id: user_id,
      page_id: page_id
    }, function (data) {
      textarea_wrapper.val('');
      if(data.status == 200) {
        Wo_OpenReplyBox(comment_id);
        comment_wrapper.find('.reply-container:last-child').after(data.html);
        comment_wrapper.find('[id=comment-replies]').html(data.replies_num);
      }
    });
  }
}

function Wo_ViewMoreReplies(comment_id) {
  main_wrapper = $('[id=comment_' + comment_id + ']');
  view_more_wrapper = main_wrapper.find('.view-more-replies');
  Wo_progressIconLoader(view_more_wrapper);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'load_more_replies',
    comment_id: comment_id
  }, function (data) {
    if(data.status == 200) {
      main_wrapper.find('.comment-replies-text').html(data.html);
      main_wrapper.find('.comment-reply').fadeIn(100);
      view_more_wrapper.remove();
    }
  });
}

function Wo_RegsiterRecent(id, type) {
  $.get(Wo_Ajax_Requests_File(), {
    f: 'register_recent_search',
    id: id,
    type:type
  }, function (data) {
    if(data.status == 200) {
      window.location.href = data.href;
    }
  });
} 

function Wo_RemoveAlbumImage(post_id, id) {
  $.get(Wo_Ajax_Requests_File(), {
    f: 'delete_album_image',
    id: id,
    post_id: post_id
  }, function (data) {
    if(data.status == 200) {
      $('#post-' + post_id).find('#image-' + id).fadeOut(200, function () {
        $(this).remove();
      });
    }
  });
}
function Wo_ShowDeleteButton(post_id, id) {
  $('#post-' + post_id).find('#image-' + id).find('span').fadeIn(200);
}
function Wo_HideDeleteButton(post_id, id) {
  $('#post-' + post_id).find('#image-' + id).find('span').fadeOut(200);
}
function Wo_RegisterInvite(user_id, page_id) {
  Wo_progressIconLoader($('#invite-' + user_id).find('button'));
  $.get(Wo_Ajax_Requests_File(), {
    f: 'register_page_invite',
    user_id: user_id,
    page_id: page_id
  }, function (data) {
    if(data.status == 200) {
      $('#invite-' + user_id).fadeOut(200, function () {
        $(this).remove();
      });
    }
  });
}

function Wo_RegisterAddGroup(user_id, group_id) {
  Wo_progressIconLoader($('#add-' + user_id).find('button'));
  $.get(Wo_Ajax_Requests_File(), {
    f: 'register_group_add',
    user_id: user_id,
    group_id: group_id
  }, function (data) {
    if(data.status == 200) {
      $('#add-' + user_id).fadeOut(200, function () {
        $(this).remove();
      });
    }
  });
}

function Wo_SkipStep(type) {
  $.get(Wo_Ajax_Requests_File(), {
    f: 'skip_step',
    type: type
  }, function (data) {
    if(data.status == 200) {
     window.location.reload();
    }
  });
}
function Wo_AddEmoToCommentInput(post_id, code) {
    inputTag = $('[id=post-' + post_id + ']').find('.comment-textarea');
    inputVal = inputTag.val();
    if (typeof(inputTag.attr('placeholder')) != "undefined") {
        inputPlaceholder = inputTag.attr('placeholder');
        if (inputPlaceholder == inputVal) {
            inputTag.val('');
            inputVal = inputTag.val();
        }
    }
    if (inputVal.length == 0) {
        inputTag.val(code + ' ');
    } else {
        inputTag.val(inputVal + ' ' + code);
    }
    inputTag.keyup().focus();
}
function Wo_SendMessages() {
  $.get(Wo_Ajax_Requests_File(), {f: 'send_mails'});
}
// request permission on page load
document.addEventListener('DOMContentLoaded', function () {
  if (Notification.permission !== "granted")
    Notification.requestPermission();
});

function Wo_NotifyMe(icon, title, notification_text, url) {
  if (!Notification) {
    return;
  }
  if (Notification.permission !== "granted")
    Notification.requestPermission();
  else {
    var notification = new Notification(title, {
      icon: icon,
      body: notification_text,
    });
    
    notification.onclick = function () {
      window.open(url);
      notification.close();
      Wo_OpenNotificationsMenu();    
    };
  }
}
function Wo_CheckForCallAnswer(id) {
  $.get(Wo_Ajax_Requests_File(), {f:'check_for_answer', id:id}, function (data1) {
    if (data1.status == 200) {
      clearTimeout(checkcalls);
      $('#calling-modal').find('.modal-title').html('<i class="fa fa fa-video-camera"></i> ' + data1.text_answered);
      $('#calling-modal').find('.modal-body p').text(data1.text_please_wait);
      setTimeout(function () {
          window.location.href = data1.url;
      }, 1000);
      return false;
    } else if (data1.status == 400) {
      clearTimeout(checkcalls);
      Wo_PlayAudioCall('stop');
      $('#calling-modal').find('.modal-title').html('<i class="fa fa fa-times"></i> ' + data1.text_call_declined);
      $('#calling-modal').find('.modal-body p').text(data1.text_call_declined_desc);
    }
    checkcalls = setTimeout(function () {
        Wo_CheckForCallAnswer(id);
    }, 2000);
  });
}

function Wo_CheckForAudioCallAnswer(id) {
  $.get(Wo_Ajax_Requests_File(), {f:'check_for_audio_answer', id:id}, function (data1) {
    if (data1.status == 200) {
      clearTimeout(checkcalls);
      $('#calling-modal').find('.modal-title').html('<i class="fa fa fa-phone"></i> ' + data1.text_answered);
      $('#calling-modal').find('.modal-body p').text(data1.text_please_wait);
      Wo_PlayAudioCall('stop');
      setTimeout(function () {
          $( '#calling-modal' ).remove();
          $( '.modal-backdrop' ).remove();
          $( 'body' ).removeClass( "modal-open" );
          $('body').append(data1.calls_html);
          $('#re-talking-modal').modal({
            show: true
          });
      }, 3000);
    } else if (data1.status == 400) {
      clearTimeout(checkcalls);
      Wo_PlayAudioCall('stop');
      $('#calling-modal').find('.modal-title').html('<i class="fa fa fa-times"></i> ' + data1.text_call_declined);
      $('#calling-modal').find('.modal-body p').text(data1.text_call_declined_desc);
    } else {
      checkcalls = setTimeout(function () {
        Wo_CheckForAudioCallAnswer(id);
      }, 2000);
    }
  });
}

function Wo_AnswerCall(id, url, type) {
  type1 = 'video';
  if (type == 'video') {
     type1 = 'video';
  } else if (type == 'audio') {
    type1 = 'audio';
  }
  Wo_progressIconLoader($('#re-calling-modal').find('.answer-call'));
  $.get(Wo_Ajax_Requests_File(), {f:'answer_call', id:id, type:type1}, function (data) {
    Wo_PlayVideoCall('stop');
    if (type1 == 'video') {
      if (data.status == 200) {
         window.location.href = url;
      }
    } else {
      $( '#re-calling-modal' ).remove();
      $( '.modal-backdrop' ).remove();
      $( 'body' ).removeClass( "modal-open" );
      $('body').append(data.calls_html);
      $('#re-talking-modal').modal({
        show: true
      });
    }
    Wo_progressIconLoader($('#re-calling-modal').find('.answer-call'));
  });
}
function Wo_DeclineCall(id, url, type) {
  type1 = 'video';
  if (type == 'video') {
     type1 = 'video';
  } else if (type == 'audio') {
    type1 = 'audio';
  }
  Wo_progressIconLoader($('#re-calling-modal').find('.decline-call'));
  $.get(Wo_Ajax_Requests_File(), {f:'decline_call', id:id, type:type1}, function (data) {
    if (data.status == 200) {
      Wo_PlayVideoCall('stop');
      $( '#re-calling-modal' ).remove();
      $( '.modal-backdrop' ).remove();
      $( 'body' ).removeClass( "modal-open" );
    }
  });
}

function Wo_CloseCall(id) {
  Wo_progressIconLoader($('#re-talking-modal').find('.decline-call'));
  $.get(Wo_Ajax_Requests_File(), {f:'close_call', id:id}, function (data) {
    if (data.status == 200) {
      $( '#re-talking-modal' ).remove();
      $( '.modal-backdrop' ).remove();
      $( 'body' ).removeClass( "modal-open");
    }
  });
}

function Wo_CancelCall() {
  Wo_progressIconLoader($('#calling-modal').find('.cancel-call'));
  $.get(Wo_Ajax_Requests_File(), {f:'cancel_call'}, function (data) {
    if (data.status == 200) {
      Wo_PlayAudioCall('stop');
      $( '#calling-modal' ).remove();
      $( '.modal-backdrop' ).remove();
      $( 'body' ).removeClass( "modal-open" );
    }
  });
}
function Wo_GenerateVideoCall(user_id1, user_id2) {
  $.get(Wo_Ajax_Requests_File(), {f:'create_new_video_call', 'new': 'true', user_id1: user_id1, user_id2:user_id2}, function(data) {
      if (data.status == 200) {
          $('body').append(data.html);
           $('#calling-modal').modal({
             show: true
           });
           checkcalls = setTimeout(function () {
              Wo_CheckForCallAnswer(data.id);
           }, 2000);
           setTimeout(function() {
            $('#calling-modal').find('.modal-title').html('<i class="fa fa fa-video-camera"></i> ' + data.text_no_answer);
            $('#calling-modal').find('.modal-body p').text(data.text_please_try_again_later);
            clearTimeout(checkcalls);
            Wo_PlayAudioCall('stop');
           }, 43000);
          Wo_PlayAudioCall('play');
    }
   });
}

function Wo_GenerateVoiceCall(user_id1, user_id2) {
  $.get(Wo_Ajax_Requests_File(), {f:'create_new_audio_call', 'new': 'true', user_id1: user_id1, user_id2:user_id2}, function(data) {
      if (data.status == 200) {
          $('body').append(data.html);
           $('#calling-modal').modal({
             show: true
           });
           checkcalls = setTimeout(function () {
              Wo_CheckForAudioCallAnswer(data.id);
           }, 2000);
           setTimeout(function() {
            $('#calling-modal').find('.modal-title').html('<i class="fa fa fa-phone"></i> ' + data.text_no_answer);
            $('#calling-modal').find('.modal-body p').text(data.text_please_try_again_later);
            clearTimeout(checkcalls);
            Wo_PlayAudioCall('stop');
           }, 43000);
          Wo_PlayAudioCall('play');
    }
   });
}

function Wo_PlayAudioCall(type) {
  if (type == 'play') {
    document.getElementById('calling-sound').play();
    playmusic_ = setTimeout(function() {
       Wo_PlayAudioCall('play');
    }, 100);
  } else {
    clearTimeout(playmusic_);
    document.getElementById('calling-sound').pause();
  }
}
function Wo_PlayVideoCall(type) {
  if (type == 'play') {
    document.getElementById('video-calling-sound').play();
    playmusic = setTimeout(function() {
       Wo_PlayVideoCall('play');
    }, 100);
  } else {
    clearTimeout(playmusic);
    document.getElementById('video-calling-sound').pause();
  }
}
function textAreaAdjust(o, h, n) {
    if (n == 'lightbox') {
      recording_node = "comm";
    } else {
       o.style.height = h + 'px';
       o.style.height = (5+o.scrollHeight)+"px";
    }
    if (n == 'comm') {
      recording_node = "comm";
    }
}

function Wo_MarkAsSold(post_id, product_id) {
  var post = $('#post-' + post_id);
  post.find('.mark-as-sold-post').html('<div class="post_drop_menu_loading"><div class="ball-pulse"><div></div><div></div><div></div></div></div>');
  $.get(Wo_Ajax_Requests_File(), {
    f: 'posts',
    s: 'mark_as_sold_post',
    post_id: post_id,
    product_id: product_id
  }, function (data) {
    if(data.status == 200) {
      post.find('.product-status').text(data.text);
      post.find('.mark-as-sold-post').html(data.text);
    }
  });
}

function Wo_VoteUp(id) {
  var $vote_con = $('#option-' + id);
  var $post_id = $vote_con.attr('data-post-id');
  if ($post_id.length == 0) {
     return false;
  }
  $is_voted = $('#post-' + $post_id).find('.options').attr('data-vote');
  if ($is_voted.length == 0) {
     return false;
  }
  if ($is_voted == 'false') {
     $vote_con.find('.vote-icon').html('<i class="fa fa-check-circle"></i>');
  }
  $('#post-' + $post_id).find('.options').attr('data-vote', true);
  $.get(Wo_Ajax_Requests_File(), {f:'vote_up', id:id}, function (data) {
       if (data.status == 200) {
        $('#post-' + $post_id).find('.total-votes').removeClass('hidden');
    $('#post-' + $post_id).find('.result-bar-parent').removeClass('hidden');
    $('#post-' + $post_id).find('.answer-vote').removeClass('hidden');
        data.votes.forEach(function(option) {
          $('#post-' + $post_id).find('#total-votes').text(option.all);
          $('#option-' + option.id).find('.answer-vote').html(option.percentage);
          if (option.percentage_num > 0) {
            $('#option-' + option.id).find('.result-bar').text(' ').css('width', option.percentage);
          }
        });
      } else if (data.status == 400) {
         alert(data.text);
      } 
  });
}


function Wo_UploadCommentImage(id) {
  var image_container = $('#post-' + id);
  var fetched_image = image_container.find('#comment-image');
  var data = new FormData();
  data.append('image', $('#comment_image_' + id).prop('files')[0]);
  image_container.find('#wo_comment_combo .ball-pulse').fadeIn(100);
  $.ajax({
        type: "POST",
        url: Wo_Ajax_Requests_File() + '?f=upload_image&id=' + id,
        data: data,
        processData: false,
        contentType: false,
        success: function (data) {
          if (data.status == 200) {
            fetched_image.html('<img src="' + data.image + '"><div class="remove-icon" onclick="Wo_EmptyCommentImage(' + id + ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></div>');
            image_container.find('#comment_src_image').val(data.image_src);
            fetched_image.removeClass('hidden');
            image_container.find('.comment-textarea').focus();
          }
          image_container.find('#wo_comment_combo .ball-pulse').fadeOut(100);
        }
    });
}

function Wo_EmptyCommentImage(id) {
  var image_container = $('#post-' + id);
  var fetched_image = image_container.find('#comment-image');
  image_container.find('.comment-image-con').empty().addClass('hidden');
  image_container.find('#comment_src_image').val('');
  image_container.find('#comment_src_image').val('');
  image_container.find('#comment_image_' + id).val('');
}

function Wo_TurnOffSound() {
  var sound = $('.turn-off-sound');
  Wo_progressIconLoader(sound);
  $.get(Wo_Ajax_Requests_File(), {
    f: 'turn-off-sound'
  }, function (data) {
    if(data.status == 200) {
      sound.find('span').html(data.message);
    }
  });
}

function Wo_Del_Article(id) {
    $("#delete-blog").find('.ball-pulse').fadeIn(100);
    $.ajax({
        type: "GET",
        url: Wo_Ajax_Requests_File(),
        data: {
            id: id,
            f: 'delete-my-blog'
        },
        dataType: 'json',
        success: function(data) {
            if (data['status'] == 200) {
                $("#delete-blog").modal("hide");
                $("div[data-rm-blog='" + data['id'] + "']").fadeOut(function() {
                    $(this).remove()
                });
            }
            $("#delete-blog").find('.ball-pulse').fadeOut(100);
        }
    });
}

function Wo_DelReply(id) {
  if (!id) {
    return false;
  }else{

      Wo_progressIconLoader($('#delete-reply').find('button'));
      $.ajax({
          type: "GET",
          url: Wo_Ajax_Requests_File(),
          data: {
              id: id,
              f: 'delete-reply'
          },
          dataType: 'json',
          success: function(data) {
              if (data['status'] == 200) {
                  $("#delete-reply").modal("hide");
                  $("[data-thread-reply='" + id + "']").slideUp(function() {
                      $(this).remove()
                  });
              }
              Wo_progressIconLoader($('#delete-reply').find('button'));
          }
      });
  }
}

function Wo_DelThread(id) {
  if (!id) {
    return false;
  }else{

      Wo_progressIconLoader($('#delete-thread').find('button'));
      $.ajax({
          type: "GET",
          url: Wo_Ajax_Requests_File(),
          data: {
              id: id,
              f: 'delete-thread'
          },
          dataType: 'json',
          success: function(data) {
              if (data['status'] == 200) {
                  $("#delete-thread").modal("hide");
                  $("[data-thread-ident='" + id + "']").slideUp(function() {
                      $(this).remove()
                  });
              }
              Wo_progressIconLoader($('#delete-thread').find('button'));
          }
      });
  }
}
var Wo_Delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();
function Wo_AddVideoViews(post_id){
    if (post_id && typeof(Number(post_id)) == 'number'  && post_id > 0) {
      Wo_Delay(function(){
        $.ajax({
          url: Wo_Ajax_Requests_File(),
          type: 'GET',
          dataType: 'json',
          data: {f:'posts', s:'add-video-view',post_id:post_id},
        })
        .done(function(data) {
          if (data.status == 200) {
            $("span[data-post-video-views="+post_id+"]").text(data.views);
            $("video[data-post-video="+post_id+"]").removeAttr('onplay');
          }
        })
      },5000)
    }
  }
function Wo_DeleteStatus(id){
  if (!id || !confirm('Are you sure you want to delete your status?')) {
    return false;
  }

  $.ajax({
    url: Wo_Ajax_Requests_File(),
    type: 'GET',
    dataType: 'json',
    data: {f: 'status',s:'remove',id:id},
  })
  .done(function(data) {
    if (data.status == 200) {
      $("[data-status-id='"+id+"']").slideUp(function(){
        $(this).remove();
      })
    }
  })
  .fail(function() {
    console.log("error");
  })
}

function Wo_StoryProgress(){
  $('.mfp-progress-line').html('<span width="0"></span>').find('span').delay(1).queue(function () {
    $(this).css('width', '100%')
  });   
}


function Wo_EditReplyComment(id){
  if (!id) { return false;}
  var self = $("div[data-post-comm-reply-edit='"+id+"']").toggleClass('hidden');  
  self.find('textarea').val($("div[data-post-comm-reply-text='"+id+"']").text().trim());
}

function Wo_UpdatCommReply(id,event,self){
  if (!id || !event || !self) {
    return false;
  }

  else if (event.keyCode == 13 && event.shiftKey == 0) {
    var text = $(self).val();
    var id   = id;
    $.ajax({
      url: Wo_Ajax_Requests_File() + "?f=posts&s=update-reply",
      type: 'POST',
      dataType: 'json',
      data: {id:id,text:text},
    })
    .done(function(data) {
      if (data.status == 200) {
        $("div[data-post-comm-reply-text='"+id+"']").text(text);
        var edit_box = $("div[data-post-comm-reply-edit='"+id+"']").addClass('hidden');
        edit_box.find('textarea').val('');
      }
    })
    .fail(function() {
      console.log("error");
    })
    
  }

}

function Wo_HidePost(post_id){
  if (!post_id) {
    return false;
  }

  $.ajax({
    url: Wo_Ajax_Requests_File(),
    type: 'GET',
    dataType: 'json',
    data: {f: 'posts',s:'hide_post',post:post_id},
  })
  .done(function(data) {
    if (data.status == 200) {
      $("#post-"+post_id).slideUp(function(){
        $(this).remove();
      })
    }
  })
  .fail(function() {
    console.log("error");
  })
}

function Wo_SharePost(post_id, owner_id, self){

  if (!post_id || !owner_id || !self) {
    return false;
  }
  $.ajax({
    url: Wo_Ajax_Requests_File(),
    type: 'GET',
    dataType: 'json',
    data: {f: 'posts',s:'share-post',id:post_id,usr:owner_id},
  })
  .done(function(data) {
    if (data.status == 200) {
      $("#post-shared").modal('show');
        setTimeout(function(){
        $("#post-shared").modal('hide');
        $('#post-' + post_id).find('.post-share').slideUp('fast')
      },3000); 
    }
  })
  .fail(function() {
    console.log("error");
  })
  
}

function Wo_AddGroupUserAdmin(member_id, group_id, self){
  if (!member_id || !group_id || !self) {
    return false;
  }
  $.ajax({
    url: Wo_Ajax_Requests_File(),
    type: 'GET',
    dataType: 'json',
    data: {f: 'groups',s:'add_admin',user_id:member_id,group_id:group_id},
  })
  .done(function(data) {
    if (data.status == 200 && data.code == 1) {
      $(self).find('span').html('<i class="fa fa-times-circle-o"></i>');
    }
    else if(data.status == 200 && data.code == 0){
      $(self).find('span').html('<i class="fa fa-plus-square-o"></i>');
    }
  })
  .fail(function() {
    console.log("error");
  })
  
}

function Wo_OpenLighteBox(self ,event){
  if (!self || !event) {
    return false;
  }
  event.stopPropagation();
  var url = $(self).attr('data-href');
  $('#modal_light_box').modal('show').find('.image').attr('src', url);
}

function Wo_UpdateLocation(position) {
  if (!position) {
    return false; 
  }
  $.post(Wo_Ajax_Requests_File() + '?f=save_user_location', {lat: position.coords.latitude, lng:position.coords.longitude}, function(data, textStatus, xhr) {
    if (data.status == 200) {
      return true;
    }
  });
  return false;
}


var Wo_ElementLoad = function(selector, callback){
    $(selector).each(function(){
        if (this.complete || $(this).height() > 0) {
            callback.apply(this);
        }
        else {
            $(this).on('load', function(){
                callback.apply(this);
            });
        }
    });
};


function Wo_NextProductPicture(product_id, id) {
  Wo_CloseLightbox();
  $('body').append('<div class="lightbox-container"><div class="lightbox-backgrond" onclick="Wo_CloseLightbox();"></div><div class="lb-preloader" style="display:block"><svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#676d76" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div></div>');
  $.get(Wo_Ajax_Requests_File(), {f:'get_next_product_image', product_id:product_id, after_image_id:id}, function(data) {
    if (data.status == 200) {
    document.body.style.overflow = 'hidden';
      $('.lightbox-container').html(data.html);
      $( ".changer").fadeIn(200);
    }
  });
}

function Wo_PreviousProductPicture(product_id, id) {
  Wo_CloseLightbox();
  $('body').append('<div class="lightbox-container"><div class="lightbox-backgrond" onclick="Wo_CloseLightbox();"></div><div class="lb-preloader" style="display:block"><svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#676d76" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg></div></div>');
  $.get(Wo_Ajax_Requests_File(), {f:'get_previous_product_image', product_id:product_id, before_image_id:id}, function(data) {
    if (data.status == 200) {
    document.body.style.overflow = 'hidden';
      $('.lightbox-container').html(data.html);
      $( ".changer").fadeIn(200);
    }
  });
}
function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}




/*Stickey Sidebar*/
!function(i){i.fn.theiaStickySidebar=function(t){function e(t,e){var a=o(t,e);a||(console.log("TSS: Body width smaller than options.minWidth. Init is delayed."),i(document).on("scroll."+t.namespace,function(t,e){return function(a){var n=o(t,e);n&&i(this).unbind(a)}}(t,e)),i(window).on("resize."+t.namespace,function(t,e){return function(a){var n=o(t,e);n&&i(this).unbind(a)}}(t,e)))}function o(t,e){return t.initialized===!0||!(i("body").width()<t.minWidth)&&(a(t,e),!0)}function a(t,e){t.initialized=!0;var o=i("#theia-sticky-sidebar-stylesheet-"+t.namespace);0===o.length&&i("head").append(i('<style id="theia-sticky-sidebar-stylesheet-'+t.namespace+'">.theiaStickySidebar:after {content: ""; display: table; clear: both;}</style>')),e.each(function(){function e(){a.fixedScrollTop=0,a.sidebar.css({"min-height":"1px"}),a.stickySidebar.css({position:"static",width:"",transform:"none"})}function o(t){var e=t.height();return t.children().each(function(){e=Math.max(e,i(this).height())}),e}var a={};if(a.sidebar=i(this),a.options=t||{},a.container=i(a.options.containerSelector),0==a.container.length&&(a.container=a.sidebar.parent()),a.sidebar.parents().css("-webkit-transform","none"),a.sidebar.css({position:a.options.defaultPosition,overflow:"visible","-webkit-box-sizing":"border-box","-moz-box-sizing":"border-box","box-sizing":"border-box"}),a.stickySidebar=a.sidebar.find(".theiaStickySidebar"),0==a.stickySidebar.length){var s=/(?:text|application)\/(?:x-)?(?:javascript|ecmascript)/i;a.sidebar.find("script").filter(function(i,t){return 0===t.type.length||t.type.match(s)}).remove(),a.stickySidebar=i("<div>").addClass("theiaStickySidebar").append(a.sidebar.children()),a.sidebar.append(a.stickySidebar)}a.marginBottom=parseInt(a.sidebar.css("margin-bottom")),a.paddingTop=parseInt(a.sidebar.css("padding-top")),a.paddingBottom=parseInt(a.sidebar.css("padding-bottom"));var r=a.stickySidebar.offset().top,d=a.stickySidebar.outerHeight();a.stickySidebar.css("padding-top",1),a.stickySidebar.css("padding-bottom",1),r-=a.stickySidebar.offset().top,d=a.stickySidebar.outerHeight()-d-r,0==r?(a.stickySidebar.css("padding-top",0),a.stickySidebarPaddingTop=0):a.stickySidebarPaddingTop=1,0==d?(a.stickySidebar.css("padding-bottom",0),a.stickySidebarPaddingBottom=0):a.stickySidebarPaddingBottom=1,a.previousScrollTop=null,a.fixedScrollTop=0,e(),a.onScroll=function(a){if(a.stickySidebar.is(":visible")){if(i("body").width()<a.options.minWidth)return void e();if(a.options.disableOnResponsiveLayouts){var s=a.sidebar.outerWidth("none"==a.sidebar.css("float"));if(s+50>a.container.width())return void e()}var r=i(document).scrollTop(),d="static";if(r>=a.sidebar.offset().top+(a.paddingTop-a.options.additionalMarginTop)){var c,p=a.paddingTop+t.additionalMarginTop,b=a.paddingBottom+a.marginBottom+t.additionalMarginBottom,l=a.sidebar.offset().top,f=a.sidebar.offset().top+o(a.container),h=0+t.additionalMarginTop,g=a.stickySidebar.outerHeight()+p+b<i(window).height();c=g?h+a.stickySidebar.outerHeight():i(window).height()-a.marginBottom-a.paddingBottom-t.additionalMarginBottom;var u=l-r+a.paddingTop,S=f-r-a.paddingBottom-a.marginBottom,y=a.stickySidebar.offset().top-r,m=a.previousScrollTop-r;"fixed"==a.stickySidebar.css("position")&&"modern"==a.options.sidebarBehavior&&(y+=m),"stick-to-top"==a.options.sidebarBehavior&&(y=t.additionalMarginTop),"stick-to-bottom"==a.options.sidebarBehavior&&(y=c-a.stickySidebar.outerHeight()),y=m>0?Math.min(y,h):Math.max(y,c-a.stickySidebar.outerHeight()),y=Math.max(y,u),y=Math.min(y,S-a.stickySidebar.outerHeight());var k=a.container.height()==a.stickySidebar.outerHeight();d=(k||y!=h)&&(k||y!=c-a.stickySidebar.outerHeight())?r+y-a.sidebar.offset().top-a.paddingTop<=t.additionalMarginTop?"static":"absolute":"fixed"}if("fixed"==d){var v=i(document).scrollLeft();a.stickySidebar.css({position:"fixed",width:n(a.stickySidebar)+"px",transform:"translateY("+y+"px)",left:a.sidebar.offset().left+parseInt(a.sidebar.css("padding-left"))-v+"px",top:"0px"})}else if("absolute"==d){var x={};"absolute"!=a.stickySidebar.css("position")&&(x.position="absolute",x.transform="translateY("+(r+y-a.sidebar.offset().top-a.stickySidebarPaddingTop-a.stickySidebarPaddingBottom)+"px)",x.top="0px"),x.width=n(a.stickySidebar)+"px",x.left="",a.stickySidebar.css(x)}else"static"==d&&e();"static"!=d&&1==a.options.updateSidebarHeight&&a.sidebar.css({"min-height":a.stickySidebar.outerHeight()+a.stickySidebar.offset().top-a.sidebar.offset().top+a.paddingBottom}),a.previousScrollTop=r}},a.onScroll(a),i(document).on("scroll."+a.options.namespace,function(i){return function(){i.onScroll(i)}}(a)),i(window).on("resize."+a.options.namespace,function(i){return function(){i.stickySidebar.css({position:"static"}),i.onScroll(i)}}(a)),"undefined"!=typeof ResizeSensor&&new ResizeSensor(a.stickySidebar[0],function(i){return function(){i.onScroll(i)}}(a))})}function n(i){var t;try{t=i[0].getBoundingClientRect().width}catch(i){}return"undefined"==typeof t&&(t=i.width()),t}var s={containerSelector:"",additionalMarginTop:0,additionalMarginBottom:0,updateSidebarHeight:!0,minWidth:0,disableOnResponsiveLayouts:!0,sidebarBehavior:"modern",defaultPosition:"relative",namespace:"TSS"};return t=i.extend(s,t),t.additionalMarginTop=parseInt(t.additionalMarginTop)||0,t.additionalMarginBottom=parseInt(t.additionalMarginBottom)||0,e(t,this),this}}(jQuery);



/*Pro users Slider*/
!function(i){"use strict";"function"==typeof define&&define.amd?define(["jquery"],i):"undefined"!=typeof exports?module.exports=i(require("jquery")):i(jQuery)}(function(i){"use strict";var e=window.Slick||{};(e=function(){var e=0;return function(t,o){var s,n=this;n.defaults={accessibility:!0,adaptiveHeight:!1,appendArrows:i(t),appendDots:i(t),arrows:!0,asNavFor:null,prevArrow:'<button class="slick-prev" aria-label="Previous" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg></button>',nextArrow:'<button class="slick-next" aria-label="Next" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg></button>',autoplay:!1,autoplaySpeed:3e3,centerMode:!1,centerPadding:"50px",cssEase:"ease",customPaging:function(e,t){return i('<button type="button" />').text(t+1)},dots:!1,dotsClass:"slick-dots",draggable:!0,easing:"linear",edgeFriction:.35,fade:!1,focusOnSelect:!1,focusOnChange:!1,infinite:!0,initialSlide:0,lazyLoad:"ondemand",mobileFirst:!1,pauseOnHover:!0,pauseOnFocus:!0,pauseOnDotsHover:!1,respondTo:"window",responsive:null,rows:1,rtl:!1,slide:"",slidesPerRow:1,slidesToShow:1,slidesToScroll:1,speed:500,swipe:!0,swipeToSlide:!1,touchMove:!0,touchThreshold:5,useCSS:!0,useTransform:!0,variableWidth:!1,vertical:!1,verticalSwiping:!1,waitForAnimate:!0,zIndex:1e3},n.initials={animating:!1,dragging:!1,autoPlayTimer:null,currentDirection:0,currentLeft:null,currentSlide:0,direction:1,$dots:null,listWidth:null,listHeight:null,loadIndex:0,$nextArrow:null,$prevArrow:null,scrolling:!1,slideCount:null,slideWidth:null,$slideTrack:null,$slides:null,sliding:!1,slideOffset:0,swipeLeft:null,swiping:!1,$list:null,touchObject:{},transformsEnabled:!1,unslicked:!1},i.extend(n,n.initials),n.activeBreakpoint=null,n.animType=null,n.animProp=null,n.breakpoints=[],n.breakpointSettings=[],n.cssTransitions=!1,n.focussed=!1,n.interrupted=!1,n.hidden="hidden",n.paused=!0,n.positionProp=null,n.respondTo=null,n.rowCount=1,n.shouldClick=!0,n.$slider=i(t),n.$slidesCache=null,n.transformType=null,n.transitionType=null,n.visibilityChange="visibilitychange",n.windowWidth=0,n.windowTimer=null,s=i(t).data("slick")||{},n.options=i.extend({},n.defaults,o,s),n.currentSlide=n.options.initialSlide,n.originalSettings=n.options,void 0!==document.mozHidden?(n.hidden="mozHidden",n.visibilityChange="mozvisibilitychange"):void 0!==document.webkitHidden&&(n.hidden="webkitHidden",n.visibilityChange="webkitvisibilitychange"),n.autoPlay=i.proxy(n.autoPlay,n),n.autoPlayClear=i.proxy(n.autoPlayClear,n),n.autoPlayIterator=i.proxy(n.autoPlayIterator,n),n.changeSlide=i.proxy(n.changeSlide,n),n.clickHandler=i.proxy(n.clickHandler,n),n.selectHandler=i.proxy(n.selectHandler,n),n.setPosition=i.proxy(n.setPosition,n),n.swipeHandler=i.proxy(n.swipeHandler,n),n.dragHandler=i.proxy(n.dragHandler,n),n.keyHandler=i.proxy(n.keyHandler,n),n.instanceUid=e++,n.htmlExpr=/^(?:\s*(<[\w\W]+>)[^>]*)$/,n.registerBreakpoints(),n.init(!0)}}()).prototype.activateADA=function(){this.$slideTrack.find(".slick-active").attr({"aria-hidden":"false"}).find("a, input, button, select").attr({tabindex:"0"})},e.prototype.addSlide=e.prototype.slickAdd=function(e,t,o){var s=this;if("boolean"==typeof t)o=t,t=null;else if(t<0||t>=s.slideCount)return!1;s.unload(),"number"==typeof t?0===t&&0===s.$slides.length?i(e).appendTo(s.$slideTrack):o?i(e).insertBefore(s.$slides.eq(t)):i(e).insertAfter(s.$slides.eq(t)):!0===o?i(e).prependTo(s.$slideTrack):i(e).appendTo(s.$slideTrack),s.$slides=s.$slideTrack.children(this.options.slide),s.$slideTrack.children(this.options.slide).detach(),s.$slideTrack.append(s.$slides),s.$slides.each(function(e,t){i(t).attr("data-slick-index",e)}),s.$slidesCache=s.$slides,s.reinit()},e.prototype.animateHeight=function(){var i=this;if(1===i.options.slidesToShow&&!0===i.options.adaptiveHeight&&!1===i.options.vertical){var e=i.$slides.eq(i.currentSlide).outerHeight(!0);i.$list.animate({height:e},i.options.speed)}},e.prototype.animateSlide=function(e,t){var o={},s=this;s.animateHeight(),!0===s.options.rtl&&!1===s.options.vertical&&(e=-e),!1===s.transformsEnabled?!1===s.options.vertical?s.$slideTrack.animate({left:e},s.options.speed,s.options.easing,t):s.$slideTrack.animate({top:e},s.options.speed,s.options.easing,t):!1===s.cssTransitions?(!0===s.options.rtl&&(s.currentLeft=-s.currentLeft),i({animStart:s.currentLeft}).animate({animStart:e},{duration:s.options.speed,easing:s.options.easing,step:function(i){i=Math.ceil(i),!1===s.options.vertical?(o[s.animType]="translate("+i+"px, 0px)",s.$slideTrack.css(o)):(o[s.animType]="translate(0px,"+i+"px)",s.$slideTrack.css(o))},complete:function(){t&&t.call()}})):(s.applyTransition(),e=Math.ceil(e),!1===s.options.vertical?o[s.animType]="translate3d("+e+"px, 0px, 0px)":o[s.animType]="translate3d(0px,"+e+"px, 0px)",s.$slideTrack.css(o),t&&setTimeout(function(){s.disableTransition(),t.call()},s.options.speed))},e.prototype.getNavTarget=function(){var e=this,t=e.options.asNavFor;return t&&null!==t&&(t=i(t).not(e.$slider)),t},e.prototype.asNavFor=function(e){var t=this.getNavTarget();null!==t&&"object"==typeof t&&t.each(function(){var t=i(this).slick("getSlick");t.unslicked||t.slideHandler(e,!0)})},e.prototype.applyTransition=function(i){var e=this,t={};!1===e.options.fade?t[e.transitionType]=e.transformType+" "+e.options.speed+"ms "+e.options.cssEase:t[e.transitionType]="opacity "+e.options.speed+"ms "+e.options.cssEase,!1===e.options.fade?e.$slideTrack.css(t):e.$slides.eq(i).css(t)},e.prototype.autoPlay=function(){var i=this;i.autoPlayClear(),i.slideCount>i.options.slidesToShow&&(i.autoPlayTimer=setInterval(i.autoPlayIterator,i.options.autoplaySpeed))},e.prototype.autoPlayClear=function(){var i=this;i.autoPlayTimer&&clearInterval(i.autoPlayTimer)},e.prototype.autoPlayIterator=function(){var i=this,e=i.currentSlide+i.options.slidesToScroll;i.paused||i.interrupted||i.focussed||(!1===i.options.infinite&&(1===i.direction&&i.currentSlide+1===i.slideCount-1?i.direction=0:0===i.direction&&(e=i.currentSlide-i.options.slidesToScroll,i.currentSlide-1==0&&(i.direction=1))),i.slideHandler(e))},e.prototype.buildArrows=function(){var e=this;!0===e.options.arrows&&(e.$prevArrow=i(e.options.prevArrow).addClass("slick-arrow"),e.$nextArrow=i(e.options.nextArrow).addClass("slick-arrow"),e.slideCount>e.options.slidesToShow?(e.$prevArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),e.$nextArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),e.htmlExpr.test(e.options.prevArrow)&&e.$prevArrow.prependTo(e.options.appendArrows),e.htmlExpr.test(e.options.nextArrow)&&e.$nextArrow.appendTo(e.options.appendArrows),!0!==e.options.infinite&&e.$prevArrow.addClass("slick-disabled").attr("aria-disabled","true")):e.$prevArrow.add(e.$nextArrow).addClass("slick-hidden").attr({"aria-disabled":"true",tabindex:"-1"}))},e.prototype.buildDots=function(){var e,t,o=this;if(!0===o.options.dots){for(o.$slider.addClass("slick-dotted"),t=i("<ul />").addClass(o.options.dotsClass),e=0;e<=o.getDotCount();e+=1)t.append(i("<li />").append(o.options.customPaging.call(this,o,e)));o.$dots=t.appendTo(o.options.appendDots),o.$dots.find("li").first().addClass("slick-active")}},e.prototype.buildOut=function(){var e=this;e.$slides=e.$slider.children(e.options.slide+":not(.slick-cloned)").addClass("slick-slide"),e.slideCount=e.$slides.length,e.$slides.each(function(e,t){i(t).attr("data-slick-index",e).data("originalStyling",i(t).attr("style")||"")}),e.$slider.addClass("slick-slider"),e.$slideTrack=0===e.slideCount?i('<div class="slick-track"/>').appendTo(e.$slider):e.$slides.wrapAll('<div class="slick-track"/>').parent(),e.$list=e.$slideTrack.wrap('<div class="slick-list"/>').parent(),e.$slideTrack.css("opacity",0),!0!==e.options.centerMode&&!0!==e.options.swipeToSlide||(e.options.slidesToScroll=1),i("img[data-lazy]",e.$slider).not("[src]").addClass("slick-loading"),e.setupInfinite(),e.buildArrows(),e.buildDots(),e.updateDots(),e.setSlideClasses("number"==typeof e.currentSlide?e.currentSlide:0),!0===e.options.draggable&&e.$list.addClass("draggable")},e.prototype.buildRows=function(){var i,e,t,o,s,n,r,l=this;if(o=document.createDocumentFragment(),n=l.$slider.children(),l.options.rows>1){for(r=l.options.slidesPerRow*l.options.rows,s=Math.ceil(n.length/r),i=0;i<s;i++){var d=document.createElement("div");for(e=0;e<l.options.rows;e++){var a=document.createElement("div");for(t=0;t<l.options.slidesPerRow;t++){var c=i*r+(e*l.options.slidesPerRow+t);n.get(c)&&a.appendChild(n.get(c))}d.appendChild(a)}o.appendChild(d)}l.$slider.empty().append(o),l.$slider.children().children().children().css({width:100/l.options.slidesPerRow+"%",display:"inline-block"})}},e.prototype.checkResponsive=function(e,t){var o,s,n,r=this,l=!1,d=r.$slider.width(),a=window.innerWidth||i(window).width();if("window"===r.respondTo?n=a:"slider"===r.respondTo?n=d:"min"===r.respondTo&&(n=Math.min(a,d)),r.options.responsive&&r.options.responsive.length&&null!==r.options.responsive){s=null;for(o in r.breakpoints)r.breakpoints.hasOwnProperty(o)&&(!1===r.originalSettings.mobileFirst?n<r.breakpoints[o]&&(s=r.breakpoints[o]):n>r.breakpoints[o]&&(s=r.breakpoints[o]));null!==s?null!==r.activeBreakpoint?(s!==r.activeBreakpoint||t)&&(r.activeBreakpoint=s,"unslick"===r.breakpointSettings[s]?r.unslick(s):(r.options=i.extend({},r.originalSettings,r.breakpointSettings[s]),!0===e&&(r.currentSlide=r.options.initialSlide),r.refresh(e)),l=s):(r.activeBreakpoint=s,"unslick"===r.breakpointSettings[s]?r.unslick(s):(r.options=i.extend({},r.originalSettings,r.breakpointSettings[s]),!0===e&&(r.currentSlide=r.options.initialSlide),r.refresh(e)),l=s):null!==r.activeBreakpoint&&(r.activeBreakpoint=null,r.options=r.originalSettings,!0===e&&(r.currentSlide=r.options.initialSlide),r.refresh(e),l=s),e||!1===l||r.$slider.trigger("breakpoint",[r,l])}},e.prototype.changeSlide=function(e,t){var o,s,n,r=this,l=i(e.currentTarget);switch(l.is("a")&&e.preventDefault(),l.is("li")||(l=l.closest("li")),n=r.slideCount%r.options.slidesToScroll!=0,o=n?0:(r.slideCount-r.currentSlide)%r.options.slidesToScroll,e.data.message){case"previous":s=0===o?r.options.slidesToScroll:r.options.slidesToShow-o,r.slideCount>r.options.slidesToShow&&r.slideHandler(r.currentSlide-s,!1,t);break;case"next":s=0===o?r.options.slidesToScroll:o,r.slideCount>r.options.slidesToShow&&r.slideHandler(r.currentSlide+s,!1,t);break;case"index":var d=0===e.data.index?0:e.data.index||l.index()*r.options.slidesToScroll;r.slideHandler(r.checkNavigable(d),!1,t),l.children().trigger("focus");break;default:return}},e.prototype.checkNavigable=function(i){var e,t;if(e=this.getNavigableIndexes(),t=0,i>e[e.length-1])i=e[e.length-1];else for(var o in e){if(i<e[o]){i=t;break}t=e[o]}return i},e.prototype.cleanUpEvents=function(){var e=this;e.options.dots&&null!==e.$dots&&(i("li",e.$dots).off("click.slick",e.changeSlide).off("mouseenter.slick",i.proxy(e.interrupt,e,!0)).off("mouseleave.slick",i.proxy(e.interrupt,e,!1)),!0===e.options.accessibility&&e.$dots.off("keydown.slick",e.keyHandler)),e.$slider.off("focus.slick blur.slick"),!0===e.options.arrows&&e.slideCount>e.options.slidesToShow&&(e.$prevArrow&&e.$prevArrow.off("click.slick",e.changeSlide),e.$nextArrow&&e.$nextArrow.off("click.slick",e.changeSlide),!0===e.options.accessibility&&(e.$prevArrow&&e.$prevArrow.off("keydown.slick",e.keyHandler),e.$nextArrow&&e.$nextArrow.off("keydown.slick",e.keyHandler))),e.$list.off("touchstart.slick mousedown.slick",e.swipeHandler),e.$list.off("touchmove.slick mousemove.slick",e.swipeHandler),e.$list.off("touchend.slick mouseup.slick",e.swipeHandler),e.$list.off("touchcancel.slick mouseleave.slick",e.swipeHandler),e.$list.off("click.slick",e.clickHandler),i(document).off(e.visibilityChange,e.visibility),e.cleanUpSlideEvents(),!0===e.options.accessibility&&e.$list.off("keydown.slick",e.keyHandler),!0===e.options.focusOnSelect&&i(e.$slideTrack).children().off("click.slick",e.selectHandler),i(window).off("orientationchange.slick.slick-"+e.instanceUid,e.orientationChange),i(window).off("resize.slick.slick-"+e.instanceUid,e.resize),i("[draggable!=true]",e.$slideTrack).off("dragstart",e.preventDefault),i(window).off("load.slick.slick-"+e.instanceUid,e.setPosition)},e.prototype.cleanUpSlideEvents=function(){var e=this;e.$list.off("mouseenter.slick",i.proxy(e.interrupt,e,!0)),e.$list.off("mouseleave.slick",i.proxy(e.interrupt,e,!1))},e.prototype.cleanUpRows=function(){var i,e=this;e.options.rows>1&&((i=e.$slides.children().children()).removeAttr("style"),e.$slider.empty().append(i))},e.prototype.clickHandler=function(i){!1===this.shouldClick&&(i.stopImmediatePropagation(),i.stopPropagation(),i.preventDefault())},e.prototype.destroy=function(e){var t=this;t.autoPlayClear(),t.touchObject={},t.cleanUpEvents(),i(".slick-cloned",t.$slider).detach(),t.$dots&&t.$dots.remove(),t.$prevArrow&&t.$prevArrow.length&&(t.$prevArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display",""),t.htmlExpr.test(t.options.prevArrow)&&t.$prevArrow.remove()),t.$nextArrow&&t.$nextArrow.length&&(t.$nextArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display",""),t.htmlExpr.test(t.options.nextArrow)&&t.$nextArrow.remove()),t.$slides&&(t.$slides.removeClass("slick-slide slick-active slick-center slick-visible slick-current").removeAttr("aria-hidden").removeAttr("data-slick-index").each(function(){i(this).attr("style",i(this).data("originalStyling"))}),t.$slideTrack.children(this.options.slide).detach(),t.$slideTrack.detach(),t.$list.detach(),t.$slider.append(t.$slides)),t.cleanUpRows(),t.$slider.removeClass("slick-slider"),t.$slider.removeClass("slick-initialized"),t.$slider.removeClass("slick-dotted"),t.unslicked=!0,e||t.$slider.trigger("destroy",[t])},e.prototype.disableTransition=function(i){var e=this,t={};t[e.transitionType]="",!1===e.options.fade?e.$slideTrack.css(t):e.$slides.eq(i).css(t)},e.prototype.fadeSlide=function(i,e){var t=this;!1===t.cssTransitions?(t.$slides.eq(i).css({zIndex:t.options.zIndex}),t.$slides.eq(i).animate({opacity:1},t.options.speed,t.options.easing,e)):(t.applyTransition(i),t.$slides.eq(i).css({opacity:1,zIndex:t.options.zIndex}),e&&setTimeout(function(){t.disableTransition(i),e.call()},t.options.speed))},e.prototype.fadeSlideOut=function(i){var e=this;!1===e.cssTransitions?e.$slides.eq(i).animate({opacity:0,zIndex:e.options.zIndex-2},e.options.speed,e.options.easing):(e.applyTransition(i),e.$slides.eq(i).css({opacity:0,zIndex:e.options.zIndex-2}))},e.prototype.filterSlides=e.prototype.slickFilter=function(i){var e=this;null!==i&&(e.$slidesCache=e.$slides,e.unload(),e.$slideTrack.children(this.options.slide).detach(),e.$slidesCache.filter(i).appendTo(e.$slideTrack),e.reinit())},e.prototype.focusHandler=function(){var e=this;e.$slider.off("focus.slick blur.slick").on("focus.slick blur.slick","*",function(t){t.stopImmediatePropagation();var o=i(this);setTimeout(function(){e.options.pauseOnFocus&&(e.focussed=o.is(":focus"),e.autoPlay())},0)})},e.prototype.getCurrent=e.prototype.slickCurrentSlide=function(){return this.currentSlide},e.prototype.getDotCount=function(){var i=this,e=0,t=0,o=0;if(!0===i.options.infinite)if(i.slideCount<=i.options.slidesToShow)++o;else for(;e<i.slideCount;)++o,e=t+i.options.slidesToScroll,t+=i.options.slidesToScroll<=i.options.slidesToShow?i.options.slidesToScroll:i.options.slidesToShow;else if(!0===i.options.centerMode)o=i.slideCount;else if(i.options.asNavFor)for(;e<i.slideCount;)++o,e=t+i.options.slidesToScroll,t+=i.options.slidesToScroll<=i.options.slidesToShow?i.options.slidesToScroll:i.options.slidesToShow;else o=1+Math.ceil((i.slideCount-i.options.slidesToShow)/i.options.slidesToScroll);return o-1},e.prototype.getLeft=function(i){var e,t,o,s,n=this,r=0;return n.slideOffset=0,t=n.$slides.first().outerHeight(!0),!0===n.options.infinite?(n.slideCount>n.options.slidesToShow&&(n.slideOffset=n.slideWidth*n.options.slidesToShow*-1,s=-1,!0===n.options.vertical&&!0===n.options.centerMode&&(2===n.options.slidesToShow?s=-1.5:1===n.options.slidesToShow&&(s=-2)),r=t*n.options.slidesToShow*s),n.slideCount%n.options.slidesToScroll!=0&&i+n.options.slidesToScroll>n.slideCount&&n.slideCount>n.options.slidesToShow&&(i>n.slideCount?(n.slideOffset=(n.options.slidesToShow-(i-n.slideCount))*n.slideWidth*-1,r=(n.options.slidesToShow-(i-n.slideCount))*t*-1):(n.slideOffset=n.slideCount%n.options.slidesToScroll*n.slideWidth*-1,r=n.slideCount%n.options.slidesToScroll*t*-1))):i+n.options.slidesToShow>n.slideCount&&(n.slideOffset=(i+n.options.slidesToShow-n.slideCount)*n.slideWidth,r=(i+n.options.slidesToShow-n.slideCount)*t),n.slideCount<=n.options.slidesToShow&&(n.slideOffset=0,r=0),!0===n.options.centerMode&&n.slideCount<=n.options.slidesToShow?n.slideOffset=n.slideWidth*Math.floor(n.options.slidesToShow)/2-n.slideWidth*n.slideCount/2:!0===n.options.centerMode&&!0===n.options.infinite?n.slideOffset+=n.slideWidth*Math.floor(n.options.slidesToShow/2)-n.slideWidth:!0===n.options.centerMode&&(n.slideOffset=0,n.slideOffset+=n.slideWidth*Math.floor(n.options.slidesToShow/2)),e=!1===n.options.vertical?i*n.slideWidth*-1+n.slideOffset:i*t*-1+r,!0===n.options.variableWidth&&(o=n.slideCount<=n.options.slidesToShow||!1===n.options.infinite?n.$slideTrack.children(".slick-slide").eq(i):n.$slideTrack.children(".slick-slide").eq(i+n.options.slidesToShow),e=!0===n.options.rtl?o[0]?-1*(n.$slideTrack.width()-o[0].offsetLeft-o.width()):0:o[0]?-1*o[0].offsetLeft:0,!0===n.options.centerMode&&(o=n.slideCount<=n.options.slidesToShow||!1===n.options.infinite?n.$slideTrack.children(".slick-slide").eq(i):n.$slideTrack.children(".slick-slide").eq(i+n.options.slidesToShow+1),e=!0===n.options.rtl?o[0]?-1*(n.$slideTrack.width()-o[0].offsetLeft-o.width()):0:o[0]?-1*o[0].offsetLeft:0,e+=(n.$list.width()-o.outerWidth())/2)),e},e.prototype.getOption=e.prototype.slickGetOption=function(i){return this.options[i]},e.prototype.getNavigableIndexes=function(){var i,e=this,t=0,o=0,s=[];for(!1===e.options.infinite?i=e.slideCount:(t=-1*e.options.slidesToScroll,o=-1*e.options.slidesToScroll,i=2*e.slideCount);t<i;)s.push(t),t=o+e.options.slidesToScroll,o+=e.options.slidesToScroll<=e.options.slidesToShow?e.options.slidesToScroll:e.options.slidesToShow;return s},e.prototype.getSlick=function(){return this},e.prototype.getSlideCount=function(){var e,t,o=this;return t=!0===o.options.centerMode?o.slideWidth*Math.floor(o.options.slidesToShow/2):0,!0===o.options.swipeToSlide?(o.$slideTrack.find(".slick-slide").each(function(s,n){if(n.offsetLeft-t+i(n).outerWidth()/2>-1*o.swipeLeft)return e=n,!1}),Math.abs(i(e).attr("data-slick-index")-o.currentSlide)||1):o.options.slidesToScroll},e.prototype.goTo=e.prototype.slickGoTo=function(i,e){this.changeSlide({data:{message:"index",index:parseInt(i)}},e)},e.prototype.init=function(e){var t=this;i(t.$slider).hasClass("slick-initialized")||(i(t.$slider).addClass("slick-initialized"),t.buildRows(),t.buildOut(),t.setProps(),t.startLoad(),t.loadSlider(),t.initializeEvents(),t.updateArrows(),t.updateDots(),t.checkResponsive(!0),t.focusHandler()),e&&t.$slider.trigger("init",[t]),!0===t.options.accessibility&&t.initADA(),t.options.autoplay&&(t.paused=!1,t.autoPlay())},e.prototype.initADA=function(){var e=this,t=Math.ceil(e.slideCount/e.options.slidesToShow),o=e.getNavigableIndexes().filter(function(i){return i>=0&&i<e.slideCount});e.$slides.add(e.$slideTrack.find(".slick-cloned")).attr({"aria-hidden":"true",tabindex:"-1"}).find("a, input, button, select").attr({tabindex:"-1"}),null!==e.$dots&&(e.$slides.not(e.$slideTrack.find(".slick-cloned")).each(function(t){var s=o.indexOf(t);i(this).attr({role:"tabpanel",id:"slick-slide"+e.instanceUid+t,tabindex:-1}),-1!==s&&i(this).attr({"aria-describedby":"slick-slide-control"+e.instanceUid+s})}),e.$dots.attr("role","tablist").find("li").each(function(s){var n=o[s];i(this).attr({role:"presentation"}),i(this).find("button").first().attr({role:"tab",id:"slick-slide-control"+e.instanceUid+s,"aria-controls":"slick-slide"+e.instanceUid+n,"aria-label":s+1+" of "+t,"aria-selected":null,tabindex:"-1"})}).eq(e.currentSlide).find("button").attr({"aria-selected":"true",tabindex:"0"}).end());for(var s=e.currentSlide,n=s+e.options.slidesToShow;s<n;s++)e.$slides.eq(s).attr("tabindex",0);e.activateADA()},e.prototype.initArrowEvents=function(){var i=this;!0===i.options.arrows&&i.slideCount>i.options.slidesToShow&&(i.$prevArrow.off("click.slick").on("click.slick",{message:"previous"},i.changeSlide),i.$nextArrow.off("click.slick").on("click.slick",{message:"next"},i.changeSlide),!0===i.options.accessibility&&(i.$prevArrow.on("keydown.slick",i.keyHandler),i.$nextArrow.on("keydown.slick",i.keyHandler)))},e.prototype.initDotEvents=function(){var e=this;!0===e.options.dots&&(i("li",e.$dots).on("click.slick",{message:"index"},e.changeSlide),!0===e.options.accessibility&&e.$dots.on("keydown.slick",e.keyHandler)),!0===e.options.dots&&!0===e.options.pauseOnDotsHover&&i("li",e.$dots).on("mouseenter.slick",i.proxy(e.interrupt,e,!0)).on("mouseleave.slick",i.proxy(e.interrupt,e,!1))},e.prototype.initSlideEvents=function(){var e=this;e.options.pauseOnHover&&(e.$list.on("mouseenter.slick",i.proxy(e.interrupt,e,!0)),e.$list.on("mouseleave.slick",i.proxy(e.interrupt,e,!1)))},e.prototype.initializeEvents=function(){var e=this;e.initArrowEvents(),e.initDotEvents(),e.initSlideEvents(),e.$list.on("touchstart.slick mousedown.slick",{action:"start"},e.swipeHandler),e.$list.on("touchmove.slick mousemove.slick",{action:"move"},e.swipeHandler),e.$list.on("touchend.slick mouseup.slick",{action:"end"},e.swipeHandler),e.$list.on("touchcancel.slick mouseleave.slick",{action:"end"},e.swipeHandler),e.$list.on("click.slick",e.clickHandler),i(document).on(e.visibilityChange,i.proxy(e.visibility,e)),!0===e.options.accessibility&&e.$list.on("keydown.slick",e.keyHandler),!0===e.options.focusOnSelect&&i(e.$slideTrack).children().on("click.slick",e.selectHandler),i(window).on("orientationchange.slick.slick-"+e.instanceUid,i.proxy(e.orientationChange,e)),i(window).on("resize.slick.slick-"+e.instanceUid,i.proxy(e.resize,e)),i("[draggable!=true]",e.$slideTrack).on("dragstart",e.preventDefault),i(window).on("load.slick.slick-"+e.instanceUid,e.setPosition),i(e.setPosition)},e.prototype.initUI=function(){var i=this;!0===i.options.arrows&&i.slideCount>i.options.slidesToShow&&(i.$prevArrow.show(),i.$nextArrow.show()),!0===i.options.dots&&i.slideCount>i.options.slidesToShow&&i.$dots.show()},e.prototype.keyHandler=function(i){var e=this;i.target.tagName.match("TEXTAREA|INPUT|SELECT")||(37===i.keyCode&&!0===e.options.accessibility?e.changeSlide({data:{message:!0===e.options.rtl?"next":"previous"}}):39===i.keyCode&&!0===e.options.accessibility&&e.changeSlide({data:{message:!0===e.options.rtl?"previous":"next"}}))},e.prototype.lazyLoad=function(){function e(e){i("img[data-lazy]",e).each(function(){var e=i(this),t=i(this).attr("data-lazy"),o=i(this).attr("data-srcset"),s=i(this).attr("data-sizes")||n.$slider.attr("data-sizes"),r=document.createElement("img");r.onload=function(){e.animate({opacity:0},100,function(){o&&(e.attr("srcset",o),s&&e.attr("sizes",s)),e.attr("src",t).animate({opacity:1},200,function(){e.removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading")}),n.$slider.trigger("lazyLoaded",[n,e,t])})},r.onerror=function(){e.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"),n.$slider.trigger("lazyLoadError",[n,e,t])},r.src=t})}var t,o,s,n=this;if(!0===n.options.centerMode?!0===n.options.infinite?s=(o=n.currentSlide+(n.options.slidesToShow/2+1))+n.options.slidesToShow+2:(o=Math.max(0,n.currentSlide-(n.options.slidesToShow/2+1)),s=n.options.slidesToShow/2+1+2+n.currentSlide):(o=n.options.infinite?n.options.slidesToShow+n.currentSlide:n.currentSlide,s=Math.ceil(o+n.options.slidesToShow),!0===n.options.fade&&(o>0&&o--,s<=n.slideCount&&s++)),t=n.$slider.find(".slick-slide").slice(o,s),"anticipated"===n.options.lazyLoad)for(var r=o-1,l=s,d=n.$slider.find(".slick-slide"),a=0;a<n.options.slidesToScroll;a++)r<0&&(r=n.slideCount-1),t=(t=t.add(d.eq(r))).add(d.eq(l)),r--,l++;e(t),n.slideCount<=n.options.slidesToShow?e(n.$slider.find(".slick-slide")):n.currentSlide>=n.slideCount-n.options.slidesToShow?e(n.$slider.find(".slick-cloned").slice(0,n.options.slidesToShow)):0===n.currentSlide&&e(n.$slider.find(".slick-cloned").slice(-1*n.options.slidesToShow))},e.prototype.loadSlider=function(){var i=this;i.setPosition(),i.$slideTrack.css({opacity:1}),i.$slider.removeClass("slick-loading"),i.initUI(),"progressive"===i.options.lazyLoad&&i.progressiveLazyLoad()},e.prototype.next=e.prototype.slickNext=function(){this.changeSlide({data:{message:"next"}})},e.prototype.orientationChange=function(){var i=this;i.checkResponsive(),i.setPosition()},e.prototype.pause=e.prototype.slickPause=function(){var i=this;i.autoPlayClear(),i.paused=!0},e.prototype.play=e.prototype.slickPlay=function(){var i=this;i.autoPlay(),i.options.autoplay=!0,i.paused=!1,i.focussed=!1,i.interrupted=!1},e.prototype.postSlide=function(e){var t=this;t.unslicked||(t.$slider.trigger("afterChange",[t,e]),t.animating=!1,t.slideCount>t.options.slidesToShow&&t.setPosition(),t.swipeLeft=null,t.options.autoplay&&t.autoPlay(),!0===t.options.accessibility&&(t.initADA(),t.options.focusOnChange&&i(t.$slides.get(t.currentSlide)).attr("tabindex",0).focus()))},e.prototype.prev=e.prototype.slickPrev=function(){this.changeSlide({data:{message:"previous"}})},e.prototype.preventDefault=function(i){i.preventDefault()},e.prototype.progressiveLazyLoad=function(e){e=e||1;var t,o,s,n,r,l=this,d=i("img[data-lazy]",l.$slider);d.length?(t=d.first(),o=t.attr("data-lazy"),s=t.attr("data-srcset"),n=t.attr("data-sizes")||l.$slider.attr("data-sizes"),(r=document.createElement("img")).onload=function(){s&&(t.attr("srcset",s),n&&t.attr("sizes",n)),t.attr("src",o).removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading"),!0===l.options.adaptiveHeight&&l.setPosition(),l.$slider.trigger("lazyLoaded",[l,t,o]),l.progressiveLazyLoad()},r.onerror=function(){e<3?setTimeout(function(){l.progressiveLazyLoad(e+1)},500):(t.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"),l.$slider.trigger("lazyLoadError",[l,t,o]),l.progressiveLazyLoad())},r.src=o):l.$slider.trigger("allImagesLoaded",[l])},e.prototype.refresh=function(e){var t,o,s=this;o=s.slideCount-s.options.slidesToShow,!s.options.infinite&&s.currentSlide>o&&(s.currentSlide=o),s.slideCount<=s.options.slidesToShow&&(s.currentSlide=0),t=s.currentSlide,s.destroy(!0),i.extend(s,s.initials,{currentSlide:t}),s.init(),e||s.changeSlide({data:{message:"index",index:t}},!1)},e.prototype.registerBreakpoints=function(){var e,t,o,s=this,n=s.options.responsive||null;if("array"===i.type(n)&&n.length){s.respondTo=s.options.respondTo||"window";for(e in n)if(o=s.breakpoints.length-1,n.hasOwnProperty(e)){for(t=n[e].breakpoint;o>=0;)s.breakpoints[o]&&s.breakpoints[o]===t&&s.breakpoints.splice(o,1),o--;s.breakpoints.push(t),s.breakpointSettings[t]=n[e].settings}s.breakpoints.sort(function(i,e){return s.options.mobileFirst?i-e:e-i})}},e.prototype.reinit=function(){var e=this;e.$slides=e.$slideTrack.children(e.options.slide).addClass("slick-slide"),e.slideCount=e.$slides.length,e.currentSlide>=e.slideCount&&0!==e.currentSlide&&(e.currentSlide=e.currentSlide-e.options.slidesToScroll),e.slideCount<=e.options.slidesToShow&&(e.currentSlide=0),e.registerBreakpoints(),e.setProps(),e.setupInfinite(),e.buildArrows(),e.updateArrows(),e.initArrowEvents(),e.buildDots(),e.updateDots(),e.initDotEvents(),e.cleanUpSlideEvents(),e.initSlideEvents(),e.checkResponsive(!1,!0),!0===e.options.focusOnSelect&&i(e.$slideTrack).children().on("click.slick",e.selectHandler),e.setSlideClasses("number"==typeof e.currentSlide?e.currentSlide:0),e.setPosition(),e.focusHandler(),e.paused=!e.options.autoplay,e.autoPlay(),e.$slider.trigger("reInit",[e])},e.prototype.resize=function(){var e=this;i(window).width()!==e.windowWidth&&(clearTimeout(e.windowDelay),e.windowDelay=window.setTimeout(function(){e.windowWidth=i(window).width(),e.checkResponsive(),e.unslicked||e.setPosition()},50))},e.prototype.removeSlide=e.prototype.slickRemove=function(i,e,t){var o=this;if(i="boolean"==typeof i?!0===(e=i)?0:o.slideCount-1:!0===e?--i:i,o.slideCount<1||i<0||i>o.slideCount-1)return!1;o.unload(),!0===t?o.$slideTrack.children().remove():o.$slideTrack.children(this.options.slide).eq(i).remove(),o.$slides=o.$slideTrack.children(this.options.slide),o.$slideTrack.children(this.options.slide).detach(),o.$slideTrack.append(o.$slides),o.$slidesCache=o.$slides,o.reinit()},e.prototype.setCSS=function(i){var e,t,o=this,s={};!0===o.options.rtl&&(i=-i),e="left"==o.positionProp?Math.ceil(i)+"px":"0px",t="top"==o.positionProp?Math.ceil(i)+"px":"0px",s[o.positionProp]=i,!1===o.transformsEnabled?o.$slideTrack.css(s):(s={},!1===o.cssTransitions?(s[o.animType]="translate("+e+", "+t+")",o.$slideTrack.css(s)):(s[o.animType]="translate3d("+e+", "+t+", 0px)",o.$slideTrack.css(s)))},e.prototype.setDimensions=function(){var i=this;!1===i.options.vertical?!0===i.options.centerMode&&i.$list.css({padding:"0px "+i.options.centerPadding}):(i.$list.height(i.$slides.first().outerHeight(!0)*i.options.slidesToShow),!0===i.options.centerMode&&i.$list.css({padding:i.options.centerPadding+" 0px"})),i.listWidth=i.$list.width(),i.listHeight=i.$list.height(),!1===i.options.vertical&&!1===i.options.variableWidth?(i.slideWidth=Math.ceil(i.listWidth/i.options.slidesToShow),i.$slideTrack.width(Math.ceil(i.slideWidth*i.$slideTrack.children(".slick-slide").length))):!0===i.options.variableWidth?i.$slideTrack.width(5e3*i.slideCount):(i.slideWidth=Math.ceil(i.listWidth),i.$slideTrack.height(Math.ceil(i.$slides.first().outerHeight(!0)*i.$slideTrack.children(".slick-slide").length)));var e=i.$slides.first().outerWidth(!0)-i.$slides.first().width();!1===i.options.variableWidth&&i.$slideTrack.children(".slick-slide").width(i.slideWidth-e)},e.prototype.setFade=function(){var e,t=this;t.$slides.each(function(o,s){e=t.slideWidth*o*-1,!0===t.options.rtl?i(s).css({position:"relative",right:e,top:0,zIndex:t.options.zIndex-2,opacity:0}):i(s).css({position:"relative",left:e,top:0,zIndex:t.options.zIndex-2,opacity:0})}),t.$slides.eq(t.currentSlide).css({zIndex:t.options.zIndex-1,opacity:1})},e.prototype.setHeight=function(){var i=this;if(1===i.options.slidesToShow&&!0===i.options.adaptiveHeight&&!1===i.options.vertical){var e=i.$slides.eq(i.currentSlide).outerHeight(!0);i.$list.css("height",e)}},e.prototype.setOption=e.prototype.slickSetOption=function(){var e,t,o,s,n,r=this,l=!1;if("object"===i.type(arguments[0])?(o=arguments[0],l=arguments[1],n="multiple"):"string"===i.type(arguments[0])&&(o=arguments[0],s=arguments[1],l=arguments[2],"responsive"===arguments[0]&&"array"===i.type(arguments[1])?n="responsive":void 0!==arguments[1]&&(n="single")),"single"===n)r.options[o]=s;else if("multiple"===n)i.each(o,function(i,e){r.options[i]=e});else if("responsive"===n)for(t in s)if("array"!==i.type(r.options.responsive))r.options.responsive=[s[t]];else{for(e=r.options.responsive.length-1;e>=0;)r.options.responsive[e].breakpoint===s[t].breakpoint&&r.options.responsive.splice(e,1),e--;r.options.responsive.push(s[t])}l&&(r.unload(),r.reinit())},e.prototype.setPosition=function(){var i=this;i.setDimensions(),i.setHeight(),!1===i.options.fade?i.setCSS(i.getLeft(i.currentSlide)):i.setFade(),i.$slider.trigger("setPosition",[i])},e.prototype.setProps=function(){var i=this,e=document.body.style;i.positionProp=!0===i.options.vertical?"top":"left","top"===i.positionProp?i.$slider.addClass("slick-vertical"):i.$slider.removeClass("slick-vertical"),void 0===e.WebkitTransition&&void 0===e.MozTransition&&void 0===e.msTransition||!0===i.options.useCSS&&(i.cssTransitions=!0),i.options.fade&&("number"==typeof i.options.zIndex?i.options.zIndex<3&&(i.options.zIndex=3):i.options.zIndex=i.defaults.zIndex),void 0!==e.OTransform&&(i.animType="OTransform",i.transformType="-o-transform",i.transitionType="OTransition",void 0===e.perspectiveProperty&&void 0===e.webkitPerspective&&(i.animType=!1)),void 0!==e.MozTransform&&(i.animType="MozTransform",i.transformType="-moz-transform",i.transitionType="MozTransition",void 0===e.perspectiveProperty&&void 0===e.MozPerspective&&(i.animType=!1)),void 0!==e.webkitTransform&&(i.animType="webkitTransform",i.transformType="-webkit-transform",i.transitionType="webkitTransition",void 0===e.perspectiveProperty&&void 0===e.webkitPerspective&&(i.animType=!1)),void 0!==e.msTransform&&(i.animType="msTransform",i.transformType="-ms-transform",i.transitionType="msTransition",void 0===e.msTransform&&(i.animType=!1)),void 0!==e.transform&&!1!==i.animType&&(i.animType="transform",i.transformType="transform",i.transitionType="transition"),i.transformsEnabled=i.options.useTransform&&null!==i.animType&&!1!==i.animType},e.prototype.setSlideClasses=function(i){var e,t,o,s,n=this;if(t=n.$slider.find(".slick-slide").removeClass("slick-active slick-center slick-current").attr("aria-hidden","true"),n.$slides.eq(i).addClass("slick-current"),!0===n.options.centerMode){var r=n.options.slidesToShow%2==0?1:0;e=Math.floor(n.options.slidesToShow/2),!0===n.options.infinite&&(i>=e&&i<=n.slideCount-1-e?n.$slides.slice(i-e+r,i+e+1).addClass("slick-active").attr("aria-hidden","false"):(o=n.options.slidesToShow+i,t.slice(o-e+1+r,o+e+2).addClass("slick-active").attr("aria-hidden","false")),0===i?t.eq(t.length-1-n.options.slidesToShow).addClass("slick-center"):i===n.slideCount-1&&t.eq(n.options.slidesToShow).addClass("slick-center")),n.$slides.eq(i).addClass("slick-center")}else i>=0&&i<=n.slideCount-n.options.slidesToShow?n.$slides.slice(i,i+n.options.slidesToShow).addClass("slick-active").attr("aria-hidden","false"):t.length<=n.options.slidesToShow?t.addClass("slick-active").attr("aria-hidden","false"):(s=n.slideCount%n.options.slidesToShow,o=!0===n.options.infinite?n.options.slidesToShow+i:i,n.options.slidesToShow==n.options.slidesToScroll&&n.slideCount-i<n.options.slidesToShow?t.slice(o-(n.options.slidesToShow-s),o+s).addClass("slick-active").attr("aria-hidden","false"):t.slice(o,o+n.options.slidesToShow).addClass("slick-active").attr("aria-hidden","false"));"ondemand"!==n.options.lazyLoad&&"anticipated"!==n.options.lazyLoad||n.lazyLoad()},e.prototype.setupInfinite=function(){var e,t,o,s=this;if(!0===s.options.fade&&(s.options.centerMode=!1),!0===s.options.infinite&&!1===s.options.fade&&(t=null,s.slideCount>s.options.slidesToShow)){for(o=!0===s.options.centerMode?s.options.slidesToShow+1:s.options.slidesToShow,e=s.slideCount;e>s.slideCount-o;e-=1)t=e-1,i(s.$slides[t]).clone(!0).attr("id","").attr("data-slick-index",t-s.slideCount).prependTo(s.$slideTrack).addClass("slick-cloned");for(e=0;e<o+s.slideCount;e+=1)t=e,i(s.$slides[t]).clone(!0).attr("id","").attr("data-slick-index",t+s.slideCount).appendTo(s.$slideTrack).addClass("slick-cloned");s.$slideTrack.find(".slick-cloned").find("[id]").each(function(){i(this).attr("id","")})}},e.prototype.interrupt=function(i){var e=this;i||e.autoPlay(),e.interrupted=i},e.prototype.selectHandler=function(e){var t=this,o=i(e.target).is(".slick-slide")?i(e.target):i(e.target).parents(".slick-slide"),s=parseInt(o.attr("data-slick-index"));s||(s=0),t.slideCount<=t.options.slidesToShow?t.slideHandler(s,!1,!0):t.slideHandler(s)},e.prototype.slideHandler=function(i,e,t){var o,s,n,r,l,d=null,a=this;if(e=e||!1,!(!0===a.animating&&!0===a.options.waitForAnimate||!0===a.options.fade&&a.currentSlide===i))if(!1===e&&a.asNavFor(i),o=i,d=a.getLeft(o),r=a.getLeft(a.currentSlide),a.currentLeft=null===a.swipeLeft?r:a.swipeLeft,!1===a.options.infinite&&!1===a.options.centerMode&&(i<0||i>a.getDotCount()*a.options.slidesToScroll))!1===a.options.fade&&(o=a.currentSlide,!0!==t?a.animateSlide(r,function(){a.postSlide(o)}):a.postSlide(o));else if(!1===a.options.infinite&&!0===a.options.centerMode&&(i<0||i>a.slideCount-a.options.slidesToScroll))!1===a.options.fade&&(o=a.currentSlide,!0!==t?a.animateSlide(r,function(){a.postSlide(o)}):a.postSlide(o));else{if(a.options.autoplay&&clearInterval(a.autoPlayTimer),s=o<0?a.slideCount%a.options.slidesToScroll!=0?a.slideCount-a.slideCount%a.options.slidesToScroll:a.slideCount+o:o>=a.slideCount?a.slideCount%a.options.slidesToScroll!=0?0:o-a.slideCount:o,a.animating=!0,a.$slider.trigger("beforeChange",[a,a.currentSlide,s]),n=a.currentSlide,a.currentSlide=s,a.setSlideClasses(a.currentSlide),a.options.asNavFor&&(l=(l=a.getNavTarget()).slick("getSlick")).slideCount<=l.options.slidesToShow&&l.setSlideClasses(a.currentSlide),a.updateDots(),a.updateArrows(),!0===a.options.fade)return!0!==t?(a.fadeSlideOut(n),a.fadeSlide(s,function(){a.postSlide(s)})):a.postSlide(s),void a.animateHeight();!0!==t?a.animateSlide(d,function(){a.postSlide(s)}):a.postSlide(s)}},e.prototype.startLoad=function(){var i=this;!0===i.options.arrows&&i.slideCount>i.options.slidesToShow&&(i.$prevArrow.hide(),i.$nextArrow.hide()),!0===i.options.dots&&i.slideCount>i.options.slidesToShow&&i.$dots.hide(),i.$slider.addClass("slick-loading")},e.prototype.swipeDirection=function(){var i,e,t,o,s=this;return i=s.touchObject.startX-s.touchObject.curX,e=s.touchObject.startY-s.touchObject.curY,t=Math.atan2(e,i),(o=Math.round(180*t/Math.PI))<0&&(o=360-Math.abs(o)),o<=45&&o>=0?!1===s.options.rtl?"left":"right":o<=360&&o>=315?!1===s.options.rtl?"left":"right":o>=135&&o<=225?!1===s.options.rtl?"right":"left":!0===s.options.verticalSwiping?o>=35&&o<=135?"down":"up":"vertical"},e.prototype.swipeEnd=function(i){var e,t,o=this;if(o.dragging=!1,o.swiping=!1,o.scrolling)return o.scrolling=!1,!1;if(o.interrupted=!1,o.shouldClick=!(o.touchObject.swipeLength>10),void 0===o.touchObject.curX)return!1;if(!0===o.touchObject.edgeHit&&o.$slider.trigger("edge",[o,o.swipeDirection()]),o.touchObject.swipeLength>=o.touchObject.minSwipe){switch(t=o.swipeDirection()){case"left":case"down":e=o.options.swipeToSlide?o.checkNavigable(o.currentSlide+o.getSlideCount()):o.currentSlide+o.getSlideCount(),o.currentDirection=0;break;case"right":case"up":e=o.options.swipeToSlide?o.checkNavigable(o.currentSlide-o.getSlideCount()):o.currentSlide-o.getSlideCount(),o.currentDirection=1}"vertical"!=t&&(o.slideHandler(e),o.touchObject={},o.$slider.trigger("swipe",[o,t]))}else o.touchObject.startX!==o.touchObject.curX&&(o.slideHandler(o.currentSlide),o.touchObject={})},e.prototype.swipeHandler=function(i){var e=this;if(!(!1===e.options.swipe||"ontouchend"in document&&!1===e.options.swipe||!1===e.options.draggable&&-1!==i.type.indexOf("mouse")))switch(e.touchObject.fingerCount=i.originalEvent&&void 0!==i.originalEvent.touches?i.originalEvent.touches.length:1,e.touchObject.minSwipe=e.listWidth/e.options.touchThreshold,!0===e.options.verticalSwiping&&(e.touchObject.minSwipe=e.listHeight/e.options.touchThreshold),i.data.action){case"start":e.swipeStart(i);break;case"move":e.swipeMove(i);break;case"end":e.swipeEnd(i)}},e.prototype.swipeMove=function(i){var e,t,o,s,n,r,l=this;return n=void 0!==i.originalEvent?i.originalEvent.touches:null,!(!l.dragging||l.scrolling||n&&1!==n.length)&&(e=l.getLeft(l.currentSlide),l.touchObject.curX=void 0!==n?n[0].pageX:i.clientX,l.touchObject.curY=void 0!==n?n[0].pageY:i.clientY,l.touchObject.swipeLength=Math.round(Math.sqrt(Math.pow(l.touchObject.curX-l.touchObject.startX,2))),r=Math.round(Math.sqrt(Math.pow(l.touchObject.curY-l.touchObject.startY,2))),!l.options.verticalSwiping&&!l.swiping&&r>4?(l.scrolling=!0,!1):(!0===l.options.verticalSwiping&&(l.touchObject.swipeLength=r),t=l.swipeDirection(),void 0!==i.originalEvent&&l.touchObject.swipeLength>4&&(l.swiping=!0,i.preventDefault()),s=(!1===l.options.rtl?1:-1)*(l.touchObject.curX>l.touchObject.startX?1:-1),!0===l.options.verticalSwiping&&(s=l.touchObject.curY>l.touchObject.startY?1:-1),o=l.touchObject.swipeLength,l.touchObject.edgeHit=!1,!1===l.options.infinite&&(0===l.currentSlide&&"right"===t||l.currentSlide>=l.getDotCount()&&"left"===t)&&(o=l.touchObject.swipeLength*l.options.edgeFriction,l.touchObject.edgeHit=!0),!1===l.options.vertical?l.swipeLeft=e+o*s:l.swipeLeft=e+o*(l.$list.height()/l.listWidth)*s,!0===l.options.verticalSwiping&&(l.swipeLeft=e+o*s),!0!==l.options.fade&&!1!==l.options.touchMove&&(!0===l.animating?(l.swipeLeft=null,!1):void l.setCSS(l.swipeLeft))))},e.prototype.swipeStart=function(i){var e,t=this;if(t.interrupted=!0,1!==t.touchObject.fingerCount||t.slideCount<=t.options.slidesToShow)return t.touchObject={},!1;void 0!==i.originalEvent&&void 0!==i.originalEvent.touches&&(e=i.originalEvent.touches[0]),t.touchObject.startX=t.touchObject.curX=void 0!==e?e.pageX:i.clientX,t.touchObject.startY=t.touchObject.curY=void 0!==e?e.pageY:i.clientY,t.dragging=!0},e.prototype.unfilterSlides=e.prototype.slickUnfilter=function(){var i=this;null!==i.$slidesCache&&(i.unload(),i.$slideTrack.children(this.options.slide).detach(),i.$slidesCache.appendTo(i.$slideTrack),i.reinit())},e.prototype.unload=function(){var e=this;i(".slick-cloned",e.$slider).remove(),e.$dots&&e.$dots.remove(),e.$prevArrow&&e.htmlExpr.test(e.options.prevArrow)&&e.$prevArrow.remove(),e.$nextArrow&&e.htmlExpr.test(e.options.nextArrow)&&e.$nextArrow.remove(),e.$slides.removeClass("slick-slide slick-active slick-visible slick-current").attr("aria-hidden","true").css("width","")},e.prototype.unslick=function(i){var e=this;e.$slider.trigger("unslick",[e,i]),e.destroy()},e.prototype.updateArrows=function(){var i=this;Math.floor(i.options.slidesToShow/2),!0===i.options.arrows&&i.slideCount>i.options.slidesToShow&&!i.options.infinite&&(i.$prevArrow.removeClass("slick-disabled").attr("aria-disabled","false"),i.$nextArrow.removeClass("slick-disabled").attr("aria-disabled","false"),0===i.currentSlide?(i.$prevArrow.addClass("slick-disabled").attr("aria-disabled","true"),i.$nextArrow.removeClass("slick-disabled").attr("aria-disabled","false")):i.currentSlide>=i.slideCount-i.options.slidesToShow&&!1===i.options.centerMode?(i.$nextArrow.addClass("slick-disabled").attr("aria-disabled","true"),i.$prevArrow.removeClass("slick-disabled").attr("aria-disabled","false")):i.currentSlide>=i.slideCount-1&&!0===i.options.centerMode&&(i.$nextArrow.addClass("slick-disabled").attr("aria-disabled","true"),i.$prevArrow.removeClass("slick-disabled").attr("aria-disabled","false")))},e.prototype.updateDots=function(){var i=this;null!==i.$dots&&(i.$dots.find("li").removeClass("slick-active").end(),i.$dots.find("li").eq(Math.floor(i.currentSlide/i.options.slidesToScroll)).addClass("slick-active"))},e.prototype.visibility=function(){var i=this;i.options.autoplay&&(document[i.hidden]?i.interrupted=!0:i.interrupted=!1)},i.fn.slick=function(){var i,t,o=this,s=arguments[0],n=Array.prototype.slice.call(arguments,1),r=o.length;for(i=0;i<r;i++)if("object"==typeof s||void 0===s?o[i].slick=new e(o[i],s):t=o[i].slick[s].apply(o[i].slick,n),void 0!==t)return t;return o}});


/*!
  Non-Sucking Autogrow 1.1.6
  license: MIT
  author: Roman Pushkin
  https://github.com/ro31337/jquery.ns-autogrow
*/
(function(){var e;!function(t,l){return t.fn.autogrow=function(i){if(null==i&&(i={}),null==i.horizontal&&(i.horizontal=!0),null==i.vertical&&(i.vertical=!0),null==i.debugx&&(i.debugx=-1e4),null==i.debugy&&(i.debugy=-1e4),null==i.debugcolor&&(i.debugcolor="yellow"),null==i.flickering&&(i.flickering=!0),null==i.postGrowCallback&&(i.postGrowCallback=function(){}),null==i.verticalScrollbarWidth&&(i.verticalScrollbarWidth=e()),i.horizontal!==!1||i.vertical!==!1)return this.filter("textarea").each(function(){var e,n,r,o,a,c,d;if(e=t(this),!e.data("autogrow-enabled"))return e.data("autogrow-enabled"),a=e.height(),c=e.width(),o=1*e.css("lineHeight")||0,e.hasVerticalScrollBar=function(){return e[0].clientHeight<e[0].scrollHeight},n=t('<div class="autogrow-shadow"></div>').css({position:"absolute",display:"inline-block","background-color":i.debugcolor,top:i.debugy,left:i.debugx,"max-width":e.css("max-width"),padding:e.css("padding"),fontSize:e.css("fontSize"),fontFamily:e.css("fontFamily"),fontWeight:e.css("fontWeight"),lineHeight:e.css("lineHeight"),resize:"none","word-wrap":"break-word"}).appendTo(document.body),i.horizontal===!1?n.css({width:e.width()}):(r=e.css("font-size"),n.css("padding-right","+="+r),n.normalPaddingRight=n.css("padding-right")),d=function(t){return function(l){var r,d,s;return d=t.value.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n /g,"<br/>&nbsp;").replace(/"/g,"&quot;").replace(/'/g,"&#39;").replace(/\n$/,"<br/>&nbsp;").replace(/\n/g,"<br/>").replace(/ {2,}/g,function(e){return Array(e.length-1).join("&nbsp;")+" "}),/(\n|\r)/.test(t.value)&&(d+="<br />",i.flickering===!1&&(d+="<br />")),n.html(d),i.vertical===!0&&(r=Math.max(n.height()+o,a),e.height(r)),i.horizontal===!0&&(n.css("padding-right",n.normalPaddingRight),i.vertical===!1&&e.hasVerticalScrollBar()&&n.css("padding-right","+="+i.verticalScrollbarWidth+"px"),s=Math.max(n.outerWidth(),c),e.width(s)),i.postGrowCallback(e)}}(this),e.change(d).keyup(d).keydown(d),t(l).resize(d),d()})}}(window.jQuery,window),e=function(){var e,t,l,i;return e=document.createElement("p"),e.style.width="100%",e.style.height="200px",t=document.createElement("div"),t.style.position="absolute",t.style.top="0px",t.style.left="0px",t.style.visibility="hidden",t.style.width="200px",t.style.height="150px",t.style.overflow="hidden",t.appendChild(e),document.body.appendChild(t),l=e.offsetWidth,t.style.overflow="scroll",i=e.offsetWidth,l===i&&(i=t.clientWidth),document.body.removeChild(t),l-i}}).call(this);