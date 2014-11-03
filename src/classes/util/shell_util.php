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

namespace local_devkit\util;

defined('MOODLE_INTERNAL') || die;

/**
 * Shell utility methods.
 *
 * Time savers.
 */
class shell_util {
    /**
     * Try to find the PHP binary.
     *
     * @return string The path to the PHP binary.
     *
     * @todo Add support for other platforms/PHP versions.
     */
    public static function get_php_binary() {
        if (defined('PHP_BINARY')) {                 // PHP >= 5.4
            return PHP_BINARY;
        } elseif (array_key_exists('_', $_SERVER)) { // Linux shell
            return $_SERVER['_'];
        } else {                                     // Hope it's on PATH
            return 'php';
        }
    }

    /**
     * Escape and implode an array of arguments.
     *
     * @param string[] $args The array of arguments.
     *
     * @return string A string representation of the arguments.
     */
    public static function stringify_args($args) {
        return implode(' ', array_map('escapeshellarg', $args));
    }

    /**
     * Execute a shell command with an array of arguments.
     *
     * @param string   $prog The program name, either fully qualified or as it
     *                       appears on PATH.
     * @param string[] $args The array of arguments to pass to the program.
     *
     * @return string Command line output from the CLI application.
     */
    public static function shell_exec_args($prog, $args) {
        return shell_exec($prog . ' ' . static::stringify_args($args));
    }
}
