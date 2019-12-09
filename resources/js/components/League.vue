<style>
    .mySlides {display: none}

    /* Slideshow container */
    .slideshow-container {
        max-width: 1000px;
        position: relative;
        margin: auto;
    }

    /* Next & previous buttons */
    .prev, .next {
        cursor: pointer;
        position: absolute;
    @if(session('rightLink') == true)
    top: 38%!important;
    @endif
    top: 31%;
        width: auto;
        padding: 16px;
        margin-top: -22px;
        color: white;
        font-weight: bold;
        font-size: 18px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover, .next:hover {
        background-color: rgba(0,0,0,0.8);
    }

    /* Caption text */
    .text {
        color: #f2f2f2;
        font-size: 15px;
        padding: 8px 12px;
        position: absolute;
        bottom: 8px;
        width: 100%;
        text-align: center;
    }

    /* Number text (1/3 etc) */
    .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
    }

    /* The dots/bullets/indicators */
    .dot {
        cursor: pointer;
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
    }

    .active, .dot:hover {
        background-color: #717171;
    }

    /* On smaller screens, decrease text size */
    @media only screen and (max-width: 300px) {
        .prev, .next,.text {font-size: 11px}
    }
</style>

<template>
    <div>
        <div v-show ="statusType !== 'expired'">
            <div class="day">
                <span class="number">{{ days }}</span>
                <div class="format">{{ wordString.day }}</div>
            </div>
            <div class="hour">
                <span class="number">{{ hours }}</span>
                <div class="format">{{ wordString.hours }}</div>
            </div>
            <div class="min">
                <span class="number">{{ minutes }}</span>
                <div class="format">{{ wordString.minutes }}</div>
            </div>
            <div class="sec">
                <span class="number">{{ seconds }}</span>
                <div class="format">{{ wordString.seconds }}</div>
            </div>
        </div>
        <!--        <div class="message">{{ message }}</div>-->
        <div class="status-tag" :class="statusType">{{ statusText }}</div>
    </div>
</template>

<script>
    export default {
        // mounted() {
        //     console.log('Component mounted.')
        // }

        outcome: [
            ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
            ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
            ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
            ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
        ],
        league: [
            [" Team 1", " Team 2 <hr>", " Team 3", " Team 4 <hr>", " Team 5", " Team 6 <hr>", " Team 7", " Team 8 <hr>", " Team 9", " Team 10 <hr>", " Team 11", " Team 12 <hr>"],
            [" Team 1 -", " Team 2 <hr>", " Team 3", " Team 4 <hr>", " Team 5", " Team 6 <hr>", " Team 7", " Team 8 <hr>", " Team 9", " Team 10 <hr>", " Team 11", " Team 12 <hr>"],
            [" Team 1 +", " Team 2 <hr>", " Team 3", " Team 4 <hr>", " Team 5", " Team 6 <hr>", " Team 7", " Team 8 <hr>", " Team 9", " Team 10 <hr>", " Team 11", " Team 12 <hr>"],
            [" Team 1 ?", " Team 2 <hr>", " Team 3", " Team 4 <hr>", " Team 5", " Team 6 <hr>", " Team 7", " Team 8 <hr>", " Team 9", " Team 10 <hr>", " Team 11", " Team 12 <hr>"],
        ],

        loop: () => {
            for (var i = 0; i < this.league.length; i++) {

            }
        },

        props: ['starttime','endtime','trans'] ,
        data: function(){
            return{
                timer:"",
                wordString: {},
                start: "",
                end: "",
                interval: "",
                days:"",
                minutes:"",
                hours:"",
                seconds:"",
                message:"",
                statusType:"",
                statusText: "",
            };
        },
        created: function () {
            this.wordString = JSON.parse(this.trans);
        },
        mounted(){
            this.start = new Date(this.starttime).getTime();
            this.end = new Date(this.endtime).getTime();
            // Update the count down every 1 second
            this.timerCount(this.start,this.end);
            this.interval = setInterval(() => {
                this.timerCount(this.start,this.end);
            }, 1000);
        },
        methods: {
            timerCount: function(start,end){
                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = start - now;
                var passTime =  end - now;

                if(distance < 0 && passTime < 0){
                    this.message = this.wordString.expired;
                    this.statusType = "expired";
                    this.statusText = this.wordString.status.expired;
                    clearInterval(this.interval);
                    return;

                }else if(distance < 0 && passTime > 0){
                    this.calcTime(passTime);
                    this.message = this.wordString.running;
                    this.statusType = "running";
                    this.statusText = this.wordString.status.running;

                } else if( distance > 0 && passTime > 0 ){
                    this.calcTime(distance);
                    this.message = this.wordString.upcoming;
                    this.statusType = "upcoming";
                    this.statusText = this.wordString.status.upcoming;
                }
            },
            calcTime: function(dist){
                // Time calculations for days, hours, minutes and seconds
                this.days = Math.floor(dist / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((dist % (1000 * 60)) / 1000);
            }

        }
    }
</script>
