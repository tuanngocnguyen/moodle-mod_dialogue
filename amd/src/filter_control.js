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

define(
    [
        'jquery',
        'core/custom_interaction_events',
        'core/log',
        'mod_dialogue/user_preferences'
    ],
    function(
        $,
        customEvents,
        log,
        userPreferences
    ) {
        var SELECTORS = {
            DESCENDANTS: '[data-filter]',
            PREFERENCE: 'data-preference',
            VALUE: 'data-value'
        };

        var preferences = [];

        /**
         *
         * @param {object} root The root element
         */
        var registerSelector = function(selector) {
            customEvents.define(selector, [customEvents.events.activate]);
            selector.on(customEvents.events.activate, SELECTORS.DESCENDANTS, function(e, data) {
                var option = $(e.target);
                var value = option.attr(SELECTORS.VALUE);
                if (option.hasClass('active')) {
                    return;
                }
                if (preferences.length > 0) {
                    var type = selector.attr(SELECTORS.PREFERENCE);
                    userPreferences.update({
                        preferences: [
                            {
                                type: type,
                                value: value
                            }
                        ]
                    });
                }
                data.originalEvent.preventDefault();
            });
        };

        /**
         *
         */
        var init = function(selector, preferenceName) {
            log.debug('Initialising filter...');
            selector = $(selector);
            registerSelector(selector);
            if (preferenceName !== undefined) {
                selector.attr(SELECTORS.PREFERENCE, preferenceName);
                preferences.push(preferenceName);
            }
        };

        /**
         *
         */
        return {
            init: init
        };
});
