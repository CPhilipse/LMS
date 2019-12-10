<style>
    .timer {
        font-size: 20px;
        color: #000;
        text-align: center;
        margin-top: 50px;
    }
    .timer .day, .timer .hour, .timer .min, .timer .sec {
        font-size: 30px;
        display: inline-block;
        font-weight: 500;
        text-align: center;
        margin: 0 5px;
    }
    .timer .day .format, .timer .hour .format, .timer .min .format, .timer .sec .format {
        font-weight: 300;
        font-size: 14px;
        opacity: 0.8;
        width: 60px;
    }
    .timer .number {
        background: lightblue;
        padding: 0 5px;
        border-radius: 5px;
        display: inline-block;
        width: 60px;
        text-align: center;
    }
    .timer .message {
        font-size: 14px;
        font-weight: 400;
        margin-top: 5px;
    }
    .timer .status-tag {
        width: 270px;
        margin: 10px auto;
        padding: 8px 0;
        font-weight: 500;
        color: #000;
        text-align: center;
        border-radius: 15px;
    }
    .timer .status-tag.upcoming {
        background-color: lightblue;
    }
    .timer .status-tag.running {
        background-color: lightblue;
    }
    .timer .status-tag.expired {
        background-color: lightblue;
    }

    /*  Slides  */
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
        top: 60%;
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

    .round {
        text-align: center;
        margin-top: 20px;
        padding-bottom: 25px;
    }

    /* On smaller screens, decrease text size */
    @media only screen and (max-width: 300px) {
        .prev, .next,.text {font-size: 11px}
    }
</style>

<template>
<!--    <template v-for="round in rounds">-->
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

        <a class="prev" style="color: #2196F3;" @click="plusSlides(-1)">&#10094;</a>
        <a class="next" style="right: 30px;color: #2196F3;" @click="plusSlides(1)">&#10095;</a>
        <!-- <h2 class="round"><b>Ronde {{ round }}</b></h2>-->


    </div>

</template>

<script>
    export default {
        // mounted() {
        //     console.log('Component mounted.')
        // }

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
                league: [
                    [" Team 1", "Team 2", " Team 3", "Team 4", "Team 5", "Team 6", "Team 7", "Team 8", "Team 9", "Team 10", "Team 11", "Team 12"],
                    [" Team 1 +", "Team 2", " Team 3", "Team 4", "Team 5", "Team 6", "Team 7", "Team 8", "Team 9", "Team 10", "Team 11", "Team 12"],
                    [" Team 1 -", "Team 2", " Team 3", "Team 4", "Team 5", "Team 6", "Team 7", "Team 8", "Team 9", "Team 10", "Team 11", "Team 12"],
                    [" Team 1 ?", "Team 2", " Team 3", "Team 4", "Team 5", "Team 6", "Team 7", "Team 8", "Team 9", "Team 10", "Team 11", "Team 12"],
                ],
                outcome: [
                    ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                    ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                    ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                    ["0", "2", "1", "3", "2", "4", "1", "3", "2", "1", "4", "3"],
                ],
                rounds: [],
                comp: [],
                slideIndex: 1,
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
            },
            showSlides: (n) => {
                // var i;
                // var slides = document.getElementsByClassName("mySlides");
                // if (n > slides.length) {this.slideIndex = 1}
                // if (n < 1) {this.slideIndex = slides.length}
                // for (i = 0; i < slides.length; i++) {
                //     slides[i].style.display = "none";
                // }
                //
                // slides[this.slideIndex-1].style.display = "block";
            },

            plusSlides: (n) => {
                this.showSlides(this.slideIndex += n);
                //console.log(n);
            },

            currentSlide: (n) => {
                this.showSlides(this.slideIndex = n);
            },


            loop: () => {
                for (var round = 0; round < this.league.length; round++) {
                    this.round = round;
                }
            },

        }
    }
</script>
