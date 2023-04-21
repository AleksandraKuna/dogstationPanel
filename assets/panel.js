const $ = require('jquery');

export const panel = $("document").ready(function () {

    console.log("ready");
    $('.workTime').click(function () {
        if($('.workTimeUser').hasClass('start')){
            var changeType = 'start';
        }else if($('.workTimeUser').hasClass('stop')){
            var changeType = 'stop';
        };

        $.ajax("/panel_workTimeChange", {
            type: "POST",
                        data: { 
                            changeType: changeType
                        },
                        success: function(data) {
                            console.log("udalo sie")
                            console.log(data)
                            console.log('test')
                            if(data.changeType == 'start'){
                                workTimeStarted(data.time);
                                // var timer = startTimer();
                            }
                            if(data.changeType == 'stop'){
                                workTimeStoped(data.time)
                                // stopTimer(timer);
                            }
                        },
                        error: function() {
                            console.log("błąd");
                                // show alert or something
                        }
                    });
        return false; // this stops normal button behaviour from executing;
    });

    function workTimeStarted(date){
        $('.workTimeUser').removeClass('bg-secondary').addClass('bg-success');
        $('.workTimeUser button i').removeClass('fa-play').addClass('fa-stop');
        $('.workTimeUser button span').html("ZAKOŃCZ PRACĘ <i class='fa fa-stop ms-2' aria-hidden='true'></i>");
        var time = new Date(date.date).toLocaleTimeString();
        $('.workTimeUser .date').append(", "+ time);
        $('.workTimeUser').removeClass('start').addClass('stop');
    }
    function workTimeStoped(date){
        $('.workTimeUser').removeClass('bg-success').addClass('bg-secondary');
        $('.workTimeUser button i').removeClass('fa-stop').addClass('fa-play');
        $('.workTimeUser button span').html("ZACZNIJ PRACĘ <i class='fa fa-play ms-2' aria-hidden='true'></i>");
        var time = new Date(date.date).toLocaleDateString('pl-PL', {day: 'numeric', month: 'short', year: 'numeric'});
        $('.workTimeUser .date').html(time);
        $('.workTimeUser').removeClass('stop').addClass('start');
    }

    // TODO: finish it later
    // timer settings

    // var sec = 0;
    // function pad ( val ) { return val > 9 ? val : "0" + val; }

    // function startTimer(){
        
    //     var timer = setInterval( function(){
    //             $("#seconds").html(pad(++ssec%60));
    //             $("#minutes").html(pad(parseInt(sec/60,10)));
    //             $("#hours").html(pad(parseInt(sec/60/60,10)));
    //         }, 1000);
    //     return timer;
    // };
    //     function stopTimer(timer){
    //         console.log('stopTimer');
    //         clearInterval ( timer );
    //     }
    



});
