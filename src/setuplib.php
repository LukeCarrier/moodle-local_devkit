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

/* Don't check for MOODLE_INTERNAL here, as this library is designed to be
/* sourced prior to /lib/setup.php. */

if (!defined('LOCAL_DEVKIT_SETUPLIB')) {
    define('LOCAL_DEVKIT_SETUPLIB', true);

    /**
     * Determine the current Moodle series from Git's HEAD.
     *
     * Git stores the actively checked out commit in a file within its data
     * directory called HEAD.
     *
     * Generally speaking, this commit will be the last commit on a given branch
     * within the repository, thus the "commit" considered the HEAD will
     * actually be a branch.
     *
     * If we encounter a detached HEAD state (that is, HEAD points to an
     * arbitrary commit instead of a branch), we have no way of determining the
     * correct branch, and fall back to using "master".
     *
     * @return string Either a (two digit) number extracted from a
     *                MOODLE_xx_STABLE branch name, or "master".
     */
    function local_devkit_moodle_series($directory) {
        global $CFG;

        $headcontents = file_get_contents("{$directory}/.git/HEAD");

        preg_match('/MOODLE_([0-9]+)_STABLE$/', $headcontents, $seriesmatches);
        preg_match('/([a-zA-Z0-9]+)$/',         $headcontents, $rawmatches);

        return (count($seriesmatches) === 2) ? $seriesmatches[1] : $rawmatches[1];
    }

    /**
     * Determine the current SERVER_ADDR.
     *
     * In order to ensure SAPIs which don't provide a SERVER_ADDR in the $_SERVER
     * superglobal don't leave us with a badly formed URL, we fall back to a local
     * IPv4 host if one isn't found.
     *
     * This _might_ break some MNet configurations.
     *
     * @return string The server's LAN IP, or the IPv4 loopback IP.
     */
    function local_devkit_server_address() {
        return array_key_exists('SERVER_ADDR', $_SERVER)
                ? $_SERVER['SERVER_ADDR'] : '127.0.0.1';
    }
}
