<?php

namespace VE\Electro\WordPress;

use VE\Electro\Support\Str;

abstract class Model
{
    use Traits\HasRelations;
    use Traits\HasMeta;

    protected static $instances = [];

    protected $translatable = false;

    protected $attributes = [];

    protected $exists = false;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     */
    public function fill(array $attributes)
    {
        foreach($attributes as $key => $value) {
            if ($this->isWpAttribute($key)) {
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }

    public function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($key)
    {
        if (! $key) {
            return;
        }

        if (array_key_exists($key, $this->attributes)) {
            return $this->getAttributeValue($key);
        }

        return $this->getRelationValue($key);
    }

    public function getAttributeValue($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function isWpAttribute($key)
    {
        return in_array($key, [
            'ID',
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_content',
            'post_content_filtered',
            'post_title',
            'post_excerpt',
            'post_status',
            'post_type',
            'comment_status',
            'ping_status',
            'post_password',
            'post_name',
            'to_ping',
            'pinged',
            'post_modified',
            'post_modified_gmt',
            'post_parent',
            'menu_order',
            'guid',
            'post_category',
            'tags_input',
            'tax_input',
            'meta_input',
            'post_mime_type',
            'comment_count',
        ]);
    }

    public static function make()
    {
        $class = static::class;
        if (empty(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }
        return static::$instances[$class];
    }

    public function register()
    {
        add_action('init', [$this, 'registerPostType']);

        if ($this->translatable) {
            add_filter('pll_get_post_types', function($post_types, $is_settings ) {
                if ( $is_settings ) {
                    unset( $post_types[$this->post_type] );
                } else {
                    $post_types[$this->post_type] = $this->post_type;
                }
                return $post_types;
            }, 10, 2);
        }
    }

    public function registerPostType()
    {
        $labels = $this->labels;

        $args = [
            'labels' => $labels,
            'show_ui' => $this->show_ui,
            'supports' => $this->supports,
        ];

        register_post_type($this->post_type, $args);
    }

    /**
     * Query models by using WP_Query
     *
     * @param array $args
     */
    protected function query(array $args = [])
    {
        if ( isset($args['post_title']) ) {
            $args['title'] = $args['post_title'];
        }

        $args['post_type'] = $this->post_type;

        $query = new \WP_Query($args);
        $items = $query->posts;

        $items = array_map(function($item) {
            $format = (array) $item;
            $model = new static($format);
            $model->exists = true;
            return $model;
        }, $items);

        return collect($items);
    }

    protected function all(array $args = [])
    {
        $args['posts_per_page'] = 500;
        $args['post_status'] = 'any';
        return $this->query($args);
    }

    protected function save()
    {
        $attrs = $this->getAttributes();

        $postArray = [
            'post_type' => $this->post_type,
            'meta_input' => [],
        ];

        foreach($attrs as $key => $value) {
            if ($this->isWpAttribute($key)) {
                $postArray[$key] = $value;
            }
        }

        if ($this->exists) {
            unset($postArray['post_modified']);
            unset($postArray['post_modified_gmt']);
            return wp_update_post($postArray, true);
        }

        return wp_insert_post($postArray, true);
    }

    protected function update(array $attributes = [])
    {
        return $this->fill($attributes)->save();
    }

    protected function trash()
    {
        return wp_trash_post($this->ID);
    }

    protected function delete()
    {
        return wp_delete_post($this->ID, true);
    }

    protected function create($attributes)
    {
        $model = new static($attributes);
        return $model->save();
    }

    protected function updateOrCreate($attributes)
    {
        $attributesQuery = wp_parse_args([
            'post_status' => ['any', 'trash'],
            'meta_input' => [],
        ], $attributes);

        $model = static::query($attributesQuery)->first();

        if ($model && $model->exists) {
            return $model->update($attributes);
        }

        $model = new static($attributes);

        return $model->save();
    }

    public function __call($method, $parameters)
    {
        if (method_exists($this, $method)) {
            return $this->{$method}(...$parameters);
        }

        $key = Str::replaceFirst('get', '', $method);
        $key = Str::snake($key);

        if ($this->isWpAttribute($key)) {
            return $this->wp_attributes[$key] ?? null;
        }

        throw new \BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
    }

    protected function postType()
    {
        return $this->post_type;
    }

    public static function __callStatic($method, $parameters)
    {
        return self::make()->$method(...$parameters);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }
}
