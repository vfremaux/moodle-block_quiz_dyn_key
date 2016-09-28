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

defined('MOODLE_INTERNAL') || die();

/**
 * Defines the form for editing Quiz dynamic keying block instances.
 *
 * @package    block_quiz_dyn_key
 * @category   blocks
 * @copyright  2015 Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing Quiz results block instances.
 *
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_quiz_dyn_key_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $DB;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        if (!$this->block->get_owning_quiz()) {
            $quizzes = $DB->get_records_menu('quiz', array('course' => $this->page->course->id), '', 'id, name');
            if(empty($quizzes)) {
                $mform->addElement('static', 'noquizzeswarning', get_string('config_select_quiz', 'block_quiz_dyn_key'),
                        get_string('config_no_quizzes_in_course', 'block_quiz_dyn_key'));
            } else {
                foreach($quizzes as $id => $name) {
                    $quizzes[$id] = strip_tags(format_string($name));
                }
                $mform->addElement('select', 'config_quizid', get_string('config_select_quiz', 'block_quiz_results'), $quizzes);
            }
        }

        $mform->addElement('advcheckbox', 'config_cronenable', get_string('config_cronenable', 'block_quiz_dyn_key'), '', 1);
        $mform->setType('config_cronenable', PARAM_BOOL);

        $mform->addElement('advcheckbox', 'config_notifychanges', get_string('config_notifychanges', 'block_quiz_dyn_key'), '', 1);
        $mform->setType('config_notifychanges', PARAM_BOOL);

        $mform->addElement('text', 'config_keychangedays', get_string('config_keychangedays', 'block_quiz_dyn_key'));
        $mform->setDefault('config_keychangedays', '1,2,3,4,5,6,7');
        $mform->setType('config_keychangedays', PARAM_TEXT);

        $mform->addElement('text', 'config_keychangehour', get_string('config_keychangehour', 'block_quiz_dyn_key'));
        $mform->setType('config_keychangehour', PARAM_INT);

        $mform->addElement('text', 'config_keychangemins', get_string('config_keychangemins', 'block_quiz_dyn_key'));
        $mform->setType('config_keychangemins', PARAM_INT);

        $mform->addElement('text', 'config_passlength', get_string('config_passlength', 'block_quiz_dyn_key'), array('size' => 2));
        $mform->setDefault('config_passlength', 8);
        $mform->setType('config_passlength', PARAM_INT);

        $mform->addElement('advcheckbox', 'config_passuppers', get_string('config_passuppers', 'block_quiz_dyn_key'), '', 1);
        $mform->setType('config_passuppers', PARAM_BOOL);

        $mform->addElement('advcheckbox', 'config_passlowers', get_string('config_passuppers', 'block_quiz_dyn_key'), '', 1);
        $mform->setType('config_passlowers', PARAM_BOOL);

        $mform->addElement('advcheckbox', 'config_passnumeric', get_string('config_passnumeric', 'block_quiz_dyn_key'), '', 1);
        $mform->setType('config_passnumeric', PARAM_BOOL);
    }
}