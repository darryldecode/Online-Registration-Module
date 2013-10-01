<?php

function ele_online_registration_settings(){

    ?>
    <div class="row ele-admin-wrapper ele-settings-wrapper" id="ele-settings" ng-app="eleAPP" ng-controller="settingsController">

        <div class="col-lg-12">

            <div class="row">

                <div class="col-lg-6">
                    <h3 class="ele-title"><b>ONLINE REGISTRATION Settings</b></h3>
                </div>

                <div class="col-lg-3">
                    <table class="table-app-info">
                        <tr>
                            <th colspan="2"><span class="label label-success"><strong>Usage Info (shortcodes):</strong></span></th>
                        </tr>
                        <tr>
                            <td>Registration Form:</td>
                            <td>[deploy_registration_form]</td>
                        </tr>
                        <tr>
                            <td>Participants Listing:</td>
                            <td>[deploy_listing]</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-3">
                    <table class="table-app-info">
                        <tr>
                            <th><span class="label label-success"><strong>Author Info:</strong></span></th>
                        </tr>
                        <tr>
                            <td>Darryl Coder</td>
                        </tr>
                        <tr>
                            <td>Engrdarrylfernandez@gmail.com</td>
                        </tr>
                        <tr>
                            <td>Version: {{eleSettings.version}}</td>
                        </tr>
                    </table>
                </div>

            </div>

            <hr>

            <div id="settings" class="clearfix settings-holder">

                <form id="paypalSettingsForm">
                    <table class="wp-list-table widefat">

                        <tr>
                            <th colspan="2">General Settings</th>
                        </tr>
                        <tr>
                            <td><strong>Safe Mode:</strong></td>
                            <td>
                                <select id="safeMode" ng-model="eleSettings.ele_safe_mode">
                                    <option value="enabled">Enable</option>
                                    <option value="disabled">Disable</option>
                                </select>
                                <span style="color:red;">(Note: Disabling this is <strong>not recommended</strong>! when disabled, all data will be deleted when deactivated.)</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Terms & Condition Link:</strong></td>
                            <td><input type="text" ng-model="eleSettings.tos_link"></td>
                        </tr>
                        <tr>
                            <td><strong>Western Union Link:</strong></td>
                            <td>
                                <input type="text" ng-model="eleSettings.western_union">
                                <span>(Note: This link will be use when a user chooses Western Union Payment Method. Obviously you have to manually set the page and put neccessary contents there.)</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Paging Display:</strong></td>
                            <td>
                                <input type="text" ng-model="eleSettings.paging_display">
                                <span>(Note: Number of items you want to be displayed in admin listing and front end listing.)</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Travellers Package Price:</strong></td>
                            <td>
                                <input type="text" ng-model="eleSettings.package_1">
                                <span>(Note: the currency of this is dependent to paypal settings currency below.)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span ng-click="updateEleSettings()" class="btn btn-primary btn-xs">Update</span>
                                <span id="updateEleSettingsAjaxLoader" class="adminAjaxLoader"></span>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">PayPal Settings</th>
                        </tr>
                        <tr>
                            <td><strong>Mode:</strong></td>
                            <td>
                                <select id="paypalMode" ng-model="paypalData.mode">
                                    <option value="sandbox">Sandbox</option>
                                    <option value="live">Live</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Business Email:</strong></td>
                            <td><input type="text" ng-model="paypalData.business"></td>
                        </tr>
                        <tr>
                            <td><strong>Notify URL:</strong></td>
                            <td><span>{{paypalData.notify_url}}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Return URL:</strong></td>
                            <td><span>{{paypalData.return}}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Cancel Return URL:</strong></td>
                            <td><span>{{paypalData.cancel_return}}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Item Name:</strong></td>
                            <td><input type="text" ng-model="paypalData.item_name"></td>
                        </tr>
                        <tr>
                            <td><strong>Currency:</strong></td>
                            <td>
                                <select id="paypalCurrency" ng-model="paypalData.currency">
                                    <option value="USD">USD</option>
                                    <option value="PHP">PHP</option>
                                </select>
                        </tr>
                        <tr>
                            <td><strong>Item Number:</strong></td>
                            <td><input type="text" ng-model="paypalData.item_number"></td>
                        </tr>
                        <tr>
                            <td><strong>Amount:</strong></td>
                            <td><input type="text" ng-model="paypalData.amount"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span ng-click="updatePaypalSettings()" class="btn btn-primary btn-xs">Update</span>
                                <span id="updatePaypalSettingsAjaxLoader" class="adminAjaxLoader"></span>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Athletic Levels Options</th>
                        </tr>
                        <tr>
                            <td><strong>Options:</strong></td>
                            <td>
                                <textarea cols="40" row="40" ng-model="athleticLevelOptions"></textarea>
                                <span>(Note: Add new athletic options separated with comma. No spaces!)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span ng-click="updateAthleticSettings()" class="btn btn-primary btn-xs">Update</span>
                                <span id="updateAthleticSettingsAjaxLoader" class="adminAjaxLoader"></span>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2">Team Category Options</th>
                        </tr>
                        <tr>
                            <td><strong>Options:</strong></td>
                            <td>
                                <textarea cols="40" row="40" ng-model="categoryOptions"></textarea>
                                <span>(Note: Add new Team Category options separated with comma. No spaces!)</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span ng-click="updateCategoryOptions()" class="btn btn-primary btn-xs">Update</span>
                                <span id="updateCategoryOptionsAjaxLoader" class="adminAjaxLoader"></span>
                            </td>
                        </tr>
                    </table>

                </form>

            </div>

        </div>

    </div>
    <?php

}