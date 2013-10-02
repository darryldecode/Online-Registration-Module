<?php

function ele_online_registration(){

    ?>

    <div class="row ele-admin-wrapper" ng-app="eleAPP" id="ng-app" ng-controller="main">

        <div class="col-lg-12">

        <div class="row">

            <div class="col-lg-6">
                <h3 class="ele-title"><b>ONLINE REGISTRATION ADMIN PANEL</b></h3>
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
                        <th colspan="2"><span class="label label-success"><strong>Author Info:</strong></span></th>
                    </tr>
                    <tr>
                        <td>Darryl Coder</td>
                    </tr>
                    <tr>
                        <td>Engrdarrylfernandez@gmail.com</td>
                    </tr>
                </table>
            </div>

        </div>

            <hr>

            <div class="clearfix filter-holder">
                <span>
                Search Everything: <input type="text" ng-model="search.$" />
                </span>&nbsp;&nbsp;

                <span>
                Filter by Status:   <select ng-model="search.ele_entry_status">
                                        <option value="">All</option>
                                        <option value="1">Paid</option>
                                        <option value="0">Unpaid</option>
                                    </select>
                </span>

                <span>
                Filter by Payment Method:   <select ng-model="search.ele_entry_payment_method">
                        <option value="">All</option>
                        <option value="pp">Paypal</option>
                        <option value="cc">Credit Card</option>
                        <option value="wu">Western Union</option>
                    </select>
                </span>

                <span class="pull-right total-entries-holder">
                    Total Entries: <span class="badge">{{totalEntries}}</span>
                    <button ng-click="refreshList()" class="btn btn-default btn-xs">Refresh</button>
                </span>

                <span class="pull-right total-entries-holder">
                    Browse Pages:   <select id="paginator" ng-model="selectedPage" ng-change="doPaging()">
                                        <option ng-repeat="i in pages" value="{{i+1}}">{{i+1}}</option>
                                    </select>
                </span>
            </div>

            <div class="tbl-data-holder">

                <table class="wp-list-table widefat ele-data-table">
                    <tr>
                        <th>Entry ID</th>
                        <th>Team Info</th>
                        <th>Member 1</th>
                        <th>Member 2</th>
                        <th>Payment Method Used</th>
                        <th>Status</th>
                    </tr>
                    <tr ng-repeat="registrant in registrants | filter:search">
                        <td>{{registrant.ele_entry_id}}</td>
                        <td>
                            <table class="tbl-team-info">
                                <tr>
                                    <td>Registration Date:</td>
                                    <td>{{registrant.ele_entry_registration_date}}</td>
                                </tr>
                                <tr>
                                    <td>Team Name:</td>
                                    <td>{{registrant.ele_entry_team_name}}</td>
                                </tr>
                                <tr>
                                    <td>Category Name:</td>
                                    <td>{{registrant.ele_entry_category_name}}</td>
                                </tr>
                                <tr>
                                    <td>Team Email:</td>
                                    <td>{{registrant.ele_entry_email}}</td>
                                </tr>
                                <tr>
                                    <td>PACKAGE TYPE:</td>
                                    <td>
                                        <span ng-show="(registrant.ele_entry_package_type=='regular_package')">Regular Package</span>
                                        <span ng-show="(registrant.ele_entry_package_type=='traveller_package')">Traveller Package</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Need a Bike?:</td>
                                    <td>
                                        <span ng-show="(registrant.ele_entry_need_bike=='yes')">Yes</span>
                                        <span ng-show="(registrant.ele_entry_need_bike=='no')">No</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Need a Accomodation?:</td>
                                    <td>
                                        <span ng-show="(registrant.ele_entry_accommodation=='yes')">Yes</span>
                                        <span ng-show="(registrant.ele_entry_accommodation=='no')">No</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><span class="label label-info"><i>Transaction INFO:</i></span></td>
                                </tr>
                                <tr>
                                    <td>Invoice #:</td>
                                    <td>{{registrant.ele_entry_invoice}}</td>
                                </tr>
                                <tr>
                                    <td>TXN ID:</td>
                                    <td>{{registrant.ele_entry_txn_id}}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="tbl-participant-info">
                                <tr>
                                    <td>First Name:</td>
                                    <td>{{registrant[0].ele_registrant_first_name}}</td>
                                </tr>
                                <tr>
                                    <td>Middle Name:</td>
                                    <td>{{registrant[0].ele_registrants_middle_name}}</td>
                                </tr>
                                <tr>
                                    <td>Last Name:</td>
                                    <td>{{registrant[0].ele_registrants_last_name}}</td>
                                </tr>
                                <tr>
                                    <td>Phone:</td>
                                    <td>{{registrant[0].ele_registrants_phone}}</td>
                                </tr>
                                <tr>
                                    <td>Birth Date:</td>
                                    <td>{{registrant[0].ele_registrants_date_of_birth}}</td>
                                </tr>
                                <tr>
                                    <td>Gender:</td>
                                    <td>{{registrant[0].ele_registrants_gender}}</td>
                                </tr>
                                <tr>
                                    <td>Citizenship:</td>
                                    <td>{{registrant[0].ele_registrants_citizenship}}</td>
                                </tr>
                                <tr>
                                    <td>Mailing Address:</td>
                                    <td>{{registrant[0].ele_registrants_mailing_address}}</td>
                                </tr>
                                <tr>
                                    <td>Email Address:</td>
                                    <td>{{registrant[0].ele_registrants_email_address}}</td>
                                </tr>
                                <tr>
                                    <td>Athletic Level:</td>
                                    <td>{{registrant[0].ele_registrants_athletic_level}}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="tbl-participant-info">
                                <tr>
                                    <td>First Name:</td>
                                    <td>{{registrant[1].ele_registrant_first_name}}</td>
                                </tr>
                                <tr>
                                    <td>Middle Name:</td>
                                    <td>{{registrant[1].ele_registrants_middle_name}}</td>
                                </tr>
                                <tr>
                                    <td>Last Name:</td>
                                    <td>{{registrant[1].ele_registrants_last_name}}</td>
                                </tr>
                                <tr>
                                    <td>Phone:</td>
                                    <td>{{registrant[1].ele_registrants_phone}}</td>
                                </tr>
                                <tr>
                                    <td>Birth Date:</td>
                                    <td>{{registrant[1].ele_registrants_date_of_birth}}</td>
                                </tr>
                                <tr>
                                    <td>Gender:</td>
                                    <td>{{registrant[1].ele_registrants_gender}}</td>
                                </tr>
                                <tr>
                                    <td>Citizenship:</td>
                                    <td>{{registrant[1].ele_registrants_citizenship}}</td>
                                </tr>
                                <tr>
                                    <td>Mailing Address:</td>
                                    <td>{{registrant[1].ele_registrants_mailing_address}}</td>
                                </tr>
                                <tr>
                                    <td>Email Address:</td>
                                    <td>{{registrant[1].ele_registrants_email_address}}</td>
                                </tr>
                                <tr>
                                    <td>Athletic Level:</td>
                                    <td>{{registrant[1].ele_registrants_athletic_level}}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <span ng-show="(registrant.ele_entry_payment_method=='pp')"><img src="<?php echo ELE_URI_IMG.'paypal-icon.png'; ?>"></span>
                            <span ng-show="(registrant.ele_entry_payment_method=='cc')"><img src="<?php echo ELE_URI_IMG.'credit-card-icon.png'; ?>"></span>
                            <span ng-show="(registrant.ele_entry_payment_method=='wu')"><img src="<?php echo ELE_URI_IMG.'western-union-icon.png'; ?>"></span>
                        </td>
                        <td>
                            <span class="label label-primary label-xs" ng-show="(registrant.ele_entry_status == '1')">Paid</span>
                            <button class="btn btn-danger btn-xs btn-set-paid-{{registrant.ele_entry_id}}" ng-show="(registrant.ele_entry_status == '0')" ng-click="setPaid(registrant.ele_entry_id)">Set as paid</button>
                        </td>
                    </tr>
                </table>

            </div>

        </div>

    </div>

    <?php

}