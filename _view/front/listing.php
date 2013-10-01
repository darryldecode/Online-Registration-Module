<?php

function ele_front_display_listing(){

    ?>

    <div class="row" ng-app="eleFront" ng-controller="eleListing">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="panel panel-default">

                <div class="panel-heading clearfix">
                    <div class="pull-left">
                        Search: <input type="text" ng-model="search.$" />
                    </div>
                    <div class="pull-right">
                        Browse Pages:   <select ng-cloak id="paginator" ng-model="selectedPage" ng-change="doPaging()">
                            <option ng-repeat="i in pages" value="{{i+1}}">{{i+1}}</option>
                        </select>
                    </div>
                </div>


                <table class="table" id="ele-listing-table">
                    <tr ng-repeat="registrant in registrants | filter:search track by $index">
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
                                    <td>Status:</td>
                                    <td>
                                        <span ng-show="(registrant.ele_entry_status=='1')" class="label label-success">Confirmed</span>
                                        <span ng-show="(registrant.ele_entry_status=='0')" class="label label-warning">Unconfirmed</span>
                                    </td>
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
                                    <td>Athletic Level:</td>
                                    <td>{{registrant[1].ele_registrants_athletic_level}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>

        </div>

    </div>

    <?php

}
add_shortcode('deploy_listing','ele_front_display_listing');