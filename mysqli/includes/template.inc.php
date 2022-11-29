<?php
        class Template extends Dom {
public $html = '<!DOCTYPE html>';
public $stylesheet = '<link rel="stylesheet" href ="style.css">';
###################################################################
# this class is for html template
##########################################################################
public function display_page($title, $head, $body){
    $title = $this->tag_wrap('title', $title);
    $title .= $this->stylesheet;
    $title .= $head;
    $head = $this->tag_wrap('head', $title);
    $head .= $this->tag_wrap('body', $body);
    $this->html .= $this->tag_wrap('html', $head);
    echo $this->html;
}
##########################################################
        }



?>
