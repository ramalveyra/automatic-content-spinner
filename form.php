<?php if ( ! defined( 'ABSPATH' ) ) exit('No direct script access allowed'); // Exit if accessed directly ?>
<div class="wrap">
    <?php echo '<h2>' . __( 'Automatic Content Spinner' ) . '</h2>';?>  
    <?php 
    $options = get_option('cs_options');
    $spinmethod = Cs_Constants::SPIN_METHOD;
    $spinpost = Cs_Constants::SPIN_POST;
    $opening_construct = Cs_Constants::OPENING_CONSTRUCT;
    $closing_construct = Cs_Constants::CLOSING_CONSTRUCT;
    $separator = Cs_Constants::SEPARATOR;
    $spinoption = Cs_Constants::SPIN_OPTION;
    $spin_titles = Cs_Constants::SPIN_TITLES;

    if (!empty($options)){
        $spinmethod = $options['spinmethod'];
        $spinpost = $options['spinpost'];
        $opening_construct = $options['opening_construct'];
        $closing_construct = $options['closing_construct'];
        $separator = $options['separator'];
        $spinoption = $options['spinoption'];

        # added this check since this is a new field, for existing installs, this will cause unwanted error/notice since the db doesn't have this record yet
        # this can be removed
        if (isset($options['spin_titles'])) 
        $spin_titles = $options['spin_titles'];
    }
    
    $spinmethods = array(
        'domainpage' => 'domain page (default)',
        'every second' => 'every second',
        'every minute' => 'every minute',
        'hourly' => 'hourly',
        'daily' => 'daily',
        'weekly' => 'weekly',
        'monthly' => 'monthly',
        'annually' => 'annually',
        'false' => 'always spin'
    );

    ?>
    <form id="cs_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" class="validate">
        <input type="hidden" name="cs_hidden" value="Y"/>  
        <table class="form-table">
            <tbody>

            <tr valign="top">
                <th scope="row"><label for="spinmethod"><?php _e('Spin Method: ' ); ?></label></th>
                <td>
                <select id="spinmethod" name="spinmethod"  style="width: 25em">
                    <?php foreach (Cs_Constants::$spinmethods as $spinid => $spinvalue) :?>
                        <?php if ($spinmethod == $spinid) $selected = 'selected="selected"'; else $selected = '';?>
                        <option value="<?php echo $spinid?>" <?php echo $selected;?>><?php _e(ucwords($spinvalue));?></option>
                    <?php endforeach;?>
                </select>
                <p class="description">Specify the spin method, such as domainpage, always spin, every minute, etc..</p>
                </td>
            </tr>
            </tr>

            <tr>
            <th scope="row"><label for="spinpost"><?php _e('Spin on: ' ); ?></label></th>
            <td>
                <select id="spinpost" name="spinpost"  style="width: 25em">
                    <?php foreach (Cs_Constants::$spinposts as $spinpostid => $spinpostvalue) :?>
                    	<?php if ($spinpost == $spinpostid) $selected = 'selected="selected"'; else $selected = '';?>
                        <option value="<?php echo $spinpostid?>" <?php echo $selected;?>><?php _e(ucwords($spinpostvalue));?></option>
                    <?php endforeach;?>
                </select>
                <p class="description">Specify which page to spin contents</p>
			</td>
            </tr>
            
            <tr>
            <th scope="row"><label for="opening_construct"><?php _e('Spin Tags: ' ); ?></label></th>
            <td>
                <select id="opening_construct" name="opening_construct"  style="width: 25em">
                    <?php foreach (Cs_Constants::$spintags['opening_construct'] as $opening_constructv) :?>
                    	<?php if ($opening_construct == $opening_constructv) $selected = 'selected="selected"'; else $selected = '';?>
                        <option value="<?php echo $opening_constructv?>" <?php echo $selected;?>><?php echo $opening_constructv;?></option>
                    <?php endforeach;?>
                </select>
                <p class="description">Specify the opening construct to use</p>
                <select id="closing_construct" name="closing_construct"  style="width: 25em">
                    <?php foreach (Cs_Constants::$spintags['closing_construct'] as $closing_constructv) :?>
                    	<?php if ($closing_construct == $closing_constructv) $selected = 'selected="selected"'; else $selected = '';?>
                        <option value="<?php echo $closing_constructv?>" <?php echo $selected;?>><?php echo $closing_constructv;?></option>
                    <?php endforeach;?>
                </select>
                <p class="description">Specify the closing construct to use</p>
                <select id="separator" name="separator"  style="width: 25em">
                    <?php foreach (Cs_Constants::$spintags['separator'] as $separatorv) :?>
                    	<?php if ($separator == $separatorv) $selected = 'selected="selected"'; else $selected = '';?>
                        <option value="<?php echo $separatorv?>" <?php echo $selected;?>><?php echo $separatorv;?></option>
                    <?php endforeach;?>
                </select>
                <p class="description">Specify the separator to use</p>
			</td>
            </tr>

            <tr>
            <th scope="row"><label for="spinoption"><?php _e('Spin Option: ' ); ?></label></th>
                <td>
                <select id="spinoption" name="spinoption"  style="width: 25em">
                    <?php foreach (Cs_Constants::$spinoptions as $spinoptionid => $spinoptionvalue) :?>
                    	<?php if ($spinoption == $spinoptionid) $selected = 'selected="selected"'; else $selected = '';?>
                        <option value="<?php echo $spinoptionid?>" <?php echo $selected;?>><?php _e(ucwords($spinoptionvalue));?></option>
                    <?php endforeach;?>
                </select>
                <p class="description">Specify the spin option to use, such as flat spin, nested spinning, or detect (default)</p>
                </td>
            </tr>
            <th scope="row"><label for="spin_titles"><?php _e('Spin Titles: ' ); ?></label></th>
                <td>
                    <?php if ($spin_titles) $checked = 'checked="checked"'; else $checked = '';?>
                    <label for="spin_titles"><input type="checkbox" value="1" id="spin_titles" <?php echo $checked;?> name="spin_titles"></label>
                    <p class="description">Specify if allowed to spin titles (depends on the `Spin on` option)</p>
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>
    </form>
</div>