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

$string['quiz_dyn_key:addinstance'] = 'Peut ajouter un bloc de rotation des clef d\'accès au test';
$string['quiz_dyn_key:getcode'] = 'Peut afficher le code d\'accès';
$string['quiz_dyn_key:changecode'] = 'Peut changer interactivement le code en cours';

$string['config_cronenable'] = 'Activer la rotation des codes d\'accès';
$string['config_notifychanges'] = 'Notifier les codes d\'accès lors du changement';
$string['config_keychangedays'] = 'Jours de rotation';
$string['config_keychangehour'] = 'Heure de rotation';
$string['config_keychangemins'] = 'Minutes de rotation';
$string['config_passuppers'] = 'Majuscules';
$string['config_passlowers'] = 'Minuscules';
$string['config_passnumeric'] = 'Nombres';
$string['keychange'] = 'Changement de clef d\'accès au test';
$string['regeneratenow'] = 'Changer la clef maintenant !';
$string['error_emptyquizrecord'] = 'Ce test n\'existe pas';
$string['error_emptyquizid'] = 'Ce bloc n\'a pas encore été configuré';
$string['pluginname'] = 'Rotation des clefs d\'accès au test';
$string['quizcurrentcode'] = 'Clef actuelle du test<br/><b>{$a->quizname}</b><br/><div class="quiz-code">{$a->password}</div>';
$string['config_passlength'] = 'Longueur de la clef';

$string['notifymail'] = '
Bonjour {$a->firstname} {$a->lastname},

Le code d\'accès au test {$a->quizname} dans
le cours {$a->coursename} a changé.

Le code d\'accès est désormais {$a->quizpassword} jusqu\'au prochain changement.
';

$string['notifyhtmlmail'] = '
<p>Bonjour {$a->firstname} {$a->lastname},</p>

<p>le code d\'accès au test {$a->quizname} dans le
cours {$a->coursename} a changé.</p>

<b>Le nouveau code est désormais <b>{$a->quizpassword}</b> jusqu\'au prochain changement.</p>
';
