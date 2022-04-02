<?php
defined('_JEXEC') or die;
/**
 * Usage:
 *
 * Create an override for the view that correspond with the pages you will be using this script on, instantiate JApplication
 * if it already isn't, and add the slug to the application data:
 * $app->set('slug', $this->item->slug);
 *
 * If slug isn't defined for a view, you can use the ID and alias to create one:
 * $app->set('slug', $this->item->id . ':' . $this->item->alias);
 */

$app              = JFactory::getApplication();
$jinput           = $app->input;
$disqus_shortname = '';
$jid              = $jinput->get('id', 0, 'int');
$jcatid           = $jinput->get('catid', 0, 'int');
$jitemid          = $jinput->get('itemid', 0, 'int');
$jlayout          = $jinput->get('layout', '', 'string');
$joption          = $jinput->get('option', '', 'string');
$jview            = $jinput->get('view', '', 'string');
$jclass           = str_ireplace('com_', '', $joption);
$component        = ucwords($jclass);
$helper_class     = ucwords($jclass) . 'HelperRoute';
$route_class      = 'get' . ucwords($jview) . 'Route';

if((bool) preg_match('/categor(y|(ies))/', $jview) !== true)
{
	require_once(JPATH_BASE . '/components/' . $joption . '/helpers/route.php');
	$uri   = JUri::getInstance();
	$uriId = (preg_match('/categor(y|(ies))/', $jview) === true) ? $jcatid : $jid;
	// $link  = JRoute::_('index.php?option='.$joption.'&view='.$jview.'&layout='.$jlayout.($uriId > 0 ? '&id='.$uriId : ''));
	preg_match('/(?!\/*\w*\/+)[\w\-]*(?=(\.html))/', $uri->getPath(), $matches);
	$slug  = $app->get('slug', '');
	$link  = JRoute::_($helper_class::$route_class($slug, $jcatid));
	$canon = $uri->getScheme() . '://' . $uri->getHost() . $link;
	?>
	<h3><a class="comments-link" id="show-comments">Comments <i class="fa fa-link"></i></a></h3>
	<div id="disqus_thread"></div>
	<script>
		let commentBtn = document.getElementById('show-comments');
		var disqus_loaded = false,
				disqus_shortname = <?php echo json_encode($disqus_shortname); ?>,
				disqus_config = function () {
				this.page.url = <?php echo json_encode($canon); ?>;
				this.page.identifier = <?php echo json_encode($joption.':'.$slug); ?>;
		};

		commentBtn.addEventListener('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			loadDisqus();

			/* if(typeof rightHeight !== 'undefined') {
				let container = document.querySelector('.copyarea > div.row');

				setTimeout(function() {
					rightHeight.adjustContainerHeight(container);
					console.log(rightHeight);
				}, 1000);
			} */
		})

		/**
		*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
		*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
		function loadDisqus() {
			if(disqus_loaded === true) {
				console.info('Disqus already loaded');
			} else {
				disqus_loaded = true;

				var d = document, s = d.createElement('script');
				s.src = 'https://'+disqus_shortname+'.disqus.com/embed.js';
				s.setAttribute('data-timestamp', +new Date());
				(d.head || d.body).appendChild(s);
			}
		}
	</script>
	<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
	<?php
}
