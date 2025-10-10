<?php
namespace Mahedi\UltimateFaqSolution\ExportImport\Base;

use Mahedi\UltimateFaqSolution\ExportImport\Contracts\ImporterInterface;

abstract class BaseImporter implements ImporterInterface {
    protected string $type = '';

    public function getType(): string {
        return $this->type;
    }

    /**
     * Insert/Update post
     * @return int new post ID
     */
    protected function insertPost( array $args ): int {
        $default = [
            'post_type' => $args['post_type'] ?? 'post',
            'post_title' => $args['title'] ?? '',
            'post_content' => $args['content'] ?? '',
            'post_status' => $args['status'] ?? 'publish',
        ];
        $postarr = [
            'post_type' => $default['post_type'],
            'post_title' => $default['post_title'],
            'post_content' => $default['post_content'],
            'post_status' => $default['post_status'],
        ];
        return (int) wp_insert_post( $postarr, true );
    }
}
