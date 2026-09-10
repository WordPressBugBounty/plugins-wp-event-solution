<?php

/**
 * Static Template Adapter
 *
 * Adapts static template array data to work with prepare_item_for_response
 *
 * @package Eventin
 */
namespace Eventin\Template;

defined( 'ABSPATH' ) || exit;

class StaticTemplate {
    /**
     * Template data
     *
     * @var array
     */
    private $data;

    /**
     * Whether this static template has already been added to the site.
     *
     * Declared explicitly so that assigning it in TemplateController does not
     * create a dynamic property (deprecated in PHP 8.2+). The class has a
     * __get() but no __set(), so the assignment used to fall through to a
     * dynamic property.
     *
     * @var bool
     */
    public $is_added = false;

    /**
     * Constructor
     *
     * @param array $data Static template data array
     */
    public function __construct( array $data ) {
        $this->data = $data;
    }

    /**
     * Magic getter for direct property access
     *
     * @param string $name Property name
     * @return mixed
     */
    public function __get( $name ) {
        return $this->data[$name] ?? null;
    }

    /**
     * Get template ID
     *
     * @return string
     */
    public function get_id() {
        return $this->data['id'] ?? '';
    }

    /**
     * Get template name
     *
     * @return string
     */
    public function get_name() {
        return $this->data['name'] ?? '';
    }

    /**
     * Get template status
     *
     * @return string
     */
    public function get_status() {
        return $this->data['status'] ?? 'default';
    }

    /**
     * Get template type
     *
     * @return string
     */
    public function get_type() {
        return $this->data['type'] ?? '';
    }

    /**
     * Get template orientation
     *
     * @return string
     */
    public function get_orientation() {
        return $this->data['orientation'] ?? '';
    }

    /**
     * Get template content
     *
     * @return string
     */
    public function get_content() {
        return $this->data['content'] ?? '';
    }

    /**
     * Get template builder
     *
     * @return string
     */
    public function get_template_builder() {
        return $this->data['template_builder'] ?? '';
    }

    /**
     * Check if this is a static template
     *
     * @return bool
     */
    public function is_static() {
        return true;
    }
}