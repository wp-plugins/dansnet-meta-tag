<?php 
	
	class DANSNET_DMT_Settings 
	{
	
		/**
		 * Tag identifier used by file includes and selector attributes.
		 * @var string
		 */
		protected $tag = 'dansnet_meta_tags';
		
		/**
		 * Name des Plugins.
		 * @var string
		 */
		protected $name = 'Meta-Tags';
		
		/**
		 * Aktuelle Version des Plugins.
		 * @var string
		 */
		protected $version = '0.1';
		
		/**
		 * Position der Settings
		 * @var string
		 */
		protected $section = 'general';
		
		/**
		 * List of options to determine plugin behaviour.
		 * @var array
		 */
		protected $options = array();
		
		/**
		 * Settings f�r die Options-Page.
		 * @var array
		 */
		protected $settings = array(
			'description' => array(
				'description' => 'Description',
			),
			'keywords' => array(
				'description' => 'Keywords',
			),
			'author' => array(
				'description' => 'Author',
			),
			'robots' => array(
				'description' => 'Robots',
			),
		);
		
		private $posts;
	
		public function __construct()
		{
			if( is_admin() )
			{
				add_action( 'admin_head', array(&$this, 'load_scripts') );
				add_action( 'admin_init', array(&$this, 'add_settings') );
				if ( $options = get_option( $this->tag ) ) 
				{
					$this->options = $options;
				} 
				$this->posts = get_posts(array('post_type'=>'page', 'post_status'=>'any', 'posts_per_page'=>-1));
				//array_unshift($this->posts, get_post(get_option( 'page_on_front' )));
			}
			
		}
		
		public function load_meta_tags()
		{
			foreach( get_option( $this->tag ) as $option => $value )
			{
				$id = get_the_ID();
				$meta = explode( '_', $option);
				if( $meta[1] == $id )
					echo "<meta name=\"$meta[0]\" content=\"$value\">\n";
			}
		}
		
		public function add_settings()
		{
			//'general', 'reading', 'writing', 'discussion', 'media', etc. Create your own using add_options_page();
			$name = $this->name;
			add_settings_section( $this->tag, $this->name, array(&$this, 'settings_header'), $this->section);
			
			foreach( $this->posts as $post)
			{
				foreach( $this->settings as $key => $options )
				{
					$options['id'] = $key."_".$post->ID;
					$options['post_id'] = $post->ID;
					add_settings_field( 
						$this->tag.'_'.$key.'_'.$post->ID.'_settings', 
						$options['description'], 
						array(&$this, settings_field),
						$this->section, 
						$this->tag,
						$options
					);
				}
			}
			
			register_setting( $this->section, $this->tag );
		}
		
		public function settings_field( array $options = array() )
		{
			
			$atts = array(
				'id' => $this->tag . '_' . $options['id'],
				'name' => $this->tag . '[' . $options['id'] . ']',
				'type' => ( isset( $options['type'] ) ? $options['type'] : 'text' ),
				'class' => 'regular-text code',
				'value' => ( array_key_exists( 'default', $options ) ? $options['default'] : null )
			);
			
			$id = $atts['id'];
			
			if ( isset( $this->options[$options['id']] ) ) {
				$atts['value'] = $this->options[$options['id']];
			}
			if ( isset( $options['placeholder'] ) ) {
				$atts['placeholder'] = $options['placeholder'];
			}
			if ( isset( $options['type'] ) && $options['type'] == 'checkbox' ) {
				if ( $atts['value'] ) {
					$atts['checked'] = 'checked';
				}
				$atts['value'] = true;
			}
			array_walk( $atts, function( &$item, $key ) {
				$item = esc_attr( $key ) . '="' . esc_attr( $item ) . '"';
			} );
			?>
			<label>
				<input <?php echo implode( ' ', $atts ); ?> />
			</label>
			<script>findParentRow(document.querySelector('#<?=$id?>')).className='<?=$this->tag.'_'.$options['post_id']?>_row <?=$this->tag?>_row';</script>
			<?php
	
		}
		
		public function settings_header()
		{
			echo "Bitte w&auml;hlen Sie eine Seite aus ";
			echo "<select id=\"".$this->tag."_select\">";
			echo "<option value=\"---\">---</option>";
			foreach($this->posts as $post) {
				echo "<option value=\"$post->ID\">$post->post_title</option>";
			}
			echo "</select>";
		}
		
		public function load_scripts()
		{
			if ( !wp_script_is( 'jquery', 'done' ) ) 
			{
				wp_enqueue_script('jquery');
			}
			else 
			{
			?>
				<script>			
					function show(clazzShow, showFirst)
					{
						hideAll();
						var rows;
						if(showFirst) rows = document.querySelector('.'+clazzShow);
						else rows = document.querySelectorAll('.'+clazzShow);
						if( rows == null ) return;
						for( var i=0;i<rows.length;i++ )
						{
							rows[i].style.display = 'initial';
						}
					}
					
					function hideAll()
					{
						var nodes = document.querySelectorAll('.<?=$this->tag?>_row');
						for( var i=0;i<nodes.length;i++ )
						{	
							nodes[i].style.display = 'none';
						}
					}
					
					function findParentRow(element)
					{
						while(element.nodeType !== Node.ELEMENT_NODE || element.nodeName !== 'TR') element = element.parentNode;
						return element;
					}
						
					jQuery(document).ready(function($)
					{
						hideAll();
						show('<?=$this->tag?>_row', true);
						var select = document.querySelector('#<?=$this->tag?>_select');
						if( select == null) return;
						select.onchange = function()
						{
							var id = jQuery('#<?=$this->tag?>_select').val();
							show('<?=$this->tag.'_'?>'+id+'_row');
						};
					});
				</script>
			<?
			}
		}
		
		public function getTag()
		{
			return $this->tag;
		}
		
	}
	

	
?>