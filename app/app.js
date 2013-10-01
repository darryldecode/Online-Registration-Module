// JavaScript Document

var eleAPP = angular.module('eleAPP',[]);

/*
 * -------------------------------------------------------------------------------------------------
 * APP MAIN CONTROLLER
 * -------------------------------------------------------------------------------------------------
 */
eleAPP.controller('main', function($scope, eleFactory, eleSettingsFactory) {

    $scope.version      = '5';
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
     * Refresh listing
     * -----------------------------------------------
     */
    $scope.refreshList = function(){
        $scope.getEleSettings();
        $scope.getTotalEntries();
    };



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
        angular.element('.tbl-data-holder').fadeOut();
        eleFactory.getAllEntries(limit,offset).success(function(data){
            angular.element('.tbl-data-holder').fadeIn();
            $scope.registrants = data;
        });

    };



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


    /*
     * Bind to ng-click in front End, calls eleFactory service
     * to set the user paid status
     * ----------------------------------------------------------
     */
    $scope.setPaid = function(entry_id){

        angular.element('.btn-set-paid-'+entry_id).attr('disabled','disabled');
        angular.element('.btn-set-paid-'+entry_id).html('setting..');

        eleFactory.setAsPaid(entry_id).success(function(data){
            if(data.success){
                angular.element('.btn-set-paid-'+entry_id).html('Paid');
            }
        });

    }

});


/*
 * -------------------------------------------------------------------------------------------------
 * APP SETTINGS CONTROLLER
 * -------------------------------------------------------------------------------------------------
 */
eleAPP.controller('settingsController', function($scope, eleSettingsFactory) {

    $scope.version = '5';
    $scope.paypalData = {};

    /*
     * use to call a service to get the
     * paypal settings in database. this is
     * triggered upon first load of settings
     * page
     * --------------------------------
     */
    $scope.getPaypalSettings = function(){

        eleSettingsFactory.getPaypalSettings().success(function(data){
            $scope.paypalData = data;
            angular.element('#settings').fadeIn('1500');
        });

    };
    $scope.getPaypalSettings();


    /*
     * use to call a service to get the
     * athletic options in database. this is
     * triggered upon first load of settings
     * page
     * --------------------------------
     */
    $scope.getAthleticOptions = function(){

        eleSettingsFactory.getAthleticOptions().success(function(data){
            $scope.athleticLevelOptions = data.join(',');
        });

    };
    $scope.getAthleticOptions();


    /*
     * use to call a service to get the
     * team category options in database. this is
     * triggered upon first load of settings
     * page
     * --------------------------------
     */
    $scope.getCategoryOptions = function(){

        eleSettingsFactory.getCategoryOptions().success(function(data){
            $scope.categoryOptions = data.join(',');
        });

    };
    $scope.getCategoryOptions();

    /*
     * use to call a service to get the
     * ele general settings in database. this is
     * triggered upon first load of settings
     * page
     * --------------------------------
     */
    $scope.getEleSettings = function(){

        eleSettingsFactory.getEleSettings().success(function(data){
            $scope.eleSettings = data;
        });

    };
    $scope.getEleSettings();


    /*
     * initialize all event handling
     * --------------------------------
     */
    $scope.eventsInit = function(){

        //watching the change event on safe mode
        angular.element('#safeMode').on('change', function(){
            $scope.eleSettings.ele_safe_mode = angular.element('#safeMode').val();
        });

        //watching the change event on paypal mode to set new value
        angular.element('#paypalMode').on('change', function(){
            $scope.paypalData.mode = angular.element('#paypalMode').val();
        });

        //watching the change event on paypal currency to set new value
        angular.element('#paypalCurrency').on('change', function(){
            $scope.paypalData.currency = angular.element('#paypalCurrency').val();
        });

    };
    $scope.eventsInit();


    /*
     * Calls eleSettingsFactory service to update ELE
     * settings in database
     * --------------------------------
     */
    $scope.updateEleSettings = function(){

        angular.element('#updateEleSettingsAjaxLoader').html('Updating..');
        angular.element('#updateEleSettingsAjaxLoader').show();

        eleSettingsFactory.updateEleSettings($scope.eleSettings).success(function(data){

            if( data.success ){
                angular.element('#updateEleSettingsAjaxLoader').html('Updated Successfully..');
                angular.element('#updateEleSettingsAjaxLoader').fadeOut(1500);
            } else {
                angular.element('#updateEleSettingsAjaxLoader').html('Update Failed!');
            }

        });

    };


    /*
     * Calls eleSettingsFactory service to update paypal
     * settings in database
     * --------------------------------
     */
    $scope.updatePaypalSettings = function(){

        angular.element('#updatePaypalSettingsAjaxLoader').html('Updating..');
        angular.element('#updatePaypalSettingsAjaxLoader').show();

        eleSettingsFactory.updatePaypalSettings($scope.paypalData).success(function(data){

            if( data.success ){
                angular.element('#updatePaypalSettingsAjaxLoader').html('Updated Successfully..');
                angular.element('#updatePaypalSettingsAjaxLoader').fadeOut(1500);
            } else {
                angular.element('#updatePaypalSettingsAjaxLoader').html('Update Failed!');
            }

        });

    };


    /*
     * Calls eleSettingsFactory service to update
     * athletic level options in database
     * --------------------------------
     */
    $scope.updateAthleticSettings = function(){

        var updatedAthleticSettings = $scope.athleticLevelOptions.split(',');

        angular.element('#updateAthleticSettingsAjaxLoader').html('Updating..');
        angular.element('#updateAthleticSettingsAjaxLoader').show();

        eleSettingsFactory.updateAthleticOptions(updatedAthleticSettings).success(function(data){

            if( data.success ){
                angular.element('#updateAthleticSettingsAjaxLoader').html('Updated Successfully..');
                angular.element('#updateAthleticSettingsAjaxLoader').fadeOut(1500);
            } else {
                angular.element('#updateAthleticSettingsAjaxLoader').html('Update Failed!');
            }

        });

    };


    /*
     * Calls eleSettingsFactory service to update
     * team category options in database
     * --------------------------------
     */
    $scope.updateCategoryOptions = function(){

        var updateCategoryOptions = $scope.categoryOptions.split(',');

        angular.element('#updateCategoryOptionsAjaxLoader').html('Updating..');
        angular.element('#updateCategoryOptionsAjaxLoader').show();

        eleSettingsFactory.updateCategoryOptions(updateCategoryOptions).success(function(data){

            if( data.success ){
                angular.element('#updateCategoryOptionsAjaxLoader').html('Updated Successfully..');
                angular.element('#updateCategoryOptionsAjaxLoader').fadeOut(1500);
            } else {
                angular.element('#updateCategoryOptionsAjaxLoader').html('Update Failed!');
            }

        });

    };


});


