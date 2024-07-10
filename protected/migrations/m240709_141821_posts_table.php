<?php

class m240709_141821_posts_table extends CDbMigration
{
	// public function up()
	// {
	// 	$this->createTable('posts',['id'=>'pk',
	// 	'title'=>'string Not Null',
	// 	'content'=>'text',
	// 	'category'=>'string',
	// 	'tags'=>'string',
	// 	'created_at'=>'datetime'
	// ]);
	// }
	public function up()
    {
        $this->createTable('posts', array(
            'id' => 'pk',
            'user_id' => 'int NOT NULL',
            'title' => 'string NOT NULL',
            'content' => 'text NOT NULL',
            'is_public' => 'boolean NOT NULL DEFAULT 0',
            'created_at' => 'datetime NOT NULL',
            'updated_at' => 'datetime NOT NULL',
        ));

        $this->addForeignKey('fk_blog_post_user', 'posts', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

	public function down()
	{
		// echo "m240709_141821_posts_table does not support migration down.\n";
		// return false;
		$this->dropForeignKey('fk_blog_post_user', 'posts');
        $this->dropTable('posts');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}