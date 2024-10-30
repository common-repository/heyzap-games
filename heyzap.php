<?php
/*
Plugin Name: Heyzap Games
Plugin URI: http://www.heyzap.com
Description: Put games on your blog
Version: 1.0
Author: Heyzap.com
Author URI: http://www.heyzap.com
*/
/*  Copyright 2009  Heyzap  (email : info@heyzap.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!function_exists('heyzap_init')){
  function heyzap_init(){

    function heyzap_link($blog_name, $embed_key) {
      $heyzap_link = "
        <script type=\"text/javascript\" src=\"http://heyzap.com/javascripts/embed.external.js\"></script>
        <style> @import 'http://heyzap.com/elightbox/lightbox.external.css'; </style>
        <script type=\"text/javascript\">
          var heyzap_link = new Heyzap('heyzap_games', {\"embed_key\": \"$embed_key\"});
        </script>
        <a onclick=\"heyzap_link.showGamesPopup();return false;\" target=\"_blank\" href=\"http://www.heyzap.com\">Play games on $blog_name</a>
        ";
      return $heyzap_link;
    }


    function add_heyzap_to_post($content) {
        $content = $content."<p>".heyzap_link(get_bloginfo('name'), trim(get_option('heyzap_embed_key'))) ."</p>";
        return $content;
    }

    function add_heyzap_to_sidebar($args) {
      $embed_key = trim(get_option('heyzap_embed_key'));
      $heyzap_mini = "
       <div id=\"heyzap_games\" style=\"\"></div>
      <script type=\"text/javascript\" src=\"http://www.heyzap.com/javascripts/embed.external.js\"></script>
      <style> @import 'http://www.heyzap.com/elightbox/lightbox.external.css'; </style>
      <script type=\"text/javascript\">
        var heyzap = new Heyzap('heyzap_games', {\"embed_key\": \"$embed_key\"});
        heyzap.renderMini();
      </script>
      <a target=\"_blank\" href=\"http://www.heyzap.com\">heyzap.com - embed games</a>
      ";
        extract($args);
    ?>
            <?php echo $before_widget; ?>
            <?php echo $before_title .
                'Heyzap Games' .
             $after_title; ?>
            <?php echo $heyzap_mini; ?>
            <?php echo $after_widget; ?>
    <?php
    }

    function heyzap_menu() {
        add_options_page('Heyzap Options', 'Heyzap Games', 4, __FILE__, 'heyzap_plugin_options');
    }

    function heyzap_plugin_options() {
        $embed_key = get_option('heyzap_embed_key');
        if(!$embed_key || $embed_key.length == 0){
          $embed_key = "Insert embed key here";
        }
        echo '<div class="wrap">';
        echo '<h2>Heyzap games</h2>';
        echo "<p>Next step: <a href='widgets.php'>go and add sidebar widgets</a>.</p>";
        echo '<form method="post" action="options.php">';
        echo wp_nonce_field('update-options');
        echo '<table class="form-table">';
        echo '<tr valign="top">';
        echo '<th scope="row">Site embed key</th>';
        echo '<td><input type="text" name="heyzap_embed_key" value=" ' . $embed_key . '" /><br />Site embed code (create a site and copy from <a href="heyzap.com/publishers/account">publisher dashboard</a></td>';
        echo '</tr>';
        echo '</table>';
        echo '<tr valign="top">';
        echo '<td><input type="checkbox" id="heyzap_end_blog" onclick="document.getElementById(\'heyzap_end_blog_hidden\').value = this.checked ? \'yes\' : \'no\'" name="heyzap_end_blog_check" value="yes"';
        echo ((get_option('heyzap_end_blog') && get_option('heyzap_end_blog') == "yes") ? 'checked' : '');
        echo ' /><label for="heyzap_end_blog">Add Heyzap games at the end of posts</label></td>';
        echo '</tr>';
        echo '<input type="hidden" name="heyzap_end_blog" id="heyzap_end_blog_hidden" value="'.((get_option('heyzap_end_blog') && get_option('heyzap_end_blog') == "yes") ? 'yes' : 'no').'" />';
        echo '<input type="hidden" name="action" value="update" />';
        echo '<input type="hidden" name="page_options" value="heyzap_embed_key,heyzap_end_blog" />';
        echo '<p class="submit">';
        echo '<input class="button-primary" type="submit" name="Submit" value="Save Changes" />';
        echo '</p>';
        echo "<p>Next step: <a href='widgets.php'>go and add sidebar widgets</a>.</p>";
        echo '</form>';

        echo '</div>';
    }


    add_action('admin_menu', 'heyzap_menu');
    if(get_option('heyzap_end_blog') == "yes"){
      add_filter('the_content', 'add_heyzap_to_post');
    }
    register_sidebar_widget("Heyzap Games", "add_heyzap_to_sidebar");
  }
}

add_action('widgets_init', 'heyzap_init');
?>
