var currenTime = (new Date()).getTime(),
	startTime = (new Date(2016,7,1)).getTime(),
	endTime = (new Date(2016,9,7)).getTime(),
	timeObj = {
		'start': startTime, // 2016/7/1
        'end': endTime,	// 2016/9/7
        'now': currenTime 
	},
    interval,

    timerHome  = Backbone.View.extend({
        el : "#timer",
        initialize : function(){
            clearTimeout(interval);
            this.initTimeDiffernce(timeObj);
        },

        /*
         *  @ param : obj : global timeObj , include start,end,now time
         *  @ funct initialTimeDiffernce
         *  calculate time diff between current and end time
         *  time include - days , hours , minutes , seconds
        */
        initTimeDiffernce : function(obj){
            var endMinusNowDiff = obj.end - obj.now,
                timer = {
                    total: Math.floor((obj.end - obj.start) / 86400),
                    days: Math.floor(endMinusNowDiff / 86400000),
                    hours: Math.floor((endMinusNowDiff % 86400000) / 3600000),
                    minutes: Math.floor(((endMinusNowDiff % 86400000) % 3600000) / 60000),
                    seconds: Math.floor((((endMinusNowDiff % 86400000) % 3600000) % 60000)/1000)
                };     
            this.startCountDown(timer);
        },

        /*
         *  @ param : timer object including time diff in days , hours , minutes , seconds
         *  @ funct startCountDown
         *  calculate countdown by setting time interval
         *  get value of days , hours , minutes , seconds after every second
        */
        startCountDown : function(timer) {
            var self = this;
            self.setValue("days",timer.days); 
            self.setValue("hours",timer.hours);
            self.setValue("minutes",timer.minutes);
            self.setValue("seconds",timer.seconds);

            interval = setInterval( function() {                        
                if (timer.seconds < 1 ) {
                    if (60 - timer.minutes == 0 && 24 - timer.hours == 0 && timer.days == 0) {
                        clearInterval(interval);
                        if (callbackFunction !== undefined) {
                            callbackFunction.call(this); // brings the scope to the callback
                        }
                        return;
                    }
                    timer.seconds = 60;
                    if (timer.minutes < 1) {
                        timer.minutes = 60;
                        self.setValue("minutes",timer.minutes);
                        if (timer.hours < 1) {
                            timer.hours = 23;
                            if (timer.days > 0 ) {
                                timer.days--;
                                self.setValue("days",timer.days);
                            }else{
                                timer.days = 0;
                            }
                        } else {                        
                            timer.hours--;
                        }                    
                        self.setValue("hours",timer.hours)
                    } else {
                        timer.minutes--;
                    }

                    self.setValue("minutes",timer.minutes);
                } else {            
                    timer.seconds--;
                }

                self.setValue("seconds",timer.seconds);
            }, 1000);
        },

        /*
         *  @ param : element : which element value needs to be set (days , hours , minutes , seconds)
         *  @ param : value : value of corresponding element
         *  @ funct setValue
         *  set html values of elements
        */
        setValue : function(element , value){
            $("#"+element).text(value);
        }
    }),

    initTimerHome;
