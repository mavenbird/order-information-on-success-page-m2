/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_OrderInformation
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */
define([
    "jquery"
], function ($) {
    'use strict';
    
    return function() {
        $(document).ready(function () {
            try {
                if ($(".mavenbird-actions-toolbar").length) {
                    $(".mavenbird-actions-toolbar").addClass('button-continue');
                }
                if ($(".mavenbird-button-continue").length) {
                    $(".mavenbird-button-continue").removeClass('mavenbird-actions-toolbar');
                }
                $(".checkout-success .actions-toolbar").not(".mavenbird-actions-toolbar").css('display', 'block');
            } catch (e) {
                console.error('Error in Mavenbird OrderInformation continue.js: ' + e.message);
            }
        });
    };
});
