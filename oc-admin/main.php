<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    class CAdminMain extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('logout'):     // unset only the required parameters in Session
                                    $this->logout();
                                    $this->redirectTo( osc_admin_base_url(true) );
                break;
                default:            //default dashboard page (main page at oc-admin)
                                    $this->_exportVariableToView( "numItemsPerCategory", osc_get_non_empty_categories() );

                                    $this->_exportVariableToView( "numUsers", User::newInstance()->count() );
                                    $this->_exportVariableToView( "numItems", Item::newInstance()->count() );

                                    $this->_exportVariableToView('newsList', osc_listNews());
                                    $this->_exportVariableToView('twitterRSS', osc_latestTweets());

                                    // stats
                                    $items = array();
                                    $stats_items = Stats::newInstance()->new_items_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day');
                                    for($k = 10; $k >= 0; $k--) {
                                        $items[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0;
                                    }
                                    foreach($stats_items as $item) {
                                        $items[$item['d_date']] = $item['num'];
                                    }
                                    $users = array();
                                    $stats_users = Stats::newInstance()->new_users_count(date( 'Y-m-d H:i:s',  mktime(0, 0, 0, date("m"), date("d") - 10, date("Y")) ),'day') ;
                                    for($k = 10; $k >= 0; $k--) {
                                        $users[date( 'Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y")) )] = 0 ;
                                    }
                                    foreach($stats_users as $user) {
                                        $users[$user['d_date']] = $user['num'] ;
                                    }

                                    $this->_exportVariableToView("item_stats", $items);
                                    $this->_exportVariableToView("user_stats", $users);
                                    //calling the view...
                                    $this->doView('main/index.php');
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
        }
    }

    /* file end: ./oc-admin/main.php */
?>