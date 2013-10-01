// JavaScript Document

jQuery(document).ready(function($){

    console.log('im working..');

    var regForm = {

        init: function(){

            this.tabs();

        },

        tabs: function(){
            $( "#regFormTabbing" ).tabs({ hide: { effect: "fade", duration: 300 } });
        }

    };

    //initalize registration page scripts
    regForm.init();

});