<?php
class Controller
{
	#region Funções Gerencial
	################################################################################################################
	function __construct()
	{
		$this->Inicializar();
		$this->SetShortCode();
		$this->Acoes();
	}
	################################################################################################################
	public function Inicializar()
	{
		$file = Componente::SetDirPlugin("siup.php");
		register_activation_hook($file, array("Controller", 'Ativar'));
		register_deactivation_hook($file, array("Controller", 'Inativar'));
		register_uninstall_hook($file, array("Controller", 'Desinstalar'));
		add_action('admin_menu', array($this, 'CriarMenu'));
		if( is_admin() )
		{
			add_action('admin_enqueue_scripts', array($this, 'SetAssets'));
		}
		else
		{
			add_action('wp_enqueue_scripts', array($this, 'SetAssets'));
		}
		add_action('init', array($this, 'load_plugin_textdomain'));
		add_action('phpmailer_init', array($this, 'phpmailerInitAction'));
        return;
	}
	################################################################################################################
	public function CriarMenu()
	{
		$icone = Componente::UrlImages("icon.png");
		$titulo = __('Painel de Configurações', SIUP_LANG);
		$acesso = 'manage_options';
		$slugmenu = 'menu_SIUP';
		add_menu_page(__('Painel sistema universal politico', SIUP_LANG), __('SIUP', SIUP_LANG), $acesso, $slugmenu, array($this, 'Admpainel'), $icone, 6 );

		add_submenu_page($slugmenu, $titulo, __('Configurações', SIUP_LANG), $acesso, 'configuracaoSIUP', array($this, 'ControleConfiguracao'));
		add_submenu_page($slugmenu, $titulo, __('Lista de área', SIUP_LANG), $acesso, 'listaareaSIUP', array($this, 'ControleArea'));
		add_submenu_page($slugmenu, $titulo, __('Nova área', SIUP_LANG), $acesso, 'editarareaSIUP', array($this, 'ControleArea'));
		add_submenu_page($slugmenu, $titulo, __('Lista de cliente', SIUP_LANG), $acesso, 'listaclienteSIUP', array($this, 'ControleCliente'));
		add_submenu_page($slugmenu, $titulo, __('Nova cliente', SIUP_LANG), $acesso, 'editarclienteSIUP', array($this, 'ControleCliente'));
		add_submenu_page($slugmenu, $titulo, __('Lista de enquete', SIUP_LANG), $acesso, 'listaenqueteSIUP', array($this, 'ControleEnquete'));
		add_submenu_page($slugmenu, $titulo, __('Nova enquete', SIUP_LANG), $acesso, 'editarenqueteSIUP', array($this, 'ControleEnquete'));
		add_submenu_page($slugmenu, $titulo, __('Lista de enquete', SIUP_LANG), $acesso, 'listaenqueteSIUP', array($this, 'ControleEnquete'));
		add_submenu_page($slugmenu, $titulo, __('Nova enquete', SIUP_LANG), $acesso, 'editarenqueteSIUP', array($this, 'ControleEnquete'));
		add_submenu_page($slugmenu, $titulo, __('Lista de equipe', SIUP_LANG), $acesso, 'listaequipeSIUP', array($this, 'ControleEquipe'));
		add_submenu_page($slugmenu, $titulo, __('Nova equipe', SIUP_LANG), $acesso, 'editarequipeSIUP', array($this, 'ControleEquipe'));


		//add_submenu_page($slugmenu, $titulo, __('teste', SIUP_LANG), $acesso, 'testesiupSIUP', array($this, 'teste'));
		

		return;
	}
	################################################################################################################
	public function SetAssets()
	{
		$this->SetCSS();
		$this->SetJS();
		return;
	}
	################################################################################################################
	public function SetCSS()
	{
		$page = Componente::Request("page");
        if(strripos($page, "SIUP") !== false)
        {
			wp_enqueue_style( 'bootstrap', Componente::UrlVendors('bootstrap/css/bootstrap.min.css' ), array(), 1, 'all' );
		}
		wp_enqueue_style( 'font-awesome', Componente::UrlCss('font-awesome-4.7.0/css/font-awesome.min.css' ), array(), "4.3.0", 'all' );
		wp_enqueue_style( 'elusive-icons', Componente::UrlCss('elusive-icons-2.0.0/css/elusive-icons.min.css' ), array(), "2.0.0", 'all' );
		wp_enqueue_style( 'ionic', Componente::UrlCss('ionic/css/ionic.css' ), array(), 1, 'all' );
		wp_enqueue_style( 'select2', Componente::UrlVendors('select2/css/select2.min.css' ), array(), 1, 'all' );
		wp_enqueue_style( 'jquery-modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css', array(), 1, 'all' );
		if( is_admin() )
		{
			$this->AdmCSS();
			wp_enqueue_style( 'SIUP-adm-css', Componente::UrlCss('main-adm.css' ), array(), 1, 'all' );
		}
		else
		{
			wp_enqueue_style( 'AnythingSlider', Componente::UrlVendors('AnythingSlider-master/css/anythingslider.css' ), array(), 1, 'all' );
			wp_enqueue_style( 'lightbox', Componente::UrlVendors('lightbox/css/lightbox.min.css' ), array(), 1, 'all' );
			wp_enqueue_style( 'theme-metallic', Componente::UrlVendors('AnythingSlider-master/css/theme-metallic.css' ), array(), 1, 'all' );
			wp_enqueue_style( 'siup-css', Componente::UrlCss('main-site.css' ), array(), 1, 'all' );
			add_action('wp_head', array($this,'RegistraCSS'));
		}
		return;
	}
	################################################################################################################
	public function AdmCSS()
	{
		wp_enqueue_media();
		wp_enqueue_editor();
		wp_enqueue_script('media-upload');
		//wp_enqueue_style( 'fileupload-ui', Componente::UrlVendors('jquery-file-upload/css/jquery.fileupload-ui.css' ), array(), 1, 'all' );
		//wp_enqueue_style( 'fileupload', Componente::UrlVendors('jquery-file-upload/css/jquery.fileupload.css' ), array(), 1, 'all' );
		wp_enqueue_style( 'jplist', Componente::UrlVendors('jplist/html/css/jplist-custom.css' ), array(), 1, 'all' );
		wp_enqueue_style( 'dataTables', Componente::UrlVendors('DataTables/datatables.min.css' ), array(), 1, 'all' );
		wp_enqueue_style( 'themesSIUP', Componente::UrlVendors('themes/style1/orange-grey.css' ), array(), 1, 'all' );
		return;
	}
	################################################################################################################
	public function SetJS()
	{
		$page = Componente::Request("page");
        if(strripos($page, "SIUP") !== false)
        {
			wp_enqueue_script( 'siup-bootstrap-ajax', Componente::UrlVendors('bootstrap/js/bootstrap.min.js' ), array('jquery'), false, false );
			wp_enqueue_script( 'iframe-transport', Componente::UrlVendors('jquery-file-upload/js/jquery.iframe-transport.js' ), array('jquery'), false, false );
			wp_enqueue_script( 'fileupload', Componente::UrlVendors('jquery-file-upload/js/jquery.fileupload.js' ), array('jquery'), false, false );
			wp_enqueue_script( 'jquery-ui', Componente::UrlVendors('jplist\html\js\vendor\jquery-ui.js' ), array('jquery'), false, true );
		}
		
		wp_enqueue_script( 'jquery-mask', Componente::UrlVendors('jquery-mask/jquery.mask.min.js' ), array('jquery'), false, false );
		wp_enqueue_script( 'select2', Componente::UrlVendors('select2/js/select2.full.min.js' ), array('jquery'), false, false );
		wp_enqueue_script( 'siup-padrao-ajax', Componente::UrlJs('main-padrao.js' ), array('jquery'), false, false);
		wp_enqueue_script( 'jquery-modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', array('jquery'), false, false );
		//$link = "https://maps.googleapis.com/maps/api/js?key=AIzaSyCmVVS4A14Caaf4seH-2rDVssGqhr25ouk&libraries=places&callback=initMap";
		//wp_enqueue_script( 'googleapis', $link, array('jquery'), false, false );
		if(is_admin())
		{			
			wp_enqueue_script( 'jquery', Componente::UrlVendors('AnythingSlider-master/js/jquery.min.js' ), false, false, false );
			wp_enqueue_script( 'dataTables-js', Componente::UrlVendors('DataTables/datatables.min.js' ), array('jquery'), false, false );
			wp_enqueue_script( 'siup-adm-ajax', Componente::UrlJs('main-adm.js' ), array('jquery'), false, false );
		}
		else
		{
			wp_enqueue_script( 'jquery', Componente::UrlVendors('AnythingSlider-master/js/jquery.min.js' ), false, false, false );
			wp_enqueue_script( 'AnythingSlider', Componente::UrlVendors('AnythingSlider-master/js/jquery.anythingslider.min.js' ), array('jquery'), false, false );
			wp_enqueue_script( 'lightbox', Componente::UrlVendors('lightbox/js/lightbox.min.js' ), array('jquery'), false, false );
			wp_enqueue_script( 'siup-ajax', Componente::UrlJs('main-site.js' ), array('jquery'), false, false);
		}
		return;
	}
	################################################################################################################
	public function SetShortCode()
	{
		if( !is_admin() )
		{
			add_shortcode( "exibepagina", array($this, 'ExibePagina'));
		}
		return;
	}
	################################################################################################################
	public function Acoes()
	{
		if( is_admin() )
		{
			add_action('wp_ajax_salvarconfiguracoes', array($this, 'ControleConfiguracao'));
			add_action('wp_ajax_buscarcep', array($this, 'BuscarCep'));
			add_action('wp_ajax_area', array($this, 'ControleArea'));
			add_action('wp_ajax_nopriv_area', array($this, 'ControleArea'));
			add_action('wp_ajax_cliente', array($this, 'ControleCliente'));
			add_action('wp_ajax_nopriv_cliente', array($this, 'ControleCliente'));
			add_action('wp_ajax_enquete', array($this, 'ControleEnquete'));
			add_action('wp_ajax_nopriv_enquete', array($this, 'ControleEnquete'));
			add_action('wp_ajax_equipe', array($this, 'ControleEquipe'));
			add_action('wp_ajax_nopriv_equipe', array($this, 'ControleEquipe'));
			
			add_action('wp_ajax_nopriv_buscarcep', array($this, 'BuscarCep'));
		}
        else
        {
	        require SIUP_LIBRARIES_PATH.'WP_Route.php';	        
			WP_Route::get('/teste/{id}', array($this,'Teste'));
			WP_Route::get('/baixararea/{file}', array($this,'ControleArea'));
			WP_Route::get('/baixarcliente/{file}', array($this,'ControleCliente'));
			WP_Route::get('/baixarenquete/{file}', array($this,'ControleEnquete'));
			WP_Route::get('/baixarequipe/{file}', array($this,'ControleEquipe'));
			

	        add_action('after_setup_theme', array($this,'remove_admin_bar'));
	        add_filter( 'excerpt_length', array($this,'Limitepost'),9999 );
	        add_filter( 'excerpt_more', array($this,'new_excerpt_more') );
        }		
		
		return;
	}
	################################################################################################################
	public function phpmailerInitAction()
	{
		global $phpmailer;
		if (!( $phpmailer instanceof PHPMailer )) {
			require_once ABSPATH . WPINC . '/class-phpmailer.php';
			require_once ABSPATH . WPINC . '/class-smtp.php';
			$phpmailer = new PHPMailer(true);
		}
		$phpmailer->AddEmbeddedImage(SIUP_IMAGES_PATH . '/headeremail.jpg', 'header');
		$phpmailer->AddEmbeddedImage(SIUP_IMAGES_PATH . '/footeremail.png', 'footer');
		$phpmailer->IsHTML( true );
		$phpmailer->isSMTP ();
	    $phpmailer->Host = 'mail.dominio.com.br';
	    $phpmailer->SMTPAuth = true; // Força que use o nome de usuário e a senha para autenticar
	    $phpmailer->porta = 465;
	    $phpmailer->Username = 'contato@dominio.com.br';
	    $phpmailer->Password = '@fes;ta#clubi';
	
	    // Configurações adicionais…
	    $phpmailer->SMTPSecure = "tls"; // Escolha SSL ou TLS, se necessário para o seu servidor
	    $phpmailer->From = "contato@dominio.com.br";
	    $phpmailer->FromName = "dominio.com.br";
		return;
	}
	#endregion
	#region Funções Gerais
	################################################################################################################
	public static function Ativar()
	{
		$obj = Componente::GetLibrary("instalador");
		$obj->Instalar();
		return;
	}
	################################################################################################################
	public static function Inativar()
	{
		$obj = Componente::GetLibrary("instalador");
		$obj->Desativar();
		return;
	}
	################################################################################################################
	public static function Desinstalar()
	{
		$obj = Componente::GetLibrary("instalador");
		$obj->Uninstall();
		return;
	}
	################################################################################################################
	function remove_admin_bar()
	{
		if ((current_user_can('usuario'))||(current_user_can('fornecedor'))) {
			show_admin_bar(false);
		}
	}
	################################################################################################################
	public function load_plugin_textdomain()
	{
		
		global $wp_locale;
		if(empty($wp_locale))
		{
			$wp_locale = new WP_Locale;
		}	
		$domain = SIUP_LANG;
		$locale = apply_filters('plugin_locale', get_locale(), $domain);
	
		load_textdomain($domain, trailingslashit(WP_LANG_DIR) . SIUP_PLUGIN_NOME. '/' . $domain . '-' . $locale . '.mo');
		load_plugin_textdomain($domain, FALSE, SIUP_LANGUAGE_PATH);
	}
	#endregion
	#region Funções admin	
	################################################################################################################
	public function Admpainel()
	{
		$obj = Componente::GetControle("adm");
		$obj->Painel();
		return;
	}
	################################################################################################################
	public function ControleArea($file = "")
	{
		$obj = Componente::GetControle("area");
		$obj->Controle($file);
		return;
	}
	################################################################################################################
	public function ControleCliente($file = "")
	{
		$obj = Componente::GetControle("cliente");
		$obj->Controle($file);
		return;
	}
	################################################################################################################
	public function ControleEnquete($file = "")
	{
		$obj = Componente::GetControle("enquete");
		$obj->Controle($file);
		return;
	}
	################################################################################################################
	public function ControleEquipe($file = "")
	{
		$obj = Componente::GetControle("equipe");
		$obj->Controle($file);
		return;
	}
	################################################################################################################
	public function ControleEvento($file = "")
	{
		$obj = Componente::GetControle("evento");
		$obj->Controle($file);
		return;
	}
	################################################################################################################
	public function ControleMidiassocial($file = "")
	{
		$obj = Componente::GetControle("midiassocial");
		$obj->Controle($file);
		return;
	}
	###############################################################################################################
	public function ControlePedido($file = "")
	{
		$obj = Componente::GetControle("pedido");
		$obj->Controle($file);
		return;
	}
	#endregion
	#region Funções Shortcode
	################################################################################################################
	public function ExibePagina($attribs, $content = null, $code)
	{
		$obj = Componente::GetControle("site");
        return $obj->ExibePagina($attribs, $content, $code);
	}	
	#endregion
	#region Funções Site
	################################################################################################################
	public function RegistraCSS()
	{
	    $opcao = Componente::GetLibrary("opcao");
		$CSS = $opcao->GetOpcao("padraocss");
		$id = $opcao->GetOpcao("idpesquisa");
		if(!empty($id))
		{
			$CSS .= "\n.site-main #post-{$id} header.entry-header{display: none;}";
		}
		if(!empty($CSS))
		{
			echo "<style>\n{$CSS}\n</style>";
		}
		return;
	}
	################################################################################################################
	public function Limitepost( $length )
	{
		return 10;
	}
	################################################################################################################
	public function new_excerpt_more( $more ) {
		return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Ler mais', HOST_LANG) . '</a>';
	}
	#endregion
	#region Funções de Pagina	
	################################################################################################################
	public function Teste()
	{
		$obj = Componente::GetControle("acoes");
		wp_die();
	}
	#endregion
}
?>