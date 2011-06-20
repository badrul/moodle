<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This page receives non-ajax rating submissions
 *
 * It is similar to rate_ajax.php. Unlike rate_ajax.php a return url is required.
 *
 * @package    core
 * @subpackage rating
 * @copyright  2010 Andrew Davis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../config.php');
<<<<<<< HEAD
require_once($CFG->dirroot.'/rating/lib.php');

$contextid   = required_param('contextid', PARAM_INT);
$component   = required_param('component', PARAM_ALPHAEXT);
$ratingarea  = required_param('ratingarea', PARAM_ALPHANUMEXT);
$itemid      = required_param('itemid', PARAM_INT);
$scaleid     = required_param('scaleid', PARAM_INT);
$userrating  = required_param('rating', PARAM_INT);
=======
require_once('lib.php');

$contextid = required_param('contextid', PARAM_INT);
$component = required_param('component', PARAM_ALPHAEXT);
$itemid = required_param('itemid', PARAM_INT);
$scaleid = required_param('scaleid', PARAM_INT);
$userrating = required_param('rating', PARAM_INT);
>>>>>>> remotes/upstream/MOODLE_20_STABLE
$rateduserid = required_param('rateduserid', PARAM_INT);//which user is being rated. Required to update their grade
$returnurl   = required_param('returnurl', PARAM_LOCALURL);//required for non-ajax requests

$result = new stdClass;

list($context, $course, $cm) = get_context_info_array($contextid);
require_login($course, false, $cm);

$contextid = null;//now we have a context object throw away the id from the user
$PAGE->set_context($context);
<<<<<<< HEAD
$PAGE->set_url('/rating/rate.php', array('contextid' => $context->id));
=======
$PAGE->set_url('/rating/rate.php', array('contextid'=>$context->id));
>>>>>>> remotes/upstream/MOODLE_20_STABLE

if (!confirm_sesskey() || !has_capability('moodle/rating:rate',$context)) {
    echo $OUTPUT->header();
    echo get_string('ratepermissiondenied', 'rating');
    echo $OUTPUT->footer();
    die();
}

$rm = new rating_manager();

//check the module rating permissions
//doing this check here rather than within rating_manager::get_ratings() so we can return a json error response
<<<<<<< HEAD
$pluginpermissionsarray = $rm->get_plugin_permissions_array($context->id, $component, $ratingarea);
=======
$pluginpermissionsarray = $rm->get_plugin_permissions_array($context->id, $component);
>>>>>>> remotes/upstream/MOODLE_20_STABLE

if (!$pluginpermissionsarray['rate']) {
    $result->error = get_string('ratepermissiondenied', 'rating');
    echo json_encode($result);
    die();
} else {
    $params = array(
<<<<<<< HEAD
        'context'     => $context,
        'component'   => $component,
        'ratingarea'  => $ratingarea,
        'itemid'      => $itemid,
        'scaleid'     => $scaleid,
        'rating'      => $userrating,
        'rateduserid' => $rateduserid
    );
    if (!$rm->check_rating_is_valid($params)) {
=======
        'context' => $context,
        'itemid' => $itemid,
        'scaleid' => $scaleid,
        'rating' => $userrating,
        'rateduserid' => $rateduserid);

    if (!$rm->check_rating_is_valid($component, $params)) {
>>>>>>> remotes/upstream/MOODLE_20_STABLE
        echo $OUTPUT->header();
        echo get_string('ratinginvalid', 'rating');
        echo $OUTPUT->footer();
        die();
    }
}

if ($userrating != RATING_UNSET_RATING) {
    $ratingoptions = new stdClass;
    $ratingoptions->context = $context;
    $ratingoptions->component = $component;
<<<<<<< HEAD
    $ratingoptions->ratingarea = $ratingarea;
=======
>>>>>>> remotes/upstream/MOODLE_20_STABLE
    $ratingoptions->itemid  = $itemid;
    $ratingoptions->scaleid = $scaleid;
    $ratingoptions->userid  = $USER->id;

    $rating = new rating($ratingoptions);
    $rating->update_rating($userrating);
} else { //delete the rating if the user set to Rate...
    $options = new stdClass;
    $options->contextid = $context->id;
    $options->component = $component;
<<<<<<< HEAD
    $options->ratingarea = $ratingarea;
=======
>>>>>>> remotes/upstream/MOODLE_20_STABLE
    $options->userid = $USER->id;
    $options->itemid = $itemid;

    $rm->delete_ratings($options);
}

//todo add a setting to turn grade updating off for those who don't want them in gradebook
//note that this needs to be done in both rate.php and rate_ajax.php
if (!empty($cm) && $context->contextlevel == CONTEXT_MODULE) {
    //tell the module that its grades have changed
    $modinstance = $DB->get_record($cm->modname, array('id' => $cm->instance), '*', MUST_EXIST);
    $modinstance->cmidnumber = $cm->id; //MDL-12961
    $functionname = $cm->modname.'_update_grades';
    require_once($CFG->dirroot."/mod/{$cm->modname}/lib.php");
    if (function_exists($functionname)) {
        $functionname($modinstance, $rateduserid);
    }
}

redirect($returnurl);