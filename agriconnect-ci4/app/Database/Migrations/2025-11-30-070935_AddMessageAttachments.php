<?php

use CodeIgniter\Database\Migration;

class AddMessageAttachments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'message_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'file_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'file_size' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('message_id');
        $this->forge->createTable('message_attachments');

        // Add foreign key constraint
        try {
            $this->db->query('ALTER TABLE message_attachments ADD CONSTRAINT message_attachments_message_fk FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Ignore if DB doesn't support adding constraints here
        }
    }

    public function down()
    {
        $this->forge->dropTable('message_attachments', true);
    }
}
