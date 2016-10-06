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
 * Classes to enforce the various access rules that can apply to a quiz.
 *
 * @package    block_quiz_dyn_key
 * @category   blocks
 * @copyright  2015 Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/lib.php');

/**
 * Block quiz_results class definition.
 *
 * This block can be added to a course page or a quiz page to display of list of
 * the best/worst students/groups in a particular quiz.
 *
 * @package    block
 * @subpackage quiz_dyn_key
 * @copyright  2014 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_quiz_dyn_key extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_quiz_dyn_key');
    }

    public function applicable_formats() {
        return array('course' => true, 'mod-quiz' => true);
    }

    /**
     * If this block belongs to a quiz context, then return that quiz's id.
     * Otherwise, return 0.
     * @return integer the quiz id.
     */
    public function get_owning_quiz() {

        if (empty($this->instance->parentcontextid)) {
            return 0;
        }

        $parentcontext = context::instance_by_id($this->instance->parentcontextid);
        if ($parentcontext->contextlevel != CONTEXT_MODULE) {
            return 0;
        }

        $cm = get_coursemodule_from_id('quiz', $parentcontext->instanceid);

        if (!$cm) {
            return 0;
        }

        return $cm->instance;
    }

    public function instance_config_save($data, $nolongerused = false) {
        if (empty($data->quizid)) {
            $data->quizid = $this->get_owning_quiz();
        }
        parent::instance_config_save($data);
    }

    public function get_content() {
        global $USER, $CFG, $DB, $COURSE;

        $context = context_block::instance($this->instance->id);

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        if (empty($this->instance)) {
            return $this->content;
        }

        if (!has_capability('block/quiz_dyn_key:getcode', $context)) {
            return $this->content;
        }

        if (!empty($this->config->quizid)) {
            $quizid = $this->config->quizid;
            $quiz = $DB->get_record('quiz', array('id' => $quizid));

            if (has_capability('block/quiz_dyn_key:changecode', $context)) {
                $regen = optional_param('quizkeyregen', false, PARAM_BOOL);
                if ($regen) {
                    $quiz->password = $this->generate_password();
                    $DB->set_field('quiz', 'password', $quiz->password);
                }
                $regenurl = new moodle_url('/course/view.php', array('id' => $COURSE->id, 'quizkeyregen' => 1));
                $this->content->footer = '<a href="'.$regenurl.'">'.get_string('regeneratenow', 'block_quiz_dyn_key').'</a>';
            }

            if (empty($quiz->password)) {
                return $this->content;
            }
            if (empty($quiz)) {
                $this->content->text = get_string('error_emptyquizrecord', 'block_quiz_dyn_key');
                return $this->content;
            }
            $a = new StdClass();
            $a->password = $quiz->password;
            $a->quizname = $quiz->name;
            $keytxt = get_string('quizcurrentcode', 'block_quiz_dyn_key', $a);
            $this->content->text = '<div class="quiz-current-key">'.$keytxt.'</div>';
        } else {
            $quizid = 0;
        }

        // TODO : display code to designated customer.

        if (empty($quizid)) {
            $this->content->text = get_string('error_emptyquizid', 'block_quiz_dyn_key');
            return $this->content;
        }

        return $this->content;
    }

    /**
     * Cron process will change regularily the access code depending on settings
     */
    public function cron() {
        global $SITE, $DB;

        $debug = optional_param('forcequizdynkeydebug', false, PARAM_BOOL);

        if ($allquizdynkeys = $DB->get_records('block_instances', array('blockname' => 'quiz_dyn_key'))) {
            foreach ($allquizdynkeys as $qdk) {

                $instance = block_instance('dashboard', $qdk);

                if (empty($instance->config->quizid)) {
                    return;
                }

                if (empty($instance->config->rotatekey)) {
                    mtrace('Processing Quiz Dyn Key is disabled for instance');
                    return;
                }

                mtrace('Processing Quiz Dyn Key block cron');

                $now = time();
                $midnight = strtotime(date("Ymd"));

                if (((@$instance->config->lastpass + DAYSECS) > $midnight) && empty($instance->config->cando) && !$debug) {
                    $instance->config->cando = true;
                    $instance->instance_config_save($this->config);
                    return;
                }

                if (empty($instance->config->cando) && !$debug) {
                    // Yet still not the time.
                    return;
                }

                // Now it could be time, we check the day and the clock time.

                $dayofweek = date('N', $now);
                if (!preg_match("/\\b$dayofweek\\b/", $instance->config->keychangedays) && !$debug) {
                    // Not the good day.
                    return;
                }

                if ((date('G', $now) * 60 + date('i', $now)) < ($instance->config->keychangehour * 60 + $instance->config->keychangemins) && !$debug) {
                    // Not yet the good time.
                    return;
                }

                // Now it could be a good day, is this the good time.

                $quiz = $DB->get_record('quiz', array('id' => $instance->config->quizid));
                $quiz->password = $instance->generate_password();
                $DB->set_field('quiz', 'password', $quiz->password);

                $adminuser = get_admin();

                // Notify users.
                if (!empty($instance->config->notifychanges)) {
                    $fields = 'u.id,'.get_all_user_name_fields(true, 'u');
                    $notifyusers = get_users_by_capability($blockcontext, 'block/quiz_dyn_key:getcode', $fields);
                    foreach ($notifyusers as $u) {
                        $u->password = $quiz->password;
                        $user = $DB->get_record('user', array('id' => $u->id));
                        $mailtitle = get_string('keychange', 'block_quiz_dyn_key');
                        $email = get_string('notifymail', 'block_quiz_dyn_key', $u);
                        $emailhtml = get_string('notifyhtmlmail', 'block_quiz_dyn_key', $u);
                        email_to_user($adminuser, $user, $SITE->shortname.' '.$mailtitle, $email, $emailhtml);
                    }
                }
                $instance->config->lastpass = time();
                $instance->config->cando = false;
                $instance->instance_config_save($instance->config);
            }
        }
    }

    /**
     * generates a configurable length and charset password.
     * @return a password string
     */
    public function generate_password() {
        $set1 = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $set2 = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $set3 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        $passet = array();
        if ($this->config->passlowers || !isset($this->config->passlowers)) {
            $passet += $set2;
        }

        if ($this->config->passuppers || !isset($this->config->passuppers)) {
            $passet += $set1;
        }

        if ($this->config->passnumeric || !isset($this->config->passnumeric)) {
            $passet += $set3;
        }

        $passetsize = count($passet);

        if (empty($this->config->passlength)) {
            $this->config->passlength = 8;
        }

        $pass = '';
        for ($i = 0; $i < $this->config->passlength; $i++) {
            $j = rand(0, $passetsize - 1);
            $pass .= $passet[$j];
        }
        return $pass;
    }

    public function instance_allow_multiple() {
        return true;
    }
}