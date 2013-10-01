

var eleFront = angular.module('eleFront',['eleAPP']);


/*
 * -------------------------------------------------------------------------------------------------
 * REGISTRATION FORM CONTROLLER
 * -------------------------------------------------------------------------------------------------
 */
eleFront.controller('eleForm', function($scope,eleFrontFactory,eleSettingsFactory) {

    //terms and condition checkbox
    $scope.tos = false;
    $scope.westernUnionLink = '';
    $scope.tosLink = '';

    /*
     * use for tabbing
     * --------------------------------
     */
    $scope.tabsInit = function(){
        angular.element("#regFormTabbing").tabs({ hide: { effect: "fade", duration: 300 } });
    };
    $scope.tabsInit();


    /*
     * use to call a service to get the
     * ele general settings in database. this is
     * triggered upon first load of settings
     * page
     * --------------------------------
     */
    $scope.getEleSettings = function(){

        eleSettingsFactory.getEleSettings().success(function(data){
            $scope.eleSettings      = data;
            $scope.westernUnionLink = data.western_union;
            $scope.tosLink          = data.tos_link;
        });

    };
    $scope.getEleSettings();


    /*
     * use to call a service to get the
     * paypal settings in database. this is
     * triggered upon first load of registration
     * form on front end
     * --------------------------------
     */
    $scope.getPaypalSettings = function(){

        eleSettingsFactory.getPaypalSettings().success(function(data){
            $scope.paypalData = data;
        });

    };
    $scope.getPaypalSettings();


    /*
     * use to call a service to get the
     * athletic options in database. this is
     * triggered upon first load of registration
     * form on front end
     * --------------------------------
     */
    $scope.getAthleticOptions = function(){

        eleSettingsFactory.getAthleticOptions().success(function(data){
            $scope.athleticLevelOptions = data;
        });

    };
    $scope.getAthleticOptions();


    /*
     * use to call a service to get the
     * team category options in database. this is
     * triggered upon first load of registration
     * form on front end
     * --------------------------------
     */
    $scope.getCategoryOptions = function(){

        eleSettingsFactory.getCategoryOptions().success(function(data){
            $scope.categoryOptions = data;
        });

    };
    $scope.getCategoryOptions();


    /*
     * triggers within a button on registration
     * form on front end.
     * --------------------------------
     */
    $scope.submitRegForm = function(){

        if( !$scope.formIsValid() ){
            return false;
        }

        angular.element('#regFormAjax').show();

        $scope.form             = angular.element('#regForm');
        $scope.formAction       = '';
        $scope.serializedData   = $scope.form.serializeArray();
        $scope.paymentMethod    = angular.element("input:radio[name=payment_method]:checked").val();

        if( ($scope.paymentMethod == 'pp') || ($scope.paymentMethod == 'cc') ){

            if( angular.element('#mode').val() == 'sandbox' ){
                $scope.formAction = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            } else {
                $scope.formAction = 'https://www.paypal.com/cgi-bin/webscr';
            }

        } else {

            $scope.formAction = $scope.westernUnionLink;

        }

        $scope.form.attr('action',$scope.formAction);
        $scope.form.attr('method','post');

        eleFrontFactory.addEntry($scope.serializedData).success(function(data){

            if( data.captchaError ){
                angular.element('#regForm').prepend('<div class="alert alert-danger regFormWarningMsg" style="text-align: center; margin-top:10px;">Invalid Captcha!</div>');
                angular.element('#regFormAjax').hide();
                return false;
            }


            angular.element('#regFormAjax').hide();
            angular.element('#custom').val(data.entry_id);
            $scope.form.submit();

        });

    };

    /*Nothing special about me, I'm just a helper*/
    $scope.formIsValid = function(){

        var err = 0;
        angular.element('#regForm .required').each(function(){
            if( $(this).val() == "" ){
                err++;
            }
        });

        if( err > 0 ){
            angular.element('#regForm').prepend('<div class="alert alert-danger regFormWarningMsg" style="text-align: center; margin-top:10px;">Form fields cannot be emptied!</div>');
            return false;
        }

        if( !$scope.tos ){
            angular.element('#regForm').prepend('<div class="alert alert-danger regFormWarningMsg" style="text-align: center; margin-top:10px;">Please check the agreement!</div>');
            return false;
        }

        return true;

    };

    /*
     * Events
     * --------------------------------
     */
    $scope.eventsInit = function(){
        //remove warning messages when interacts with form
        angular.element('#regForm .required').on('focus', function(){
            angular.element('.regFormWarningMsg').remove();
        });
        angular.element('#email_1').on('blur', function(){
            angular.element('#recieptEmail').val( angular.element(this).val() );
        });
        //watch for payment method and show hidden field exclusive only for western union payers
        angular.element("input:radio[name=payment_method]").on('change', function(){
            if( angular.element("input:radio[name=payment_method]:checked").val() == 'wu' ){
                angular.element('#wuField').fadeIn();
            } else {
                angular.element('#wuField').fadeOut();
            }

        });
        //watch for package options
        angular.element("input:radio[name=package_mode]").on('change', function(){
            if( angular.element("input:radio[name=package_mode]:checked").val() == 'regular_package' ){
                angular.element("#amount").val($scope.paypalData.amount);
                console.log('regular_package');
            } else {
                angular.element("#amount").val($scope.eleSettings.package_1);
                console.log('travellers_package');
            }
        });
    };
    $scope.eventsInit();

});





