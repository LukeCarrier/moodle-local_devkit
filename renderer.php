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

defined('MOODLE_INTERNAL') || die;

/**
 * DevKit renderer.
 */
class local_devkit_renderer extends plugin_renderer_base {
    /**
     * Render a table from an array of key value pairs.
     *
     * @param string[] $head
     * @param mixed[] $pairs
     *
     * @return string
     */
    public function render_key_value_pair_table($head, $pairs) {
        $table = new html_table();
        $table->head = $head;
        foreach ($pairs as $key => $value) {
            $table->data[] = array(
                html_writer::tag('code', $key),
                $value,
            );
        }
        return html_writer::table($table);
    }
}
