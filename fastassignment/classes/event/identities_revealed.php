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
 * The mod_fastassignment identities revealed event.
 *
 * @package    mod_fastassignment
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_fastassignment\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The mod_fastassignment identities revealed event class.
 *
 * @package    mod_fastassignment
 * @since      Moodle 2.6
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class identities_revealed extends base {
    /**
     * Flag for prevention of direct create() call.
     * @var bool
     */
    protected static $preventcreatecall = true;

    /**
     * Create instance of event.
     *
     * @since Moodle 2.7
     *
     * @param \fastassignment $fastassignment
     * @return identities_revealed
     */
    public static function create_from_assign(\fastassignment $fastassignment) {
        $data = array(
            'context' => $fastassignment->get_context(),
            'objectid' => $fastassignment->get_instance()->id
        );
        self::$preventcreatecall = false;
        /** @var identities_revealed $event */
        $event = self::create($data);
        self::$preventcreatecall = true;
        $event->set_assign($fastassignment);
        return $event;
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' has revealed identities in the assignment with course module " .
            "id '$this->contextinstanceid'.";
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventidentitiesrevealed', 'mod_fastassignment');
    }

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
        $this->data['objecttable'] = 'fastassignment';
    }

    /**
     * Return legacy data for add_to_log().
     *
     * @return array
     */
    protected function get_legacy_logdata() {
        $this->set_legacy_logdata('reveal identities', get_string('revealidentities', 'fastassignment'));
        return parent::get_legacy_logdata();
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        if (self::$preventcreatecall) {
            throw new \coding_exception('cannot call identities_revealed::create() directly, use identities_revealed::create_from_assign() instead.');
        }

        parent::validate_data();
    }

    public static function get_objectid_mapping() {
        return array('db' => 'fastassignment', 'restore' => 'fastassignment');
    }
}
