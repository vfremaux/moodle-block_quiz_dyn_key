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
 * Strings for component 'block_quiz_results', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    block
 * @subpackage quiz_dyn_key
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['quiz_dyn_key:addinstance'] = 'Add a new Quiz Dynamic Key block';
$string['quiz_dyn_key:getcode'] = 'Can see the current code';
$string['quiz_dyn_key:changecode'] = 'Can change interactively the current code';

$string['config_cronenable'] = 'Enable cron key change';
$string['config_notifychanges'] = 'Notify key changes';
$string['config_keychangedays'] = 'Key rotation days';
$string['config_keychangehour'] = 'Key rotation hour';
$string['config_keychangemins'] = 'Key rotation minutes';
$string['config_passlength'] = 'Pass code length';
$string['config_passuppers'] = 'Uppercases';
$string['config_passlowers'] = 'Lowercases';
$string['config_passnumeric'] = 'Numerics';
$string['keychange'] = 'Test password change';
$string['regeneratenow'] = 'Change code now!';
$string['pluginname'] = 'Quiz Dynamic Key';
$string['error_emptyquizrecord'] = 'This quiz does not exist';
$string['error_emptyquizid'] = 'This block has not yet been configured.';
$string['quizcurrentcode'] = 'Current for quiz:<br/><b>{$a->quizname}</b><br/><div class="quiz-code">{$a->password}</div>';

$string['confignotifymail'] = '
Dear {$a->firstname} {$a->lastname},

The access password to the quiz {$a->quizname} in 
course {$a->coursename} has changed. 

the code is now {$a->quizpassword} until next change notification.
';

$string['confignotifyhtmlmail'] = '
<p>Dear {$a->firstname} {$a->lastname},</p>

<p>The access password to the quiz {$a->quizname} in 
course {$a->coursename} has changed. </p>

<b>the code is now <b>{$a->quizpassword}</b> until next change notification.</p>
';
