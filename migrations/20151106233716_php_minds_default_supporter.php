<?php

use Phinx\Migration\AbstractMigration;

class PhpMindsDefaultSupporter extends AbstractMigration
{
    public function up()
    {
        $sql = 'INSERT INTO supporters (name, url, twitter, email) VALUE("PHPMinds Organiser", "http://phpminds.org", "phpminds", "phpminds.org@gmail.com")';
        $this->execute($sql);
    }
}
