<?php

namespace VE\Electro\WordPress;

use VE\Electro\Support\Str;

class Model
{
    use Concerns\HasRelations;

    protected static $instances = [];

    protected $attributes = [];
    protected $wp_attributes = [];

    protected $fillable = [];

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
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
            // $key = Str::lower($key);
            if ( in_array($key, $this->wp_attributes()) ) {
                $this->wp_attributes[$key] = $value;
            }
        }

        return $this;
    }

    public function isFillable($key)
    {
        if (in_array($key, $this->getFillable())) {
            return true;
        }

        return empty($this->getFillable()) &&
            ! Str::startsWith($key, '_');
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function setAttribute($key, $value) {
        if ($this->hasSetMutator($key)) {
            return $this->setMutatedAttributeValue($key, $value);
        }

        $this->attributes[$key] = $value;
    }

    protected function setMutatedAttributeValue($key, $value)
    {
        return $this->{'set'.Str::studly($key).'Attribute'}($value);
    }

    public function hasSetMutator($key)
    {
        return method_exists($this, 'set'.Str::studly($key).'Attribute');
    }

    protected function setMetaAttribute($values)
    {
        foreach($values as $key => $value) {
            $value = maybe_unserialize($value[0]);
            $this->setAttribute($key, $value);
        }
    }

    public function hasGetMutator($key)
    {
        return method_exists($this, 'get'.Str::studly($key).'Attribute');
    }

    protected function mutateAttribute($key, $value)
    {
        return $this->{'get'.Str::studly($key).'Attribute'}($value);
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

        if (array_key_exists($key, $this->attributes) ||
            $this->hasGetMutator($key)) {
            return $this->getAttributeValue($key);
        }

        return $this->getRelationValue($key);
    }

    public function getAttributeValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the model to a form that is more useful for usage.
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        return $value;
    }

    protected function getAttributeFromArray($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function wp_attributes()
    {
        return [
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
        ];
    }

    public static function make()
    {
        $class = static::class;
        if (empty(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }
        return static::$instances[$class];
    }

    protected function register()
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
     * @return VE\Electro\Support\Collection
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
            if ( in_array($key, $this->wp_attributes()) ) {
                $postArray[$key] = $value;
            }
        }

        foreach($this->meta as $key => $value) {
            $postArray['meta_input'][$key] = $value;
        }

        if ( $this->exists ) {
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

        if ( $model && $model->exists ) {
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

        if ( in_array($key, $this->wp_attributes()) ) {
            return $this->wp_attributes[$key] ?? null;
        }

        throw new \BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
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
