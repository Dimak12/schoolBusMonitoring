$(document).ready(function(){
  // Add user
  $(document).on('click', '.user_add', function(){
    //user Info
    var student_num = $('#student_num').val();
    var card_uid = $('#card_uid').val();
    var name = $('#name').val();
    var surname = $('#surname').val();
    var number = $('#number').val();
    
    //Additional Info
    var bus_id = $('#bus_id').val();
    var gender = $(".gender:checked").val();
    var bus_id = $('#dev_sel option:selected').val();
    
    $.ajax({
      url: 'manage_users_conf.php',
      type: 'POST',
      data: {
        'Add': 1,
        'student_num': student_num,
        'card_uid': card_uid,
        'name': name,
        'number': number,
        'surname': surname,
        'bus_id': bus_id,
        'gender': gender,
      },
      success: function(response){

        if (response == 1) {
          $('#student_num').val('');
          $('#card_uid').val('');
          $('#name').val('');
          $('#number').val('');
          $('#surname').val('');

          $('#dev_sel').val('0');
          $('.alert_user').fadeIn(500);
          $('.alert_user').html('<p class="alert alert-success">A new User has been successfully added</p>');
        }
        else{
          $('.alert_user').fadeIn(500);
          $('.alert_user').html('<p class="alert alert-danger">'+ response + '</p>');
        }

        setTimeout(function () {
            $('.alert').fadeOut(500);
        }, 5000);
        
        $.ajax({
          url: "manage_users_up.php"
          }).done(function(data) {
          $('#manage_users').html(data);
        });
      }
    });
  });

  // Update user
  $(document).on('click', '.user_upd', function(){
    //user Info
    var student_num = $('#student_num').val();
    var card_uid = $('#card_uid').val();
    var name = $('#name').val();
    var number = $('#number').val();
    var surname = $('#surname').val();
    //Additional Info
    var bus_id = $('#bus_id').val();
    var gender = $(".gender:checked").val();
    var bus_id = $('#dev_sel option:selected').val();

    $.ajax({
      url: 'manage_users_conf.php',
      type: 'POST',
      data: {
        'Update': 1,
        'student_num': student_num,
        'card_uid': card_uid,
        'name': name,
        'number': number,
        'surname': surname,
        'bus_id': bus_id,
        'gender': gender,
      },
      success: function(response){

        if (response == 1) {
          $('#student_num').val('');
          $('#card_uid').val('');
          $('#name').val('');
          $('#number').val('');
          $('#surname').val('');

          $('#dev_sel').val('0');
          $('.alert_user').fadeIn(500);
          $('.alert_user').html('<p class="alert alert-success">The selected User has been updated!</p>');
        }
        else{
          $('.alert_user').fadeIn(500);
          $('.alert_user').html('<p class="alert alert-danger">'+ response + '</p>');
        }
        
        setTimeout(function () {
            $('.alert').fadeOut(500);
        }, 5000);
        
        $.ajax({
          url: "manage_users_up.php"
          }).done(function(data) {
          $('#manage_users').html(data);
        });
      }
    });   
  });
  // delete user
  $(document).on('click', '.user_rmo', function(){

    var student_num = $('#student_num').val();

    bootbox.confirm("Do you really want to delete this Student?", function(result) {
      if(result){
        $.ajax({
          url: 'manage_users_conf.php',
          type: 'POST',
          data: {
            'delete': 1,
            'student_num': student_num,
          },
          success: function(response){

            if (response == 1) {
              $('#student_num').val('');
              $('#card_uid').val('');
              $('#name').val('');
              $('#number').val('');
              $('#surname').val('');

              $('#dev_sel').val('0');
              $('.alert_user').fadeIn(500);
              $('.alert_user').html('<p class="alert alert-success">The selected User has been deleted!</p>');
            }
            else{
              $('.alert_user').fadeIn(500);
              $('.alert_user').html('<p class="alert alert-danger">'+ response + '</p>');
            }
            
            setTimeout(function () {
                $('.alert').fadeOut(500);
            }, 5000);
            
            $.ajax({
              url: "manage_users_up.php"
              }).done(function(data) {
              $('#manage_users').html(data);
            });
          }
        });
      }
    });
  });
  

  // select user
  $(document).on('click', '.select_btn', function(){
    var el = this;
    var card_uid = $(this).attr("id");
    $.ajax({
      url: 'manage_users_conf.php',
      type: 'GET',
      data: {
      'select': 1,
      'card_uid': card_uid,
      },
      success: function(response){

        $(el).closest('tr').css('background','#70c276');

        $('.alert_user').fadeIn(500);
        $('.alert_user').html('<p class="alert alert-success">The card has been selected!</p>');
        
        setTimeout(function () {
            $('.alert').fadeOut(500);
        }, 5000);

        $.ajax({
          url: "manage_users_up.php"
          }).done(function(data) {
          $('#manage_users').html(data);
        });

        console.log(response);

        var card_uid = {
          card_uid : []
        };
        var user_name = {
          User_name : []
        };
        var user_on = {
          User_on : []
        };
        var user_surname = {
          User_surname : []
        };
        var user_dev = {
          User_dev : []
        };
        var user_gender = {
          User_gender : []
        };

        var len = response.length;

        for (var i = 0; i < len; i++) {
            card_uid.card_uid.push(response[i].id);
            user_name.User_name.push(response[i].username);
            user_on.User_on.push(response[i].serialnumber);
            user_surname.User_surname.push(response[i].surname);
            user_dev.User_dev.push(response[i].device_uid);
            user_gender.User_gender.push(response[i].gender);
        }
        if (user_dev.User_dev == "All") {
          user_dev.User_dev = 0;
        }
        $('#card_uid').val(card_uid.card_uid);
        $('#name').val(user_name.User_name);
        $('#number').val(user_on.User_on);
        $('#surname').val(user_surname.User_surname);
        $('#dev_sel').val(user_dev.User_dev);

        if (user_gender.User_gender == 'Female'){
            $('.form-style-5').find(':radio[name=gender][value="Female"]').prop('checked', true);
        }
        else{
            $('.form-style-5').find(':radio[name=gender][value="Male"]').prop('checked', true);
        }

      },
      error : function(data) {
        console.log(data);
      }
    });
  });
});

