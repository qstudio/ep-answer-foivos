<?php

// Ref: https://www.macarthur.me/posts/simpler-unit-testing-for-wordpress
class WPTest extends \PHPUnit\Framework\TestCase
{
    public $testPostId;

    protected function setUp(): void
    {
        $this->testPostId = wp_insert_post([
            'post_title' => 'Sample Post',
            'post_content' => 'This is just some sample post content.'
        ]);
    }
    protected function tearDown(): void
    {
        wp_delete_post($this->testPostId, true);
    }

    public function test_shouldWork()
    {
        $post = get_post($this->testPostId);

        // var_dump($GLOBALS['wp_filter']);

        // global $wp_filter;
        // print_r($wp_filter['show_user_profile']->callbacks);

        $this->assertEquals('Sample Post', $post->post_title);
    }
}
