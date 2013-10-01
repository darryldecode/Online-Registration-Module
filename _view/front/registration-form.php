<?php

function ele_front_view_registration_form(){

    $settingsDB = get_option('paypal_settings');

    $settingsDB        = unserialize( $settingsDB );

    $settings = array();
    $settings['mode']           = $settingsDB['mode'];
    $settings['business']       = $settingsDB['business'];
    $settings['notify_url']     = $settingsDB['notify_url'];
    $settings['item_name']      = $settingsDB['item_name'];
    $settings['amount']         = $settingsDB['amount'];
    $settings['currency']       = $settingsDB['currency'];
    $settings['invoice']        = $settingsDB['invoice'].time().rand(0,1000);
    $settings['return']         = $settingsDB['return'];
    $settings['cancel_return']  = $settingsDB['cancel_return'];

    ?>
    <!--put jquery here, because we can't use wordpress jquery here, conflict issue-->
    <script type="text/javascript" src="<?php echo ELE_URI_JS.'jquery.min.js'; ?>"></script>
    <div id="eleForm" ng-app="eleFront" ng-controller="eleForm" xmlns="http://www.w3.org/1999/html">

        <div id="regFormTabbing">

        <ul>
            <li><a href="#tabs-1">Participant 1</a></li>
            <li><a href="#tabs-2">Participant 2</a></li>
        </ul>

        <form name="regForm" id="regForm">

        <!--participant 1 tab-->
        <div id="tabs-1">

            <h4>Participant 1 Personal Information</h4>

            <table>
                <tr>
                    <td>First Name:</td>
                    <td><input type="text" name="firstName_1" id="firstName_1" class="required"></td>
                </tr>
                <tr>
                    <td>Middle Name:</td>
                    <td><input type="text" name="middleName_1" id="middleName_1" class="required"></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><input type="text" name="lastName_1" id="lastName_1" class="required"></td>
                </tr>
                <tr>
                    <td>Date of Birth:</td>
                    <td>
                        <select name="dateOfBirth_year_1" id="dateOfBirth_year_1">

                            <option selected="selected">Year</option>

                            <?php
                            for($i=1900; $i<2013; $i++){
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>

                        </select>
                        <select name="dateOfBirth_month_1" id="dateOfBirth_month_1">

                            <option selected="selected">Month</option>

                            <?php
                            for($i=1; $i<13; $i++){
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>

                        </select>
                        <select name="dateOfBirth_day_1" id="dateOfBirth_day_1">

                            <option selected="selected">Date</option>

                            <?php
                            for($i=1; $i<32; $i++){
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td>
                        <select name="gender_1" id="gender_1">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Physical Mailing Address:</td>
                    <td><input type="text" name="physicalMail_1" id="physicalMail_1" class="required"></td>
                </tr>
                <tr>
                    <td>Daytime Phone No:</td>
                    <td><input type="tel" name="phone_1" id="phone_1" class="required"></td>
                </tr>
                <tr>
                    <td>Email Address:</td>
                    <td><input type="email" name="email_1" id="email_1" class="required"></td>
                </tr>
                <tr>
                    <td>Citizenship:</td>
                    <td><input type="text" name="citizenship_1" id="citizenship_1" class="required"></td>
                </tr>
                <tr>
                    <td>How would you describe your level of endurance athleticism?</td>
                    <td>
                        <select name="level_1" id="level_1">
                            <option ng-repeat="levelOption in athleticLevelOptions track by $index" value="{{levelOption}}">{{levelOption}}</option>
                        </select>
                    </td>
                </tr>
            </table>

        </div>
        <!--/participant 1 tab-->

        <!--participant 2 tab-->
        <div id="tabs-2">

            <h4>Participant 2 Personal Information</h4>

            <table>
                <tr>
                    <td>First Name:</td>
                    <td><input type="text" name="firstName_2" id="firstName_2" class="required"></td>
                </tr>
                <tr>
                    <td>Middle Name:</td>
                    <td><input type="text" name="middleName_2" id="middleName_2" class="required"></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><input type="text" name="lastName_2" id="lastName_2" class="required"></td>
                </tr>
                <tr>
                    <td>Date of Birth:</td>
                    <td>
                        <select name="dateOfBirth_year_2" id="dateOfBirth_year_2">

                            <option selected="selected">Year</option>

                            <?php
                            for($i=1900; $i<2013; $i++){
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>

                        </select>
                        <select name="dateOfBirth_month_2" id="dateOfBirth_month_2">

                            <option selected="selected">Month</option>

                            <?php
                            for($i=1; $i<13; $i++){
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>

                        </select>
                        <select name="dateOfBirth_day_2" id="dateOfBirth_day_2">

                            <option selected="selected">Date</option>

                            <?php
                            for($i=1; $i<32; $i++){
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td>
                        <select name="gender_2" id="gender_2">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Physical Mailing Address:</td>
                    <td><input type="text" name="physicalMail_2" id="physicalMail_2" class="required"></td>
                </tr>
                <tr>
                    <td>Daytime Phone No:</td>
                    <td><input type="tel" name="phone_2" id="phone_2" class="required"></td>
                </tr>
                <tr>
                    <td>Email Address:</td>
                    <td><input type="email" name="email_2" id="email_2" class="required"></td>
                </tr>
                <tr>
                    <td>Citizenship:</td>
                    <td><input type="text" name="citizenship_2" id="citizenship_2" class="required"></td>
                </tr>
                <tr>
                    <td>How would you describe your level of endurance athleticism?</td>
                    <td>
                        <select name="level_2" id="level_2">
                            <option ng-repeat="levelOption in athleticLevelOptions track by $index" value="{{levelOption}}">{{levelOption}}</option>
                        </select>
                    </td>
                </tr>
            </table>

        </div>
        <!--/participant 2 tab-->

        <!--general fields-->
        <div class="clearfix" id="general_fields_wrapper">
            <table>
                <tr>
                    <td>Team Name:</td>
                    <td><input type="text" name="teamName" id="teamName" class="required"></td>
                </tr>
                <tr>
                    <td>Team Category:</td>
                    <td>
                        <select name="teamCategory" id="teamCategory">
                            <option ng-repeat="categoryOption in categoryOptions track by $index" value="{{categoryOption}}">{{categoryOption}}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>CHOOSE PACKAGE:</td>
                    <td>
                        <input type="radio" checked name="package_mode" value="regular_package"> Regular {{paypalData.amount}} {{paypalData.currency}}<br>
                        <input type="radio" name="package_mode" value="traveller_package"> Travellers Package {{eleSettings.package_1}} {{paypalData.currency}}<br>
                    </td>
                </tr>
                <tr>
                    <td>CHOOSE PAYMENT METHOD:</td>
                    <td>
                        <input type="radio" checked name="payment_method" value="pp"> PayPal<br>
                        <input type="radio" name="payment_method" value="cc"> Credit Card<br>
                        <input type="radio" name="payment_method" value="wu"> Western Union
                    </td>
                </tr>
                <tr id="wuField">
                    <td>TEAM EMAIL ADDRESS(important!):</td>
                    <td>
                        <input type="email" name="receiptEmail" id="recieptEmail" class="required"><br>
                        <small>Note: This is were your verification details will be sent when you choose Western Union Payment.</small>
                    </td>
                </tr>
                <td>
                    <img src="<?php echo ELE_URI.'resources/libs/captcha/captcha.php' ?>" id="captcha" />
                </td>

                <td>
                    <div>
                        <input type="text" name="captcha_code" id="captcha_code" size="10" maxlength="6" class="required" /><br>
                        <label>Security check. Please enter the code.</label>
                    </div><br />
                </td>
                <tr>
                    <td colspan="2">
                        <div class="alert alert-info" style="text-align: center;">
                            <h3>Terms and Conditions:</h3>
                            <a href="{{tosLink}}">Read »</a><br>
                            <input type="checkbox" ng-model="tos" name="ele_tos" id="ele_tos"><strong> I have read and agree.</strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span ng-click="submitRegForm()" class="btn btn-primary btn-sm" id="regFormButton">Submit</span><span id="regFormAjax"><img src="<?php echo ELE_URI_IMG.'ajax.gif'; ?>"></span></td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" id="mode" value="<?php echo $settings['mode']; ?>">
                        <input type="hidden" name="cmd" value="_xclick">
                        <input type="hidden" name="item_name" value="<?php echo $settings['item_name']; ?>">
                        <input type="hidden" name="item_number" value="<?php echo $settings['item_number']; ?>">
                        <input type="hidden" name="amount" id="amount" value="<?php echo $settings['amount']; ?>">
                        <input type="hidden" name="custom" value="" id="custom">
                        <input type="hidden" name="business" value="<?php echo $settings['business']; ?>">
                        <input type="hidden" name="currency_code" value="<?php echo $settings['currency']; ?>">
                        <input type="hidden" name="invoice" value="<?php echo $settings['invoice']; ?>">
                        <input type="hidden" name="no_shipping" value="1">
                        <input type="hidden" name="no_note" value="1">
                        <input type="hidden" name="return" value="http://philtoday.net/paypal/success.php">
                        <input type="hidden" name="cancel_return" value="http://philtoday.net/paypal/cancel.php">
                        <input type="hidden" name="rm" value="2">
                        <input type="hidden" name="notify_url" value="<?php echo $settings['notify_url']; ?>">
                        <input type="hidden" name="ele_nonce" value="<?php echo wp_create_nonce("ele_nonce"); ?>"  />
                    </td>
                </tr>
            </table>
        </div>
        <!--general fields-->

        </form>

        </div>

    </div>
    <?php
}
add_shortcode('deploy_registration_form','ele_front_view_registration_form');