/*
 * -------------------------------------------------------------------------------------------------
 * APP MAIN FACTORY
 * -------------------------------------------------------------------------------------------------
 */
eleAPP.factory('eleFactory', function($http){

    this.getAllEntries = function(limit, offset){

        var data = {
            action: 'ele_get_entries',
            limit: limit,
            offset: offset
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    this.getTotalEntries = function(){

        var data = {
            action: 'ele_get_total_entries'
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    this.setAsPaid = function(entry_id){

        var data = {
            action: 'ele_set_paid',
            entry_id: entry_id
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;
    };

    this.addEntry = function(){

        var data = {
            action: 'ele_add_entry'
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

/*
 * -------------------------------------------------------------------------------------------------
 * APP MAIN FACTORY
 * -------------------------------------------------------------------------------------------------
 */
eleAPP.factory('eleSettingsFactory', function($http){

    this.getEleSettings = function(){

        var data = {
            action: 'ele_get_ele_settings'
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    this.getPaypalSettings = function(){

        var data = {
            action: 'ele_get_paypal_settings'
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    this.getAthleticOptions = function(){

        var data = {
            action: 'ele_get_athletic_options'
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    this.getCategoryOptions = function(){

        var data = {
            action: 'ele_get_team_category_options'
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    this.updateEleSettings = function(data){

        var data = {
            action: 'ele_update_ele_settings',
            data: data
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    this.updatePaypalSettings = function(data){

        var data = {
            action: 'ele_update_paypal_settings',
            data: data
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    this.updateAthleticOptions = function(data){

        var data = {
            action: 'ele_update_athletic_options',
            data: data
        };

        var httpObj =  $http({

            url: ajaxURL,
            method: 'POST',
            data: data,
            headers: {'Content-Type': 'application/json'}

        });

        return httpObj;

    };

    this.updateCategoryOptions = function(data){

        var data = {
            action: 'ele_update_category_options',
            data: data
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