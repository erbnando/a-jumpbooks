<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>

<?php wp_footer(); ?>
<?php
$whitelist = array(
    '127.0.0.1',
    '::1'
);
if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)) { ?>
	<script id="__bs_script__">//<![CDATA[
    document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.2.13.0.js'><\/script>".replace("HOST", location.hostname));
//]]></script>
<?php } ?>

<!--<a id="test" href="#">test</a>-->

</body>
</html>