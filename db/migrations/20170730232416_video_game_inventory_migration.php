<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class VideoGameInventoryMigration extends AbstractMigration
{
  public function change()
  {
    if (! $this->hasTable('game')) {
      $table = $this->table('game', ['signed' => false, 'engine' => 'MyISAM']);
      $table
        ->addColumn('title', 'string', ['limit' => 128])
        ->addColumn('image', 'string', ['limit' => 128, 'null' => true])
        ->addColumn('system', 'string', ['limit' => 128])
        ->addColumn('genre', 'string', ['limit' => 128, 'null' => true])
        ->addColumn('developer', 'string', ['limit' => 256, 'null' => true])
        ->addColumn('description', 'text', ['null' => true])
        ->addColumn('released_on', 'date', ['null' => true])
        ->addColumn('notes', 'string', ['limit' => 256, 'null' => true])
        ->addColumn('completed', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => '0'])
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
        ->addIndex(['completed'], ['name' => 'video_game_inventory_idx_1'])
        ->addIndex(['system'], ['name' => 'video_game_inventory_idx_2'])
        ->addIndex(['created_at'], ['name' => 'video_game_inventory_idx_3'])
        ->addIndex(['released_on'], ['name' => 'video_game_inventory_idx_4'])
        ->addIndex(['title', 'system'], ['unique' => true, 'name' => 'video_game_inventory_uqk_1'])
        ->create();
      }
  }
}
