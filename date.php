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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle/Totara LMS DevKit plugin.
 *
 * A suite of developer tools that aim to smooth over some of the cracks in
 * Moodle. This plugin should absolutely not be installed on production sites.
 *
 * @author Luke Carrier <luke@carrier.im>
 * @copyright 2014 Luke Carrier
 * @license GPL v3
 */

require_once dirname(dirname(__DIR__)) . '/config.php';

use local_devkit\util;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/devkit/date.php'));

$stringmgr = get_string_manager();
/** @var local_devkit_renderer $renderer */
$renderer = $PAGE->get_renderer(util::MOODLE_COMPONENT);

echo
    $OUTPUT->header(),
    $OUTPUT->heading(get_string('date:timezones', util::MOODLE_COMPONENT)),
    $renderer->render_key_value_pair_table(array(
        get_string('www:code', util::MOODLE_COMPONENT),
        get_string('date:timezone', util::MOODLE_COMPONENT),
    ), core_date::get_list_of_timezones()),
    $OUTPUT->footer();
