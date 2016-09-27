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

use local_devkit\helper\plugin_helper;

define('CLI_SCRIPT', true);

require_once dirname(dirname(dirname(__DIR__))) . '/config.php';
require_once "{$CFG->libdir}/adminlib.php";
require_once "{$CFG->libdir}/clilib.php";

list($options, $unrecognized) = cli_get_params(
    array(
        'action' => null,
        'component' => null,
    ),
    array(
        'a' => 'action',
        'c' => 'component',
    )
);

$helper   = new plugin_helper(core_plugin_manager::instance());
$progress = new progress_trace_buffer(new text_progress_trace(), false);

switch ($options['action']) {
    case 'install':
        $helper->install($options['component'], $progress);
        break;

    case 'uninstall':
        $helper->uninstall($options['component'], $progress);
        break;

    case 'upgrade':
        $helper->upgrade($progress);
        break;

    default:
        echo $helper->help();
}

$progress->finished();
echo $progress->get_buffer();
