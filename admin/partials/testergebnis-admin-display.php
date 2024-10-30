<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.krankenkasseninfo.de
 * @since      1.0.0
 *
 * @package    Testergebnis
 * @subpackage Testergebnis/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
<p>
    Shortcode: [Testergebnisse]
</p>
<form method="post" name="testergebnisse" action="options.php">

	<?php
	$options = get_option($this->plugin_name);
	//print_r($options);
	$Option = $options['Option'];
	$Testergebnis_Slug = $options['Testergebnis-Slug'];
	$Testergebnis_Site_Title = $options['Testergebnis-Site-Title'];
	$CSS_Version = $options['CSS-Version'];
    $Sterne = $options['Testergebnis-Sterne'];
    $Informationen = $options['Testergebnis-Informationen'];
    $sterne_ja = '';
    $sterne_nein = '';
    if(abs($Sterne) === 0) {
        $sterne_nein = 'checked';
    } else {
        $sterne_ja = 'checked';
    }

	$infos_ja = '';
	$infos_nein = '';
	if(abs($Informationen) === 0) {
		$infos_nein = 'checked';
	} else {
		$infos_ja = 'checked';
	}


	settings_fields($this->plugin_name); ?>

    <!-- This file should primarily consist of HTML with a little bit of PHP. -->
    <div class="wrap">



        <fieldset>
            <p>Ihre Testkategorie</p>

            <legend class="screen-reader-text"><span>Ihre Testkategorie:</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-test-category">
                <select name="<?php echo $this->plugin_name; ?>[Option]" id="<?php echo $this->plugin_name; ?>-test-category">
		            <?php echo KKTE_getCategories($Option); ?>
                </select>
            </label>
        </fieldset>

        <fieldset>
            <p>Ihr Slug</p>

            <legend class="screen-reader-text"><span>Ihr Slug:</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-testergebnis-slug">
                <input type="text" id="<?php echo $this->plugin_name; ?>-testergebnis-slug" name="<?php echo $this->plugin_name; ?>[Testergebnis-Slug]" value="<?php if(!empty($Testergebnis_Slug)) echo $Testergebnis_Slug;?>" />
            </label>
        </fieldset>


        <fieldset>
            <p>Ihr Seitentitel</p>

            <legend class="screen-reader-text"><span>Ihr Seitentitel:</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-testergebnis-site-title">
                <input type="text" id="<?php echo $this->plugin_name; ?>-testergebnis-site-title" name="<?php echo $this->plugin_name; ?>[Testergebnis-Site-Title]" value="<?php if(!empty($Testergebnis_Site_Title)) echo $Testergebnis_Site_Title;?>" />
            </label>
        </fieldset>

        <fieldset>
            <p>Sterne anzeigen</p>

            <legend class="screen-reader-text"><span>Sterne anzeigen:</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-testergebnis-sterne">
                <input type="radio" id="<?php echo $this->plugin_name; ?>-testergebnis-sterne" name="<?php echo $this->plugin_name; ?>[Testergebnis-Sterne]" value="1" <?php echo $sterne_ja; ?> /> Ja
                <input type="radio" id="<?php echo $this->plugin_name; ?>-testergebnis-sterne" name="<?php echo $this->plugin_name; ?>[Testergebnis-Sterne]" value="0" <?php echo $sterne_nein; ?> /> Nein
            </label>
        </fieldset>

        <fieldset>
            <p>Informationenbutton anzeigen</p>

            <legend class="screen-reader-text"><span>Informationenbutton anzeigen:</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-testergebnis-infos">
                <input type="radio" id="<?php echo $this->plugin_name; ?>-testergebnis-infos" name="<?php echo $this->plugin_name; ?>[Testergebnis-Informationen]" value="1" <?php echo $infos_ja; ?> /> Ja
                <input type="radio" id="<?php echo $this->plugin_name; ?>-testergebnis-infos" name="<?php echo $this->plugin_name; ?>[Testergebnis-Informationen]" value="0" <?php echo $infos_nein; ?> /> Nein
            </label>
        </fieldset>

        <fieldset>
            <p>Verwendetes CSS</p>

            <legend class="screen-reader-text"><span>CSS benutzen:</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-css-version">
                <select name="<?php echo $this->plugin_name; ?>[CSS-Version]" id="<?php echo $this->plugin_name; ?>-css-version">
                    <?php echo KKTE_getCSSOptions($CSS_Version); ?>
                </select>
            </label>
        </fieldset>

		<?php submit_button('Speichern', 'primary','submit', TRUE); ?>

</form>

</div>

<?php
function KKTE_getCSSOptions($CSS_Version) {
    $content = '';
    if($CSS_Version === '') {
        $CSS_Version = 'ownerCSS';
    }
    $cssArray = array('bootstrap3' => 'Bootstrap 3', 'bootstrap4' => "Bootstrap4", 'ownerCSS' => "Standard-CSS");

    foreach($cssArray as $key => $value) {
        $selected = '';
        if($key === $CSS_Version) {
            $selected = 'selected="selected"';
        }
        $content.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
    }
    return $content;
}

function KKTE_getCategories($Option) {
	$slug = $Option;

	// jSON URL which should be requested
	$json_url = 'https://cdn.krankenkasseninfo.de/wp_api/index.php?overview='. $slug;

	$response = wp_remote_get($json_url);
	$result = wp_remote_retrieve_body($response);

	$result = json_decode($result, true);
	$content = '';
	foreach($result as $key => $value) {
	    $selected = '';
	    if(trim($key) === trim($slug)) {
	        $selected = 'selected="selected"';
        }
	    $content.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
    }
    return $content;
}

?>