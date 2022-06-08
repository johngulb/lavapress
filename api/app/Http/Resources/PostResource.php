<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{

    protected $wp_post;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->wp_post = get_post($this->ID);
        $GLOBALS['post'] = $this->wp_post;

        // $data = parent::toArray($request);

        $parser = new \WP_Block_Parser();

        $data = [
            'id'     => $this->ID,
            'title'  => $this->post_title,
            'slug'   => $this->post_name,
            'url'    => get_permalink($this->wp_post),
            'meta'   => $this->meta(),
            'blocks' => $parser->parse($this->post_content),
        ];
        return $data;
    }

    function meta()
    {
        $yoast = get_post_meta($this->wp_post->ID, '_yoast_wpseo_title', true);
        $yoast_val = \wpseo_replace_vars($yoast, $this->wp_post);
        $title = apply_filters('wpseo_title', $yoast_val, $this->wp_post);

        $yoast_desc = get_post_meta($this->wp_post->ID, '_yoast_wpseo_metadesc', true);
        $metadesc_val = \wpseo_replace_vars($yoast_desc, $this->wp_post);
        $description = apply_filters('wpseo_metadesc', $metadesc_val, $this);

        return [
            'title'            => $title,
            'description'      => $description,
            'image'            => $this->image,
            'author'           => $this->author->display_name,
            'authorSlug'       => $this->author->user_nicename,
            'postId'           => $this->ID,
            'publishedDate'    => $this->post_date,
            'modifiedDate'     => $this->post_modified,
        ];
    }

}
