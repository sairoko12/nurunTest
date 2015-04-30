window.fbAsyncInit = function () {
    FB.init({
        appId: '785952734829382', // App ID
        //channelUrl: 'http://hayageek.com/examples/oauth/facebook/oauth-javascript/channel.html', // Channel File
        status: true, // check login status
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true  // parse XFBML
    });
};

// Load the SDK asynchronously
(function (d) {
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));

$(document).ready(function () {
    $("#registros").tablesorter( {dateFormat: 'pt'});
    
    $(".tools").click(function (e) {
        e.preventDefault();
        
        var content = $(".content-tools");
        var button = $(this);

        content.slideToggle("slow", function(){
        	var contentHeight = parseInt(content.height());
                
        	if (contentHeight < 74) {
        		button.html('<span class="glyphicon glyphicon-plus"></span>');
        	} else if (contentHeight === 74) {
        		button.html('<span class="glyphicon glyphicon-minus"> </span>');
        	}
        });
    });
    
    $("#login-fb").click(function(e){
        FB.login(function (response) {
            if (response.authResponse) {
                FB.api('/me', function (response) {
                    $.ajax({
                        url : "../source/session.php",
                        type : "POST",
                        dataType : "json",
                        data : {
                            data : response
                        },
                        success : function(r) {
                            if (r.success) {
                                location.href = "../index.php";
                            } else {
                                alert("No fue posible idetificarte.");
                                console.log(r.msg);
                            }
                        },
                        error : function(err) {
                            console.log(err.responseText);
                        }
                    });
                });
            } else {
                alert("No ha sido posible procesar tu peticion.");
            }
        }, {scope: 'email,user_photos,user_videos'});
    });
    
    $("#add_number").submit(function(e){
        e.preventDefault();
        
        $(this).ajaxSubmit({
            dataType : "json",
            success : function(r){
                if (r.success) {
                    var tpl = $("#tpl-add").html();
                    var render = Mustache.render(tpl, { record : r.data });
                    $("#user_records").html(r.user_records);
                    $("#total").html(r.total);
                    $("#registros > tbody").append(render);
                } else {
                    alert(r.msg);
                }
            },
            error : function(err) {
                console.log(err.responseText)
            }
        });
        
        return false;
    });
});