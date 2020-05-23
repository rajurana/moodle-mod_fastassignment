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
 * This file contains the class for restore of this submission plugin
 *
 * @package fastassignsubmission_onlinetext
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Restore subplugin class.
 *
 * Provides the necessary information needed to restore
 * one fastassignment_submission subplugin.
 *
 * @package fastassignsubmission_onlinetext
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_fastassignsubmission_onlinetext_subplugin extends restore_subplugin {

    /**
     * Returns array the paths to be handled by the subplugin at assignment level
     * @return array
     */
    protected function define_submission_subplugin_structure() {

        $paths = array();

        $elename = $this->get_namefor('submission');

        // We used get_recommended_name() so this works.
        $elepath = $this->get_pathfor('/submission_onlinetext');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths;
    }

    /**
     * Processes one fastassignsubmission_onlinetext element
     *
     * @param mixed $data
     */
    public function process_fastassignsubmission_onlinetext_submission($data) {
        global $DB;

        $data = (object)$data;
        $data->assignment = $this->get_new_parentid('fastassignment');
        $oldsubmissionid = $data->submission;
        // The mapping is set in the restore for the core fastassignment activity
        // when a submission node is processed.
        $data->submission = $this->get_mappingid('submission', $data->submission);

        $DB->insert_record('fastasgnsubmsn_onlinetext', $data);

        $this->add_related_files('fastassignsubmission_onlinetext', 'submissions_onlinetext', 'submission', null, $oldsubmissionid);
    }

}
