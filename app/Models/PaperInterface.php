<?php
namespace App\Models;

interface PaperInterface{
    const TABLE_NAME = 'papers';
    
    const ATTR_ID = 'id';
    const ATTR_TITLE = 'title';
    const ATTR_URL_ALIAS = 'url_alias';
    const ATTR_SHORT_CONTENT = 'short_conten';
    const ATTR_ACTIVE = 'active';
    const ATTR_SHOW = 'show';
    const ATTR_SHOW_TIME = 'show_time';
    const ATTR_AUTO_HIDE = 'auto_hide';
    const ATTR_IMAGE_PATH = 'image_path';
    const ATTR_WRITER = 'writer';
    const ATTR_SHOW_WRITER = 'show_writer';
}

?>