/*
 * -------------------------------------------------------------------------------------------------
 * LISTING CONTROLLER
 * -------------------------------------------------------------------------------------------------
 */
eleFront.controller('eleListing', function($scope, eleFactory, eleFrontFactory,eleSettingsFactory) {

    $scope.registrants  = [];
    $scope.totalEntries = 'loading..';

    //pagination
    $scope.selectedPage = 1;
    $scope.offset       = 0;
    $scope.pages        = [];


    /*
     * use to call a service to get the
     * ele general settings in database.
     * --------------------------------
     */
    $scope.getEleSettings = function(){

        eleSettingsFactory.getEleSettings().success(function(data){

            $scope.limit = data.paging_display;

            //we now get all entries using the limit we got from
            //our settings in database
            $scope.getAllEntries($scope.limit,$scope.offset);

        });

    };
    $scope.getEleSettings();


    /*
     * HELPERS
     * -----------------------------------------------
     */
    function helpersInit(){

        //use in pagination iteration on pages (select input)
        $scope.getPages = function(number){
            return new Array(number);
        }

    }
    helpersInit();



    /*
     * EVENT in pagination
     * -------------------------------------------------
     */
    $scope.doPaging = function(){
        var offset  = ($scope.selectedPage - 1)*$scope.limit;
        $scope.getAllEntries($scope.limit,offset);
    };


    /*
     * GET ALL ENTRIES IN DATABASE
     * --------------------------------------------------
     */
    $scope.getAllEntries = function(limit,offset){
        angular.element('#ele-listing-table').fadeOut();
        eleFactory.getAllEntries(limit,offset).success(function(data){
            angular.element('#ele-listing-table').fadeIn();
            $scope.registrants = data;
            console.log($scope.registrants);
        });

    };
    $scope.getAllEntries($scope.limit,$scope.offset);


    /*
     * GET TOTAL ENTRIES IN DATABASE USE IN Browse
     * Pages in front End
     * ----------------------------------------------------
     */
    $scope.getTotalEntries = function(){

        eleFactory.getTotalEntries().success(function(data){
            if(data.success){
                $scope.totalEntries = data.total;
                $scope.totalPages = Math.ceil($scope.totalEntries/$scope.limit);

                for(var i=0; i<$scope.totalPages; i++){
                    $scope.pages.push(i);
                }
            }
        });

    };
    $scope.getTotalEntries();

});





/*
 * -------------------------------------------------------------------------------------------------
 * FACTORY
 * -------------------------------------------------------------------------------------------------
 */
eleFront.factory('eleFrontFactory', function($http){

    //add entry
    this.addEntry = function(serializedData){

        var data = {
            action: 'ele_add_entry',
            entry_data: serializedData
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    return this;
});