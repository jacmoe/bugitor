<?php

/**
 * CakePHP Gravatar Helper
 *
 * A CakePHP View Helper for the display of Gravatar images (http://www.gravatar.com)
 *
 * @copyright Copyright 2009, Graham Weldon
 * @version 1.2
 * @author Graham Weldon <graham@grahamweldon.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class Gravatar extends CComponent{

/**
 * Gravatar avatar image base URL
 *
 * @var string
 * @access private
 */
    private $__url = 'http://www.gravatar.com/avatar/';

/**
 * Hash type to use for email addresses
 *
 * @var string
 * @access private
 */
    private $__hashType = 'md5';

/**
 * Collection of allowed ratings
 *
 * @var array
 * @access private
 */
    private $__allowedRatings = array('g', 'pg', 'r', 'x');

/**
 * Default Icon sets
 *
 * @var array
 * @access private
 */
    private $__defaultIcons = array('none', 'identicon', 'monsterid', 'wavatar');

/**
 * Default settings
 *
 * @var array
 * @access private
 */
    private $__default = array('default' => 'identicon', 'size' => null, 'class' => 'gravatar', 'rating' => null, 'ext' => false);

    public function init(){
    }
/**
 * Show gravatar for the supplied email address
 *
 * @param string $email Email address
 * @param array $options Array of options, keyed from default settings
 * @return string Gravatar image string
 * @access public
 */
    public function image($email, $options = array()) {
        $options = $this->__cleanOptions(array_merge($this->__default, $options));

        $imageUrl = $this->url($email, $options);

        unset($options['default'], $options['size'], $options['rating'], $options['ext']);
        return '<img src="'.$imageUrl . '"/>';//, $options;
    }

/**
 * Generate image URL
 *
 * @param string $email Email address
 * @param string $options Array of options, keyed from default settings
 * @return string Gravatar Image URL
 * @access public
 */
    public function url($email, $options = array()) {
        $ext = $options['ext'];
        unset($options['ext']);

        $imageUrl = $this->__url . $this->__emailHash($email, $this->__hashType);
        if ($ext === true) {
            // If 'ext' option is supplied and true, append an extension to the generated image URL.
            // This helps systems that don't display images unless they have a specific image extension on the URL.
            $imageUrl .= '.jpg';
        }
        $imageUrl .= $this->__buildOptions($options);
        return $imageUrl;
    }

/**
 * Generate an array of default images for preview purposes
 *
 * @param array $options Array of options, keyed from default settings
 * @return array Default images array
 * @access public
 */
    public function defaultImages($options = array()) {
        $options = $this->__cleanOptions(array_merge($this->__default, $options));
        $images = array();
        foreach ($this->__defaultIcons as $defaultIcon) {
            $options['default'] = $defaultIcon;
            $images[$defaultIcon] = $this->image(null, $options);
        }
        return $images;
    }

/**
 * Sanitize the options array
 *
 * @param array $options Array of options, keyed from default settings
 * @return array Clean options array
 * @access private
 */
    private function __cleanOptions($options) {
        if (!isset($options['size']) || empty($options['size']) || !is_numeric($options['size'])) {
            unset($options['size']);
        } else {
            $options['size'] = min(max($options['size'], 1), 512);
        }

        if (!$options['rating'] || !in_array(mb_strtolower($options['rating']), $this->__allowedRatings)) {
            unset($options['rating']);
        }

        if (!$options['default']) {
            unset($options['default']);
        } else {
            if (!in_array($options['default'], $this->__defaultIcons) && !Validation::url($options['default'])) {
                unset($options['default']);
            }
        }
        return $options;
    }

/**
 * Generate email address hash
 *
 * @param string $email Email address
 * @param string $type Hash type to employ
 * @return string Email address hash
 * @access private
 */
    private function __emailHash($email, $type) {
        return md5(mb_strtolower($email), $type);
    }

/**
 * Build Options URL string
 *
 * @param array $options Array of options, keyed from default settings
 * @return string URL string of options
 * @access private
 */
    private function __buildOptions($options = array()) {
        if (!empty($options)) {
            $optionArray = array();
            foreach ($options as $k => $v) {
                if ($v == 'default' || $v == 'none') {
                    continue;
                }
                $optionArray[] = $k . '=' . mb_strtolower($v);
            }
            return '?' . implode('&amp;', $optionArray);
        }
        return '';
    }
}
